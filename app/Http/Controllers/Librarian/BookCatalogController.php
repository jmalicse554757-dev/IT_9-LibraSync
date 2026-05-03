<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\College;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookCatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('college');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function($builder) use ($q) {
                $builder->where('title',    'like', "%$q%")
                        ->orWhere('author', 'like', "%$q%")
                        ->orWhere('book_id','like', "%$q%")
                        ->orWhere('isbn',   'like', "%$q%");
            });
        }

        if ($request->filled('college_id')) {
            $query->where('college_id', $request->college_id);
        }

        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                $query->where('stock', '>', 3);
            } elseif ($request->availability === 'low stock') {
                $query->where('stock', '>', 0)->where('stock', '<=', 3);
            } elseif ($request->availability === 'unavailable') {
                $query->where('stock', 0);
            }
        }

        $books    = $query->latest()->paginate(15)->withQueryString();
        $colleges = College::orderBy('name')->get();

        return view('librarian.book-catalog', compact('books', 'colleges'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'author'        => 'required|string|max:255',
            'publisher'     => 'nullable|string|max:255',
            'year_published'=> 'nullable|integer',
            'edition'       => 'nullable|string|max:100',
            'isbn'          => 'nullable|string|max:50',
            'category'      => 'nullable|string|max:100',
            'college_id'    => 'nullable|exists:colleges,id',
            'program'       => 'nullable|string|max:255',
            'shelf_location'=> 'nullable|string|max:100',
            'stock'         => 'required|integer|min:0',
            'description'   => 'nullable|string',
            'cover_image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $data = $request->except('cover_image');

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        Book::create($data);

        return back()->with('success', 'Book added successfully!');
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'author'        => 'required|string|max:255',
            'publisher'     => 'nullable|string|max:255',
            'year_published'=> 'nullable|integer',
            'edition'       => 'nullable|string|max:100',
            'isbn'          => 'nullable|string|max:50',
            'category'      => 'nullable|string|max:100',
            'college_id'    => 'nullable|exists:colleges,id',
            'program'       => 'nullable|string|max:255',
            'shelf_location'=> 'nullable|string|max:100',
            'stock'         => 'required|integer|min:0',
            'description'   => 'nullable|string',
            'cover_image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $data = $request->except('cover_image');

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book->update($data);

        return back()->with('success', 'Book updated successfully!');
    }

    public function destroy(Book $book)
    {
        if ($book->cover_image) Storage::disk('public')->delete($book->cover_image);
        $book->delete();
        return back()->with('success', 'Book deleted successfully.');
    }
}