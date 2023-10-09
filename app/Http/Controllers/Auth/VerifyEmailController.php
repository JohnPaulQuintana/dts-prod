<?php

namespace App\Http\Controllers\Auth;

use App\Events\NotifyEvent;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new NotifyEvent('refresh the page'));
            // Update the user's status to "active"
            $request->user()->update(['status' => 'active']);
            
            event(new Verified($request->user()));
        }


        return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    }
}
