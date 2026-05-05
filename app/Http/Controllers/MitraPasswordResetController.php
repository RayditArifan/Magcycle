<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MitraPasswordResetController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.mitra-forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $mitra = DB::table('data_mitra')
            ->where('email', $request->email)
            ->first();

        if (!$mitra) {
            return back()
                ->withInput()
                ->with('popup', 'email_not_found');
        }

        DB::table('mitra_password_resets')
            ->where('email', $request->email)
            ->whereNull('used_at')
            ->update([
                'used_at' => now(),
            ]);

        $token = Str::random(64);

        DB::table('mitra_password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'expires_at' => now()->addMinutes(15),
            'used_at' => null,
        ]);

        $resetLink = route('mitra.password.reset', $token);

        Mail::raw(
            "Halo {$mitra->username},\n\nKlik link berikut untuk reset password akun Mitra MagCycle:\n\n{$resetLink}\n\nLink ini berlaku selama 15 menit dan hanya bisa digunakan sekali.",
            function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Reset Password Mitra MagCycle');
            }
        );

        return back()->with('popup', 'link_sent');
    }

    public function showResetForm($token)
    {
        $resetData = DB::table('mitra_password_resets')
            ->where('token', $token)
            ->whereNull('used_at')
            ->where('expires_at', '>=', now())
            ->first();

        if (!$resetData) {
            return redirect()
                ->route('mitra.password.forgot')
                ->with('popup', 'token_invalid');
        }

        return view('auth.mitra-reset-password', [
            'token' => $token,
        ]);
    }

    public function resetPassword(Request $request, $token)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $resetData = DB::table('mitra_password_resets')
            ->where('token', $token)
            ->whereNull('used_at')
            ->where('expires_at', '>=', now())
            ->first();

        if (!$resetData) {
            return redirect()
                ->route('mitra.password.forgot')
                ->with('popup', 'token_invalid');
        }

        DB::table('data_mitra')
            ->where('email', $resetData->email)
            ->update([
                'password' => $request->password,
            ]);

        DB::table('mitra_password_resets')
            ->where('id', $resetData->id)
            ->update([
                'used_at' => now(),
            ]);

        return redirect()
            ->route('login')
            ->with('popup', 'password_updated');
    }
}
