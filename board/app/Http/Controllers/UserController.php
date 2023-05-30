<?php
//------------------------------
//프로젝트명 :laravel_board
//디렉토리   :Controllers
//파일명     :UserController.php
//이력       :v001 0530 jyp new
//------------------------------

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    function login(){
        return view('login');
    }
    function loginpost(Request $req){
        $req->validate([
            'email' => 'required|email|max:100'
            ,'password' => 'required|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        ]);

        //유저정보 습득
        $user = User::where('email', $req->email)->first();
        if (!$user || !Hash::check($req->password, $user->password)) {
            $errors[] = '아이디와 비밀번호를 확인해 주세요';
            return redirect()->back()->with('errors', collect($errors));
        }

        
        // Auth::login($user);
        if(Auth::check()){
            session([$user->only('id')]);
            return redirect()->intended(route('boards.index'));
        } else {
            $errors[] = '인증작업 에러';
            return redirect()->back()->with('errors', collect($errors));
        }

    }

    function registration(){
        return view('registration');
    }

    function registrationpost(Request $req){
        
        //유효성 체크
        $req->validate([
            'name' => 'required|regex:/^[가-힣]+$/|min:2|max:30',
            'email' => 'required|email|max:100',
            'password' => 'required|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        ]);
    

        $data['name'] = $req->name;
        $data['email'] = $req->email;
        $data['password'] = Hash::make($req->password);

        $user = User::create($data);
        if (!$user) {
            $errors[] = '시스템 에러가 발생하여, 회원가입에 실패했습니다.';
            $errors[] = '잠시 후에 다시 회원가입을 시도해 주십시오.';

            return redirect()
                ->route('users.registration')
                ->with('errors', collect($errors));
        }
        // 회원 가입 완료, 로그인 페이지로 이동
        return redirect()
            ->route('users.login')
            ->with('success', '회원가입을 완료했습니다. 가입하신 아이디와 비밀번호로 로그인해 주십시오.');
    }
}