<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Events\UserRegisterEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\RegisterRequest;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->only('name', 'email', 'password');

        $user = User::create($data);

        // event(new UserRegisterEvent($user));

        // $remember = $request->has('remember');
        // Auth::login($user, $remember);

        alert()->info('Bilgi', 'Mailinize gelen onay mailini onaylayin.');

        return redirect()->back();
    }

    public function verify(Request $request)
    {
        $userID = Cache::get('verify_token_' . $request->token);

        if (!$userID) {
            alert()->warning('Uyari', 'Tokenin gecerlilik suresi dolmis.');
            return redirect()->route('register');
        }

        $user = User::findOrFail($userID);
        $user->email_verified_at = now();
        $user->save();

        Cache::forget('verify_token_' . $request->token);

        Auth::login($user);
        alert()->success('Basarili', 'Hesabiniz onaylandi.');

        return redirect()->route('admin.index');
    }
}