<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Users\Models\Conversation;
use Modules\Users\Models\Message;
use Modules\Users\Models\User;
use Modules\Users\Models\UserConversation;

class ConversationsController extends Controller
{
    /**
     * Lista konwersacji zalogowanego użytkownika (1-1 i grupowe), posortowana
     * od ostatnio aktywnej, z podglądem ostatniej wiadomości i licznikiem nieprzeczytanych.
     */
    public function index(Request $request): JsonResponse
    {
        $authId = $request->user()->id;

        $conversationIds = UserConversation::where('member_id', $authId)->pluck('conversation_id');

        $conversations = Conversation::with('members')
            ->whereIn('id', $conversationIds)
            ->orderByDesc('updated_at')
            ->get();

        $myPivots = UserConversation::where('member_id', $authId)
            ->whereIn('conversation_id', $conversationIds)
            ->get()
            ->keyBy('conversation_id');

        // Uwaga: eager-load `latest()->limit(1)` na relacji hasMany dla wielu
        // konwersacji naraz zwraca jedną wiadomość ŁĄCZNIE (limit działa na całe
        // zapytanie, nie per-konwersacja), więc ostatnie wiadomości pobieramy
        // osobnym zapytaniem i grupujemy w PHP.
        $lastMessageByConversation = Message::whereIn('conversation_id', $conversationIds)
            ->with(['user', 'advertisement'])
            ->orderByDesc('id')
            ->get()
            ->groupBy('conversation_id')
            ->map(fn ($messages) => $messages->first());

        $data = $conversations->map(function (Conversation $conversation) use ($authId, $myPivots, $lastMessageByConversation) {
            return $this->formatSummary(
                $conversation,
                $authId,
                $myPivots->get($conversation->id),
                $lastMessageByConversation->get($conversation->id)
            );
        })->values();

        return response()->json(['data' => $data]);
    }

    /**
     * Tworzy nową konwersację (1-1 lub grupową) razem z pierwszą wiadomością.
     * Dla rozmów 1-1 (dokładnie jeden odbiorca) ponownie wykorzystuje istniejącą
     * rozmowę między tymi samymi dwiema osobami zamiast tworzyć duplikat.
     */
    public function store(Request $request): JsonResponse
    {
        $authId = $request->user()->id;

        $validated = $request->validate([
            'participant_ids'   => 'required|array|min:1',
            'participant_ids.*' => 'integer|exists:user__users,id',
            'title'             => 'nullable|string|max:150',
            'message'           => 'required|string|max:5000',
            'advertisement_id'  => 'nullable|integer|exists:advertisement__advertisements,id',
        ]);

        $participantIds = collect($validated['participant_ids'])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->reject(fn ($id) => $id === $authId)
            ->values();

        if ($participantIds->isEmpty()) {
            return response()->json(['message' => 'Wybierz co najmniej jednego odbiorcę.'], 422);
        }

        $isGroup = $participantIds->count() > 1;

        if ($isGroup && empty($validated['title'])) {
            return response()->json(['message' => 'Nazwa grupy jest wymagana dla rozmowy grupowej.'], 422);
        }

        if (!$isGroup) {
            $existing = $this->findExistingDirectConversation($authId, $participantIds->first());

            if ($existing) {
                $message = $existing->messages()->create([
                    'creator_id'       => $authId,
                    'content'          => $validated['message'],
                    'advertisement_id' => $validated['advertisement_id'] ?? null,
                ]);
                $existing->touch();
                $this->markRead($existing->id, $authId);

                $pivot = UserConversation::where('conversation_id', $existing->id)->where('member_id', $authId)->first();

                return response()->json([
                    'message' => 'Wiadomość wysłana.',
                    'data'    => $this->formatSummary($existing->fresh(['members']), $authId, $pivot, $message),
                ], 200);
            }
        }

        $conversation = Conversation::create([
            'owner_id' => $authId,
            'type'     => $isGroup ? 'group' : 'direct',
            'title'    => $isGroup ? ($validated['title'] ?? null) : null,
        ]);

        $now = now();
        UserConversation::create(['conversation_id' => $conversation->id, 'member_id' => $authId, 'last_read_at' => $now]);
        foreach ($participantIds as $participantId) {
            UserConversation::create(['conversation_id' => $conversation->id, 'member_id' => $participantId]);
        }

        $message = $conversation->messages()->create([
            'creator_id'       => $authId,
            'content'          => $validated['message'],
            'advertisement_id' => $validated['advertisement_id'] ?? null,
        ]);

        $conversation->load('members');
        $pivot = UserConversation::where('conversation_id', $conversation->id)->where('member_id', $authId)->first();

        return response()->json([
            'message' => 'Konwersacja została utworzona.',
            'data'    => $this->formatSummary($conversation, $authId, $pivot, $message),
        ], 201);
    }

    /**
     * Szczegóły konwersacji (uczestnicy) — bez wiadomości, te pobiera się osobno (paginacja).
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $authId = $request->user()->id;
        $conversation = $this->authorizedConversation($id, $authId);

        if (!$conversation) {
            return response()->json(['message' => 'Konwersacja nie istnieje lub brak dostępu.'], 404);
        }

        $conversation->load('members');
        $pivot = UserConversation::where('conversation_id', $conversation->id)->where('member_id', $authId)->first();
        $lastMessage = $conversation->messages()->with(['user', 'advertisement'])->orderByDesc('id')->first();

        return response()->json(['data' => $this->formatSummary($conversation, $authId, $pivot, $lastMessage)]);
    }

    /**
     * Wiadomości konwersacji, stronicowane (najnowsze na końcu listy).
     */
    public function messages(Request $request, int $id): JsonResponse
    {
        $authId = $request->user()->id;
        $conversation = $this->authorizedConversation($id, $authId);

        if (!$conversation) {
            return response()->json(['message' => 'Konwersacja nie istnieje lub brak dostępu.'], 404);
        }

        $perPage = max(1, min(100, (int) $request->get('per_page', 50)));

        $messages = $conversation->messages()
            ->with(['user', 'advertisement'])
            ->orderByDesc('id')
            ->paginate($perPage);

        $data = collect($messages->items())
            ->map(fn (Message $m) => $this->formatMessage($m))
            ->reverse()
            ->values();

        return response()->json([
            'data' => $data,
            'meta' => [
                'current_page' => $messages->currentPage(),
                'last_page'    => $messages->lastPage(),
                'total'        => $messages->total(),
            ],
        ]);
    }

    /**
     * Wysyła nową wiadomość w konwersacji.
     */
    public function sendMessage(Request $request, int $id): JsonResponse
    {
        $authId = $request->user()->id;
        $conversation = $this->authorizedConversation($id, $authId);

        if (!$conversation) {
            return response()->json(['message' => 'Konwersacja nie istnieje lub brak dostępu.'], 404);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:5000',
            'img'     => 'nullable|string|max:2048',
        ]);

        $message = $conversation->messages()->create([
            'creator_id' => $authId,
            'content'    => $validated['content'],
            'img'        => $validated['img'] ?? null,
        ]);

        $conversation->touch();
        $this->markRead($conversation->id, $authId);

        $message->load('user');

        return response()->json([
            'message' => 'Wiadomość wysłana.',
            'data'    => $this->formatMessage($message),
        ], 201);
    }

    /**
     * Oznacza konwersację jako przeczytaną przez zalogowanego użytkownika.
     */
    public function markAsRead(Request $request, int $id): JsonResponse
    {
        $authId = $request->user()->id;
        $conversation = $this->authorizedConversation($id, $authId);

        if (!$conversation) {
            return response()->json(['message' => 'Konwersacja nie istnieje lub brak dostępu.'], 404);
        }

        $this->markRead($id, $authId);

        return response()->json(['message' => 'Oznaczono jako przeczytane.']);
    }

    /**
     * Dodaje uczestnika do konwersacji grupowej (dowolny obecny członek może zapraszać).
     */
    public function addParticipant(Request $request, int $id): JsonResponse
    {
        $authId = $request->user()->id;
        $conversation = $this->authorizedConversation($id, $authId);

        if (!$conversation) {
            return response()->json(['message' => 'Konwersacja nie istnieje lub brak dostępu.'], 404);
        }

        if ($conversation->type !== 'group') {
            return response()->json(['message' => 'Do rozmowy 1-1 nie można dodawać kolejnych osób.'], 422);
        }

        $validated = $request->validate([
            'user_id' => 'required|integer|exists:user__users,id',
        ]);

        $alreadyMember = UserConversation::where('conversation_id', $id)
            ->where('member_id', $validated['user_id'])
            ->exists();

        if ($alreadyMember) {
            return response()->json(['message' => 'Ta osoba już jest w grupie.'], 422);
        }

        UserConversation::create(['conversation_id' => $id, 'member_id' => $validated['user_id']]);

        $newMember = User::find($validated['user_id']);
        $joinedName = trim(($newMember?->name ?? '') . ' ' . ($newMember?->surname ?? '')) ?: ($newMember?->username ?? 'Nowa osoba');

        $conversation->messages()->create([
            'creator_id' => $validated['user_id'],
            'type'       => 'system',
            'content'    => "{$joinedName} dołączył(a) do czatu",
        ]);
        $conversation->touch();

        $conversation->load('members');

        $pivot = UserConversation::where('conversation_id', $id)->where('member_id', $authId)->first();
        $lastMessage = $conversation->messages()->with(['user', 'advertisement'])->orderByDesc('id')->first();

        return response()->json([
            'message' => 'Dodano uczestnika.',
            'data'    => $this->formatSummary($conversation, $authId, $pivot, $lastMessage),
        ]);
    }

    // --- Pomocnicze ---

    private function authorizedConversation(int $id, int $authId): ?Conversation
    {
        $isMember = UserConversation::where('conversation_id', $id)->where('member_id', $authId)->exists();

        if (!$isMember) {
            return null;
        }

        return Conversation::find($id);
    }

    private function findExistingDirectConversation(int $authId, int $otherId): ?Conversation
    {
        return Conversation::query()
            ->where('type', 'direct')
            ->whereHas('conversationUsers', fn ($q) => $q->where('member_id', $authId))
            ->whereHas('conversationUsers', fn ($q) => $q->where('member_id', $otherId))
            ->withCount('conversationUsers')
            ->get()
            ->first(fn (Conversation $c) => $c->conversation_users_count === 2);
    }

    private function markRead(int $conversationId, int $authId): void
    {
        UserConversation::where('conversation_id', $conversationId)
            ->where('member_id', $authId)
            ->update(['last_read_at' => now()]);
    }

    private function formatSummary(Conversation $conversation, int $authId, ?UserConversation $myPivot, ?Message $lastMessage = null): array
    {
        $otherMembers = $conversation->members->where('id', '!=', $authId)->values();

        $lastReadAt = $myPivot?->last_read_at;
        $unreadCount = $conversation->messages()
            ->where('creator_id', '!=', $authId)
            ->when($lastReadAt, fn ($q) => $q->where('created_at', '>', $lastReadAt))
            ->count();

        if ($conversation->type === 'group') {
            $displayTitle = $conversation->title;
        } else {
            $other = $otherMembers->first();
            $fullName = trim(($other?->name ?? '') . ' ' . ($other?->surname ?? ''));
            $displayTitle = $fullName !== '' ? $fullName : $other?->username;
        }

        return [
            'id'            => $conversation->id,
            'type'          => $conversation->type,
            'title'         => $displayTitle,
            'owner_id'      => $conversation->owner_id,
            'members'       => $otherMembers->map(fn (User $u) => [
                'id'      => $u->id,
                'name'    => $u->name,
                'surname' => $u->surname,
                'image'   => $u->image,
            ])->all(),
            'last_message'  => $lastMessage ? $this->formatMessage($lastMessage) : null,
            'unread_count'  => $unreadCount,
            'updated_at'    => $conversation->updated_at,
        ];
    }

    private function formatMessage(Message $message): array
    {
        $sender = $message->user;
        $advertisement = $message->advertisement_id ? $message->advertisement : null;

        return [
            'id'               => $message->id,
            'conversation_id'  => $message->conversation_id,
            'type'             => $message->type ?? 'text',
            'content'          => $message->content,
            'img'              => $message->img,
            'created_at'       => $message->created_at,
            'sender'           => [
                'id'      => $sender?->id,
                'name'    => $sender?->name,
                'surname' => $sender?->surname,
                'image'   => $sender?->image,
            ],
            'advertisement_id' => $message->advertisement_id,
            'advertisement'    => $advertisement ? [
                'id'          => $advertisement->id,
                'description' => $advertisement->description,
                'price'       => $advertisement->price,
            ] : null,
        ];
    }
}
