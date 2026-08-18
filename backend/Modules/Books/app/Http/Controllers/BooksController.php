<?php

namespace Modules\Books\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Books\Models\Book;

class BooksController extends Controller
{
    /**
     * Display a listing of books.
     */
    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->filled('subject')) {
            $query->where('subject', $request->get('subject'));
        }

        if ($request->filled('language')) {
            $query->where('language', $request->get('language'));
        }

        if ($request->filled('author')) {
            $query->where('author', 'like', '%' . $request->get('author') . '%');
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->get('search') . '%');
        }

        $query->orderBy('title');

        $books = $query->paginate((int) $request->get('per_page', 15));

        return apiResponse($books, 'Books fetched successfully', true, 200);
    }

    /**
     * Store a new book.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), self::validationRules());

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        $book = Book::create($request->only(self::fillableFields()));

        return apiResponse($book, 'Book created successfully', true, 201);
    }

    /**
     * Display a book.
     */
    public function show($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return apiResponse(null, 'Book not found', false, 404);
        }

        return apiResponse($book, 'Book fetched successfully', true, 200);
    }

    /**
     * Update a book.
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return apiResponse(null, 'Book not found', false, 404);
        }

        $validator = Validator::make($request->all(), self::validationRules(false));

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        $book->fill($request->only(self::fillableFields()));
        $book->save();

        return apiResponse($book, 'Book updated successfully', true, 200);
    }

    /**
     * Delete a book.
     */
    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return apiResponse(null, 'Book not found', false, 404);
        }

        $book->delete();

        return apiResponse(null, 'Book deleted successfully', true, 200);
    }

    private static function fillableFields(): array
    {
        return [
            'title',
            'author',
            'language',
            'subject',
            'publisher',
            'isbn',
            'publication_year',
            'edition',
            'cover_path',
            'description',
        ];
    }

    private static function validationRules(bool $titleRequired = true): array
    {
        return [
            'title' => $titleRequired ? 'required|string|max:255' : 'sometimes|required|string|max:255',
            'author' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:120',
            'subject' => 'nullable|string|max:120',
            'publisher' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|max:32',
            'publication_year' => 'nullable|integer|min:1400|max:2100',
            'edition' => 'nullable|string|max:60',
            'cover_path' => 'nullable|string',
            'description' => 'nullable|string',
        ];
    }
}
