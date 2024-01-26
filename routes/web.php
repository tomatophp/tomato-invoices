<?php


use Illuminate\Support\Facades\Route;
use TomatoPHP\TomatoInvoices\Http\Controllers\InvoiceController;

Route::middleware(['web','auth', 'splade', 'verified'])->name('admin.')->group(function () {
    Route::get('admin/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('admin/invoices/products', [InvoiceController::class, 'products'])->name('invoices.products');
    Route::get('admin/invoices/branches', [InvoiceController::class, 'branches'])->name('invoices.branches');
    Route::post('admin/invoices/company', [InvoiceController::class, 'company'])->name('invoices.company');
    Route::post('admin/invoices/accounts', [InvoiceController::class, 'accounts'])->name('invoices.accounts');
    Route::get('admin/invoices/api', [InvoiceController::class, 'api'])->name('invoices.api');
    Route::get('admin/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('admin/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('admin/invoices/{model}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('admin/invoices/{model}/print', [InvoiceController::class, 'printIt'])->name('invoices.print');
    Route::get('admin/invoices/{model}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::post('admin/invoices/{model}', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('admin/invoices/{model}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
});
