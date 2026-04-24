<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Root redirect
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->to(match(auth()->user()->role) {
            'admin'     => '/admin/dashboard',
            'librarian' => '/librarian/dashboard',
            'student'   => '/student/dashboard',
            default     => '/login',
        });
    }
    return redirect()->route('login');
});

// Temp logout for testing
Route::get('/logout-now', function() {
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/login');
});

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login',    [LoginController::class,    'showLoginForm'])->name('login');
    Route::post('/login',   [LoginController::class,    'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register',[RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Admin routes
Route::middleware(['auth', 'check.status', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/account-requests', [App\Http\Controllers\Admin\AccountRequestController::class, 'index'])->name('account-requests');
    Route::post('/account-requests/{user}/approve', [App\Http\Controllers\Admin\AccountRequestController::class, 'approve'])->name('account-requests.approve');
    Route::post('/account-requests/{user}/reject', [App\Http\Controllers\Admin\AccountRequestController::class, 'reject'])->name('account-requests.reject');
    Route::get('/database', [App\Http\Controllers\Admin\DatabaseController::class, 'index'])->name('database');
    Route::get('/analytics', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/database', [App\Http\Controllers\Admin\DatabaseController::class, 'index'])->name('database');
    Route::get('/database/export-csv', [App\Http\Controllers\Admin\DatabaseController::class, 'exportCsv'])->name('database.export-csv');
    Route::get('/database/audit-log', [App\Http\Controllers\Admin\DatabaseController::class, 'auditLog'])->name('database.audit-log');
    Route::get('/announcements', [App\Http\Controllers\Admin\AnnouncementController::class, 'index'])->name('announcements');
    Route::post('/announcements', [App\Http\Controllers\Admin\AnnouncementController::class, 'store'])->name('announcements.store');
    Route::put('/announcements/{announcement}', [App\Http\Controllers\Admin\AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{announcement}', [App\Http\Controllers\Admin\AnnouncementController::class, 'destroy'])->name('announcements.destroy');
});

// Librarian routes
Route::middleware(['auth', 'check.status', 'role:librarian'])->prefix('librarian')->name('librarian.')->group(function () {
    Route::get('/dashboard',     [App\Http\Controllers\Librarian\DashboardController::class,    'index'])->name('dashboard');
    Route::get('/book-catalog',  [App\Http\Controllers\Librarian\BookCatalogController::class,  'index'])->name('book-catalog');
    Route::post('/book-catalog', [App\Http\Controllers\Librarian\BookCatalogController::class,  'store'])->name('book-catalog.store');
    Route::put('/book-catalog/{book}',    [App\Http\Controllers\Librarian\BookCatalogController::class, 'update'])->name('book-catalog.update');
    Route::delete('/book-catalog/{book}', [App\Http\Controllers\Librarian\BookCatalogController::class, 'destroy'])->name('book-catalog.destroy');
    Route::get('/book-reports',  [App\Http\Controllers\Librarian\BookReportController::class,   'index'])->name('book-reports');
    Route::get('/book-reports/export', [App\Http\Controllers\Librarian\BookReportController::class, 'export'])->name('book-reports.export');
    Route::get('/book-requests', [App\Http\Controllers\Librarian\BookRequestController::class,  'index'])->name('book-requests');
    Route::post('/book-requests/{borrowing}/approve', [App\Http\Controllers\Librarian\BookRequestController::class, 'approve'])->name('book-requests.approve');
    Route::post('/book-requests/{borrowing}/decline', [App\Http\Controllers\Librarian\BookRequestController::class, 'decline'])->name('book-requests.decline');
    Route::post('/book-requests/{borrowing}/return',  [App\Http\Controllers\Librarian\BookRequestController::class, 'confirmReturn'])->name('book-requests.return');
    Route::get('/collab-zones',  [App\Http\Controllers\Librarian\CollabZoneController::class,   'index'])->name('collab-zones');
    Route::post('/collab-zones/{collabRoomRequest}/approve', [App\Http\Controllers\Librarian\CollabZoneController::class, 'approve'])->name('collab-zones.approve');
    Route::post('/collab-zones/{collabRoomRequest}/decline', [App\Http\Controllers\Librarian\CollabZoneController::class, 'decline'])->name('collab-zones.decline');
    Route::post('/collab-zones/{collabRoom}/available', [App\Http\Controllers\Librarian\CollabZoneController::class, 'markRoomAvailable'])->name('collab-zones.available');
    Route::post('/collab-zones/attendance/{restZoneAttendance}/confirm', [App\Http\Controllers\Librarian\CollabZoneController::class, 'confirmEntry'])->name('collab-zones.confirm-entry');
    Route::post('/collab-zones/attendance/{restZoneAttendance}/decline', [App\Http\Controllers\Librarian\CollabZoneController::class, 'declineEntry'])->name('collab-zones.decline-entry');
    Route::post('/collab-zones/attendance/{restZoneAttendance}/checkout', [App\Http\Controllers\Librarian\CollabZoneController::class, 'checkOut'])->name('collab-zones.checkout');
    Route::get('/profile', [App\Http\Controllers\Librarian\ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [App\Http\Controllers\Librarian\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [App\Http\Controllers\Librarian\ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/book-requests/{penalty}/waive', [App\Http\Controllers\Librarian\BookRequestController::class, 'waivePenalty'])->name('book-requests.waive');
    Route::get('/announcements', [App\Http\Controllers\Librarian\AnnouncementController::class, 'index'])->name('announcements');
    Route::post('/announcements', [App\Http\Controllers\Librarian\AnnouncementController::class, 'store'])->name('announcements.store');
    Route::put('/announcements/{announcement}', [App\Http\Controllers\Librarian\AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{announcement}', [App\Http\Controllers\Librarian\AnnouncementController::class, 'destroy'])->name('announcements.destroy');
});

// Student routes
Route::middleware(['auth', 'check.status', 'role:student'])->prefix('student')->name('student.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
    // Profile
    Route::get('/profile', [App\Http\Controllers\Student\ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [App\Http\Controllers\Student\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [App\Http\Controllers\Student\ProfileController::class, 'updatePassword'])->name('profile.password');
    // Browse Books
    Route::get('/browse-books', [App\Http\Controllers\Student\BrowseBooksController::class, 'index'])->name('browse-books');
    Route::post('/browse-books/{book}/request', [App\Http\Controllers\Student\BrowseBooksController::class, 'requestBook'])->name('browse-books.request');
    Route::delete('/browse-books/{book}/cancel', [App\Http\Controllers\Student\BrowseBooksController::class, 'cancelRequest'])->name('browse-books.cancel');
    // Borrowed Books
    Route::get('/borrowed-books', [App\Http\Controllers\Student\BorrowedBooksController::class, 'index'])->name('borrowed-books');
    // Records & History
    Route::get('/records', [App\Http\Controllers\Student\RecordsController::class, 'index'])->name('records');
    // Spaces & Zones
    Route::get('/spaces', [App\Http\Controllers\Student\SpacesController::class, 'index'])->name('spaces');
    Route::post('/spaces/collab/{collabRoom}/request', [App\Http\Controllers\Student\SpacesController::class, 'requestRoom'])->name('spaces.request-room');
    Route::post('/spaces/rest/{restZone}/attend', [App\Http\Controllers\Student\SpacesController::class, 'attendRestZone'])->name('spaces.attend-rest-zone');
    // Borrowed Books
    Route::get('/borrowed-books', [App\Http\Controllers\Student\BorrowedBooksController::class, 'index'])->name('borrowed-books');
    Route::delete('/borrowed-books/{borrowing}/cancel', [App\Http\Controllers\Student\BorrowedBooksController::class, 'cancel'])->name('borrowed-books.cancel');
    //restsones
    Route::get('/spaces', [App\Http\Controllers\Student\SpacesController::class, 'index'])->name('spaces');
    Route::post('/spaces/collab/{collabRoom}/request', [App\Http\Controllers\Student\SpacesController::class, 'requestRoom'])->name('spaces.request-room');
    Route::post('/spaces/rest/{restZone}/attend', [App\Http\Controllers\Student\SpacesController::class, 'attendRestZone'])->name('spaces.attend-rest-zone');
    Route::get('/announcements', [App\Http\Controllers\Student\AnnouncementController::class, 'index'])->name('announcements');
    });

    // Notifications (shared across all roles)
Route::middleware(['auth', 'check.status'])->group(function () {
    Route::get('/notifications',          [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
});