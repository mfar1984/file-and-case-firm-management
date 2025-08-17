<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(Request $request, $id, $hash): RedirectResponse
    {
        // Find user by ID
        $user = User::findOrFail($id);
        
        // Check if user has already verified email
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('success', 'Email already verified. You can now login.');
        }
        
        // Verify the hash
        if (!hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            return redirect()->route('login')->with('error', 'Invalid verification link.');
        }
        
        // Mark email as verified
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }
        
        return redirect()->route('login')->with('success', 'Email verified successfully! You can now login.');
    }
}
