<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [\App\Authentication\Actions\RegisteredUser::class, 'create'])
            ->name('register');
    Route::post('register', [\App\Authentication\Actions\RegisteredUser::class, 'store']);
    Route::get('login', [\App\Authentication\Actions\AuthenticatedSession::class, 'create'])
            ->name('login');
    Route::post('login', [\App\Authentication\Actions\AuthenticatedSession::class, 'store']);

    Route::get('forgot-password', [\App\Authentication\Actions\PasswordResetLink::class, 'create'])
            ->name('password.request');
    Route::post('forgot-password', [\App\Authentication\Actions\PasswordResetLink::class, 'store'])
            ->name('password.email');
    Route::get('reset-password/{token}', [\App\Authentication\Actions\NewPassword::class, 'create'])
            ->name('password.reset');
    Route::post('reset-password', [\App\Authentication\Actions\NewPassword::class, 'store'])
            ->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', [\App\Authentication\Actions\EmailVerificationPrompt::class, '__invoke'])
            ->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', [\App\Authentication\Actions\VerifyEmail::class, '__invoke'])
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');
    Route::post('email/verification-notification', [\App\Authentication\Actions\EmailVerificationNotification::class, 'store'])
            ->middleware('throttle:6,1')->name('verification.send');
    Route::get('confirm-password', [\App\Authentication\Actions\ConfirmablePassword::class, 'show'])
            ->name('password.confirm');
    Route::post('confirm-password', [\App\Authentication\Actions\ConfirmablePassword::class, 'store']);
    Route::post('logout', [\App\Authentication\Actions\AuthenticatedSession::class, 'destroy'])
        ->name('logout');
});
