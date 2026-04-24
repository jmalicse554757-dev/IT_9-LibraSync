@extends('layouts.librarian')

@section('title', 'Book Catalog')
@section('page-title', 'Book Catalog')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;">
    <div>
        <h1 style="font-family:'Playfair Display',serif;font-size:26px;font-weight:700;color:var(--maroon-deep);">Book Catalog</h1>
        <p style="color:var(--text-muted);font-size:13px;">Manage the library collection</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('modalAddBook')">+ Add Book</button>
</div>

{{-- ALERTS --}}
@if(session('success'))
<div style="background:rgba(39,174,96,0.1);border:1px solid rgba(39,174,96,0.3);border-radius:10px;padding:12px 16px;margin-bottom:18px;color:#27ae60;font-size:13px;font-weight:600;">
    ✓ {{ session('success') }}
</div>
@endif

{{-- SEARCH --}}
<div style="margin-bottom:18px;">
    <form method="GET" action="{{ route('librarian.book-catalog') }}">
        <div style="position:relative;">
            <svg style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-muted);" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title, author, ISBN, Book ID..."
                style="width:100%;padding:10px 14px 10px 40px;border:1px solid var(--border);border-radius:10px;font-size:13px;background:var(--white);color:var(--text-dark);outline:none;font-family:'Lato',sans-serif;">
        </div>
    </form>
</div>

{{-- TABLE --}}
<div class="card" style="padding:0;overflow:hidden;">
    <table class="tbl">
        <thead>
            <tr>
                <th>Book ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Program</th>
                <th>Category</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($books as $book)
            <tr>
                <td style="color:var(--red-main);font-weight:700;font-size:12px;">{{ $book->book_id }}</td>
                <td style="font-weight:600;max-width:200px;">{{ $book->title }}</td>
                <td style="color:var(--text-muted);">{{ $book->author }}</td>
                <td>
                    <span class="prog-badge prog-cce">{{ $book->program }}</span>
                </td>
                <td style="color:var(--text-muted);font-size:12px;">{{ $book->category }}</td>
                <td style="font-weight:700;color:var(--maroon-deep);">{{ $book->stock }}</td>
                <td>
                    @if($book->status === 'unavailable')
                        <span class="badge badge-rejected">Unavailable</span>
                    @elseif($book->status === 'low stock')
                        <span class="badge badge-pending">Low Stock</span>
                    @else
                        <span class="badge badge-active">Available</span>
                    @endif
                </td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <button onclick="openEdit({{ $book->id }})" style="background:rgba(107,0,0,0.08);border:1px solid var(--border);border-radius:6px;padding:5px 8px;cursor:pointer;color:var(--maroon-mid);">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <form method="POST" action="{{ route('librarian.book-catalog.destroy', $book) }}" onsubmit="return confirm('Delete this book?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:rgba(192,57,43,0.08);border:1px solid rgba(192,57,43,0.2);border-radius:6px;padding:5px 8px;cursor:pointer;color:#c0392b;">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>

            {{-- Hidden edit data --}}
            <div id="editData-{{ $book->id }}" style="display:none;"
                data-id="{{ $book->id }}"
                data-title="{{ $book->title }}"
                data-author="{{ $book->author }}"
                data-isbn="{{ $book->isbn }}"
                data-publisher="{{ $book->publisher }}"
                data-year="{{ $book->year_published }}"
                data-edition="{{ $book->edition }}"
                data-college="{{ $book->college_id }}"
                data-program="{{ $book->program }}"
                data-category="{{ $book->category }}"
                data-stock="{{ $book->stock }}"
                data-shelf="{{ $book->shelf_location }}"
                data-description="{{ $book->description }}"
                data-bookid="{{ $book->book_id }}"
            ></div>

            @empty
            <tr>
                <td colspan="8" style="text-align:center;color:var(--text-muted);padding:40px;">No books found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- PAGINATION --}}
@if($books->hasPages())
<div style="margin-top:16px;display:flex;justify-content:flex-end;">
    {{ $books->links() }}
</div>
@endif

@endsection

{{-- MODALS --}}
@section('modals')

{{-- ADD BOOK MODAL --}}
<div class="modal-overlay" id="modalAddBook">
    <div class="modal" style="max-width:680px;">
        <button class="modal-close" onclick="closeModal('modalAddBook')">✕</button>
        <div class="modal-title">Add New Book</div>

        <form method="POST" action="{{ route('librarian.book-catalog.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Cover Image --}}
            <div style="margin-bottom:18px;">
                <label style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);display:block;margin-bottom:6px;">Book Cover Image</label>
                <div id="coverDropzone" onclick="document.getElementById('coverInput').click()"
                    style="border:2px dashed var(--border);border-radius:10px;padding:24px;text-align:center;cursor:pointer;background:var(--cream);transition:all .2s;">
                    <svg width="28" height="28" fill="none" stroke="var(--text-muted)" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 8px;display:block;"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    <div style="font-size:12px;color:var(--text-muted);">Click to upload book cover</div>
                    <div style="font-size:10px;color:var(--text-muted);margin-top:2px;">JPG, PNG, WEBP · Max 5MB</div>
                    <div id="coverFileName" style="font-size:11px;color:var(--red-main);margin-top:6px;font-weight:600;"></div>
                </div>
                <input type="file" id="coverInput" name="cover_image" accept="image/*" style="display:none;" onchange="document.getElementById('coverFileName').textContent = this.files[0]?.name ?? ''">
            </div>

            {{-- Book ID + ISBN --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                <div>
                    <label class="form-label">Book ID (Auto)</label>
                    <input type="text" value="LIB-XXXX" disabled class="form-input" style="background:var(--cream);color:var(--text-muted);">
                </div>
                <div>
                    <label class="form-label">ISBN</label>
                    <input type="text" name="isbn" placeholder="978-..." class="form-input">
                </div>
            </div>

            {{-- Title --}}
            <div style="margin-bottom:12px;">
                <label class="form-label">Title <span style="color:var(--red-main);">*</span></label>
                <input type="text" name="title" placeholder="Book title" class="form-input" required>
            </div>

            {{-- Author + Publisher --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                <div>
                    <label class="form-label">Author <span style="color:var(--red-main);">*</span></label>
                    <input type="text" name="author" placeholder="Author name" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Publisher</label>
                    <input type="text" name="publisher" placeholder="Publisher" class="form-input">
                </div>
            </div>

            {{-- Year + Edition --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                <div>
                    <label class="form-label">Year</label>
                    <input type="number" name="year_published" placeholder="{{ date('Y') }}" class="form-input" min="1900" max="{{ date('Y') }}">
                </div>
                <div>
                    <label class="form-label">Edition</label>
                    <input type="text" name="edition" placeholder="3rd Edition" class="form-input">
                </div>
            </div>

            {{-- College + Program --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                <div>
                    <label class="form-label">College <span style="color:var(--red-main);">*</span></label>
                    <select name="college_id" id="addCollegeSelect" class="form-input" required onchange="loadPrograms(this.value, 'addProgramSelect')">
                        <option value="">Select College</option>
                        @foreach($colleges as $college)
                            <option value="{{ $college->id }}">{{ $college->code }} — {{ $college->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Program <span style="color:var(--red-main);">*</span></label>
                    <input type="text" name="program" id="addProgramSelect" placeholder="Select college first" class="form-input" required>
                </div>
            </div>

            {{-- Category + Stock --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                <div>
                    <label class="form-label">Category <span style="color:var(--red-main);">*</span></label>
                    <input type="text" name="category" placeholder="Technology, Medical, Law..." class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Stock / Copies <span style="color:var(--red-main);">*</span></label>
                    <input type="number" name="stock" placeholder="0" class="form-input" min="0" required>
                </div>
            </div>

            {{-- Shelf Location --}}
            <div style="margin-bottom:12px;">
                <label class="form-label">Shelf Location</label>
                <input type="text" name="shelf_location" placeholder="e.g. CCE-A3" class="form-input">
            </div>

            {{-- Description --}}
            <div style="margin-bottom:20px;">
                <label class="form-label">Description</label>
                <textarea name="description" placeholder="Brief description..." class="form-input" rows="3" style="resize:vertical;"></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;padding:12px;">Add Book</button>
        </form>
    </div>
</div>

{{-- EDIT BOOK MODAL --}}
<div class="modal-overlay" id="modalEditBook">
    <div class="modal" style="max-width:680px;">
        <button class="modal-close" onclick="closeModal('modalEditBook')">✕</button>
        <div class="modal-title">Edit Book</div>

        <form method="POST" id="editBookForm" enctype="multipart/form-data">
            @csrf @method('PUT')

            {{-- Book ID (readonly) --}}
            <div style="margin-bottom:12px;">
                <label class="form-label">Book ID</label>
                <input type="text" id="editBookId" disabled class="form-input" style="background:var(--cream);color:var(--text-muted);">
            </div>

            {{-- ISBN --}}
            <div style="margin-bottom:12px;">
                <label class="form-label">ISBN</label>
                <input type="text" name="isbn" id="editIsbn" placeholder="978-..." class="form-input">
            </div>

            {{-- Title --}}
            <div style="margin-bottom:12px;">
                <label class="form-label">Title <span style="color:var(--red-main);">*</span></label>
                <input type="text" name="title" id="editTitle" class="form-input" required>
            </div>

            {{-- Author + Publisher --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                <div>
                    <label class="form-label">Author <span style="color:var(--red-main);">*</span></label>
                    <input type="text" name="author" id="editAuthor" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Publisher</label>
                    <input type="text" name="publisher" id="editPublisher" class="form-input">
                </div>
            </div>

            {{-- Year + Edition --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                <div>
                    <label class="form-label">Year</label>
                    <input type="number" name="year_published" id="editYear" class="form-input">
                </div>
                <div>
                    <label class="form-label">Edition</label>
                    <input type="text" name="edition" id="editEdition" class="form-input">
                </div>
            </div>

            {{-- College + Program --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                <div>
                    <label class="form-label">College <span style="color:var(--red-main);">*</span></label>
                    <select name="college_id" id="editCollegeSelect" class="form-input" required onchange="loadPrograms(this.value, 'editProgramInput')">
                        <option value="">Select College</option>
                        @foreach($colleges as $college)
                            <option value="{{ $college->id }}">{{ $college->code }} — {{ $college->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Program <span style="color:var(--red-main);">*</span></label>
                    <input type="text" name="program" id="editProgramInput" class="form-input" required>
                </div>
            </div>

            {{-- Category + Stock --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                <div>
                    <label class="form-label">Category <span style="color:var(--red-main);">*</span></label>
                    <input type="text" name="category" id="editCategory" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Stock / Copies <span style="color:var(--red-main);">*</span></label>
                    <input type="number" name="stock" id="editStock" class="form-input" min="0" required>
                </div>
            </div>

            {{-- Shelf --}}
            <div style="margin-bottom:12px;">
                <label class="form-label">Shelf Location</label>
                <input type="text" name="shelf_location" id="editShelf" class="form-input">
            </div>

            {{-- Description --}}
            <div style="margin-bottom:12px;">
                <label class="form-label">Description</label>
                <textarea name="description" id="editDescription" class="form-input" rows="3" style="resize:vertical;"></textarea>
            </div>

            {{-- Cover Image --}}
            <div style="margin-bottom:20px;">
                <label class="form-label">Replace Cover Image (optional)</label>
                <input type="file" name="cover_image" accept="image/*" class="form-input" style="padding:6px;">
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;padding:12px;">Save Changes</button>
        </form>
    </div>
</div>

@endsection

@section('styles')
<style>
.form-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);display:block;margin-bottom:5px;}
.form-input{width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px;font-size:13px;font-family:'Lato',sans-serif;color:var(--text-dark);background:var(--white);outline:none;transition:border-color .2s;}
.form-input:focus{border-color:var(--red-main);}
</style>
@endsection

@section('scripts')
<script>
const collegePrograms = {
    1: ['BSIT', 'BSCS'],
    2: ['BSN'],
    3: ['BSCrim'],
    4: ['BSCE', 'BSEE', 'BSME'],
    5: ['BSBA', 'BSAccountancy'],
    6: ['BEEd', 'BSEd'],
    7: ['BSPS', 'ABCOM'],
};

function loadPrograms(collegeId, targetId) {
    const programs = collegePrograms[collegeId] ?? [];
    const target = document.getElementById(targetId);
    target.value = '';
    target.placeholder = programs.length ? programs.join(', ') + '...' : 'Select college first';
}

function openEdit(bookId) {
    const d = document.getElementById('editData-' + bookId).dataset;
    document.getElementById('editBookForm').action = '/librarian/book-catalog/' + bookId;
    document.getElementById('editBookId').value        = d.bookid;
    document.getElementById('editTitle').value         = d.title;
    document.getElementById('editAuthor').value        = d.author;
    document.getElementById('editIsbn').value          = d.isbn;
    document.getElementById('editPublisher').value     = d.publisher;
    document.getElementById('editYear').value          = d.year;
    document.getElementById('editEdition').value       = d.edition;
    document.getElementById('editCollegeSelect').value = d.college;
    document.getElementById('editProgramInput').value  = d.program;
    document.getElementById('editCategory').value      = d.category;
    document.getElementById('editStock').value         = d.stock;
    document.getElementById('editShelf').value         = d.shelf;
    document.getElementById('editDescription').value   = d.description;
    openModal('modalEditBook');
}
</script>
@endsection