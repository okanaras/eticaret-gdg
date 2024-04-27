<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        // $credentials['status'] = 1; // $credentials = kosullarimizi yazariz. Burada status'u true ise kosulunu da istersek ayrica ekleyebiliriz.
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            // attemp giris icin gerekli controlleri kendisi yapacak.

            $user = Auth::user();

            if (!$user->hasVerifiedEmail()) {
                // if ($user->email_verified_at !== null) {
                // if (is_null($user->email_verified_at)) {
                Auth::logout();
                alert()->warning('Uyari', 'Giris yapabilmeniz icin mail adresinizi dogrulayiniz.');
                return redirect()->back();
            }
        }

        /**
         * intentended() -> Kullanicinin gitmek istedigi bi sayfa varsa oraya yonlendir yoksa default yere yonlendir.
         * Ornek siparislerim sayfasina gidecgim ama giris yapmamisim.
         * Once giris ekranina yonlendirecek girsi yaptiktan sonra direkt siparislerime gidecek
         * */
        return redirect()->intended('/admin');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('index');
    }
}