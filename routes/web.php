<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceArchiveController;
use App\Http\Controllers\InvoiceAttachmentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceDetailController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SectionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

// Auth::route();


Route::get('/index', function () {
    return view('index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
});

Route::resource('invoices', InvoiceController::class);
Route::resource('sections', SectionController::class);
Route::resource('products', ProductController::class);
Route::resource('Archive', InvoiceArchiveController::class);

Route::post('InvoiceAttachments', [InvoiceAttachmentController::class, 'store']);

Route::get('section/{id}', [InvoiceController::class, 'get_products']);

Route::get('Invoices_Details/{id}', [InvoiceDetailController::class, 'edit']);

Route::get('view_file/{invoice_number}/{file_name}', [InvoiceDetailController::class, 'view_file']);

Route::get('download/{invoice_number}/{file_name}', [InvoiceDetailController::class, 'download']);

Route::delete('delete_file', [InvoiceDetailController::class, 'destroy']);

Route::get('Status_show/{id}', [InvoiceController::class, 'show'])->name('Status_show');

Route::post('Status_update/{id}', [InvoiceController::class, 'Status_update'])->name('Status_update');

Route::get('invoice_paid', [InvoiceController::class, 'invoice_paid'])->name('invoice_paid');

Route::get('invoice_unpaid', [InvoiceController::class, 'invoice_unpaid'])->name('invoice_unpaid');

Route::get('invoice_partial', [InvoiceController::class, 'invoice_partial'])->name('invoice_partial');

Route::get('edit_invoice/{id}', [InvoiceController::class, 'edit'])->name('edit_invoice');

Route::get('Archive_invoice', [InvoiceArchiveController::class, 'index'])->name('Archive_invoice');

Route::get('print_invoice/{id}', [InvoiceController::class, 'print_invoice'])->name('print_invoice');







Route::get('modals', function () {
    return view('modals');
});

Route::get('table_data', function () {
    return view('table-data');
});

Route::get('icons', function () {
    return view('icons');
});

Route::get('images', function () {
    return view('image-compare');
});

Route::get('buttons', function () {
    return view('buttons');
});

Route::get('tabs', function () {
    return view('tabs');
});



require __DIR__ . '/auth.php';
