<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

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
        } else {
            return redirect()->back()->withErrors([
                'email' => 'Lutfen bilgilerinizi kontrol ediniz!'
            ]);
        }

        if ($user->hasRole(['super-admin', 'category-manager', 'product-manager', 'order-manager', 'user-manager'])) {

            return redirect()->route('admin.index');
        }

        return redirect()->route('order.index');

        /**
         * intended('/url') -> Kullanicinin gitmek istedigi bi sayfa varsa oraya yonlendir yoksa default yere yonlendir.
         * Ornek siparislerim sayfasina gidecgim ama giris yapmamisim.
         * Once giris ekranina yonlendirecek girsi yaptiktan sonra direkt siparislerime gidecek
         * */
        // return redirect()->intended('/admin');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('index');
    }

    public function socialite($driver)
    {
        // driver (google) giris sayfasina yonlendirdik. Giris adimlari tamamlandiktan sonra callbackli route gidiyoruz o route da asagidaki fonksiyonu cagiriyor
        return Socialite::driver($driver)->redirect();
    }

    public function socialiteVerify($driver)
    {
        try {
            // google girisi yaptik yukardan buraya geldik once giris yapan user i aliyoruz ve bu user i db mizde buluyoruz.Eger mevcutsa direkt login yapiyoruz, mevcut degilse de create edip login yapiyoruz.
            $user = Socialite::driver($driver)->user();
        } catch (\Throwable $th) {
            // github ile erisime izin verilmediginde bad rq hatasi veriyordu o yuzden try catch blogu icerisine aldim bu kismi
            return redirect()->route('index');
        }

        $checkUser = User::query()->where('email', $user->getEmail())->first();

        if ($checkUser) {
            Auth::login($checkUser);
            return redirect()->route('index');
        }

        if ($driver === 'google') {
            $name = $user->getName();
        } elseif ($driver === 'github') {
            $name = $user->getNickname();
        }

        $createUser = User::create([
            'name' => $name,
            'email' => $user->getEmail(),
            'password' => bcrypt(123456),
            'email_verified_at' => now()
        ]);

        Auth::login($createUser);
        return redirect()->route('index');
    }
}