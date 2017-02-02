<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Socialite;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return \Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'phone' => 'required|mobile_phone|min:11|max:13',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        \Redis::incr('user:count');
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'phone' => $data['phone'],
        ]);
    }

    /**
     * 사용자를 깃허브 인증 페이지로 전환
     *
     * @return Response
     */
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * 깃허브에서 인증이 완료된 사용자 정보를 받아서 처리
     *
     * @return Response
     */
    public function handleGithubCallback(Request $request)
    {
        try {
            $authUser = Socialite::driver('github')->user();
        } catch (Exception $e) {
            return redirect('auth/github');
        }

        $authUser = $this->findOrCreateUser($authUser);

        \Auth::login($authUser);
        $request->session()->put('github_id', $authUser->id);
//        session(['github_id' => $authUser->id]);
//        Session::put('github_id', $authUser->id);

        return redirect()->intended($this->redirectPath());
    }
    /**
     * 깃허브 인증에 성공한 후 받은 사용자 정보가 데이터베이스에 없을 경우 생성하고, 이미 있을 경우 가져옴
     *
     * @param $githubUser 깃허브에서 전달받은 사용자 정보
     * @return User
     */
    private function findOrCreateUser($githubUser)
    {
        if ($user = User::where('github_id', $githubUser->id)->first()) {
            return $user;
        }

        return User::create([
            'name' => $githubUser->name,
            'email' => $githubUser->email,
            'github_id' => $githubUser->id,
        ]);
    }
}
