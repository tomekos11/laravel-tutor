<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Modules\Users\Models\Conversation;
use Modules\Users\Models\User;
use Modules\Users\Models\UserConversation;

class UsersController extends Controller
{
    /**
     * Wyszukiwarka użytkowników do wyboru odbiorcy nowej wiadomości/grupy.
     *
     * Bez zapytania (albo zbyt krótkiego) podpowiada osoby, z którymi
     * zalogowany użytkownik już rozmawiał — posortowane od najświeższej
     * konwersacji. Z zapytaniem wyszukuje po imieniu/nazwisku/loginie/emailu,
     * ale wcześniejszych rozmówców podbija na górę listy.
     */
    public function search(Request $request): JsonResponse
    {
        $query = trim((string) $request->get('q', ''));
        $authId = $request->user()->id;
        $contactIds = $this->contactUserIds($authId);

        if (mb_strlen($query) < 2) {
            $contacts = $this->recentContacts($authId, 8);

            return response()->json(['data' => $this->formatUsers($contacts, $contactIds)]);
        }

        $users = User::query()
            ->where('id', '!=', $authId)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('surname', 'like', "%{$query}%")
                    ->orWhere('username', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->limit(30)
            ->get(['id', 'name', 'surname', 'username', 'email', 'image']);

        $sorted = $users
            ->sortByDesc(fn (User $u) => $contactIds->contains($u->id))
            ->values()
            ->take(15);

        return response()->json(['data' => $this->formatUsers($sorted, $contactIds)]);
    }

    /**
     * Id użytkowników, z którymi zalogowany użytkownik ma wspólną konwersację.
     */
    private function contactUserIds(int $authId): Collection
    {
        $conversationIds = UserConversation::where('member_id', $authId)->pluck('conversation_id');

        return UserConversation::whereIn('conversation_id', $conversationIds)
            ->where('member_id', '!=', $authId)
            ->pluck('member_id')
            ->unique()
            ->values();
    }

    /**
     * Ostatni rozmówcy (unikalni), posortowani od najświeższej aktywności konwersacji.
     */
    private function recentContacts(int $authId, int $limit): Collection
    {
        $conversationIds = UserConversation::where('member_id', $authId)->pluck('conversation_id');

        $conversations = Conversation::whereIn('id', $conversationIds)
            ->orderByDesc('updated_at')
            ->with('members')
            ->get();

        $seen = [];
        $contacts = collect();

        foreach ($conversations as $conversation) {
            foreach ($conversation->members as $member) {
                if ($member->id === $authId || isset($seen[$member->id])) {
                    continue;
                }
                $seen[$member->id] = true;
                $contacts->push($member);

                if ($contacts->count() >= $limit) {
                    return $contacts;
                }
            }
        }

        return $contacts;
    }

    private function formatUsers(Collection $users, Collection $contactIds): array
    {
        return $users->map(fn (User $u) => [
            'id'         => $u->id,
            'name'       => $u->name,
            'surname'    => $u->surname,
            'username'   => $u->username,
            'image'      => $u->image,
            'is_contact' => $contactIds->contains($u->id),
        ])->values()->all();
    }
}
