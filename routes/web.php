<?php

use App\Http\Controllers\FormBuilderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FormBuilderController::class, 'index'])->name('form-builder');
