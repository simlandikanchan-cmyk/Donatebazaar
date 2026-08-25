<?php

use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/messages', [ContactMessageController::class, 'index'])->name('messages');
    Route::get('/messages/{id}', [ContactMessageController::class, 'show'])->name('messages.show');
    Route::delete('/messages/{id}', [ContactMessageController::class, 'destroy'])->name('messages.delete');
    Route::post('/messages/{id}/toggle-read', [ContactMessageController::class, 'toggleRead'])->name('messages.toggle-read');
    Route::post('/messages/{id}/reply', [ContactMessageController::class, 'reply'])->name('messages.reply');
    Route::post('/messages/bulk', [ContactMessageController::class, 'bulk'])->name('messages.bulk');

    Route::get('/contacts', [ContactController::class, 'adminIndex'])->name('contacts');
    Route::delete('/contacts/{id}', [ContactController::class, 'destroy'])->name('contacts.destroy');

});
