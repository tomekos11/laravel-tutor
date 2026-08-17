<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Modules\Users\Models\School;
use Modules\Users\Models\UserSchool;

class EducationController extends Controller
{
    /**
     * Lista wpisów edukacji zalogowanego użytkownika.
     */
    public function index(Request $request): JsonResponse
    {
        $entries = UserSchool::with('school')
            ->where('user_id', $request->user()->id)
            ->orderByDesc('begin_date')
            ->get();

        return response()->json([
            'data' => $entries->map(fn (UserSchool $entry) => $this->format($entry))->all(),
        ]);
    }

    /**
     * Dodaje nowy wpis edukacji (np. szkoła/uczelnia + kierunek).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rules());

        $school = $this->findOrCreateSchool($validated['school_name'], $validated['city'] ?? null);

        $entry = UserSchool::create([
            'user_id'    => $request->user()->id,
            'school_id'  => $school->id,
            'title'      => $validated['title'],
            'type'       => $validated['type'],
            'begin_date' => $validated['begin_date'],
            'end_date'   => $validated['end_date'] ?? null,
        ]);

        $entry->load('school');

        return response()->json([
            'message' => 'Wpis edukacji został dodany.',
            'data'    => $this->format($entry),
        ], 201);
    }

    /**
     * Aktualizuje wpis edukacji należący do zalogowanego użytkownika.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $entry = UserSchool::where('user_id', $request->user()->id)->findOrFail($id);

        $validated = $request->validate($this->rules(true));

        if (array_key_exists('school_name', $validated)) {
            $school = $this->findOrCreateSchool($validated['school_name'], $validated['city'] ?? null);
            $entry->school_id = $school->id;
        }

        $entry->fill(array_filter([
            'title'      => $validated['title'] ?? null,
            'type'       => $validated['type'] ?? null,
            'begin_date' => $validated['begin_date'] ?? null,
        ], static fn ($value) => $value !== null));

        if (array_key_exists('end_date', $validated)) {
            $entry->end_date = $validated['end_date'];
        }

        $entry->save();
        $entry->load('school');

        return response()->json([
            'message' => 'Wpis edukacji został zaktualizowany.',
            'data'    => $this->format($entry),
        ]);
    }

    /**
     * Usuwa wpis edukacji należący do zalogowanego użytkownika.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $entry = UserSchool::where('user_id', $request->user()->id)->findOrFail($id);
        $entry->delete();

        return response()->json([
            'message' => 'Wpis edukacji został usunięty.',
        ]);
    }

    private function rules(bool $isUpdate = false): array
    {
        $prefix = $isUpdate ? 'sometimes' : 'required';

        return [
            'title'       => "$prefix|string|max:255",
            'type'        => ['sometimes', Rule::in(['szkoła', 'studia', 'kurs', 'inne'])],
            'school_name' => "$prefix|string|max:255",
            'city'        => 'sometimes|nullable|string|max:255',
            'begin_date'  => "$prefix|date",
            'end_date'    => 'sometimes|nullable|date|after_or_equal:begin_date',
        ];
    }

    private function findOrCreateSchool(string $name, ?string $city): School
    {
        $name = trim($name);

        return School::firstOrCreate(
            ['name' => $name],
            ['type' => 'inne', 'coordinates' => '', 'city' => $city ?? '', 'country' => 'Polska']
        );
    }

    private function format(UserSchool $entry): array
    {
        return [
            'id'         => $entry->id,
            'title'      => $entry->title,
            'type'       => $entry->type,
            'school'     => $entry->school?->name,
            'city'       => $entry->school?->city,
            'begin_date' => optional($entry->begin_date)->format('Y-m-d'),
            'end_date'   => optional($entry->end_date)->format('Y-m-d'),
        ];
    }
}
