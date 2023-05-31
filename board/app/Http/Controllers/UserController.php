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
use Illuminate\Support\Facades\Session;
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
            $error = '아이디와 비밀번호를 확인해 주세요';
            return redirect()->back()->with('error', $error);
        }

        
        Auth::login($user);
        if(Auth::check()){
            session($user->only('id'));
            return redirect()->intended(route('boards.index'));
        } else {
            $error = '인증작업 에러';
            return redirect()->back()->with('error', $error);
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
            $error = '시스템 에러가 발생하여, 회원가입에 실패했습니다.<br>잠시 후에 다시 회원가입을 시도해 주십시오.';

            return redirect()
                ->route('users.registration')
                ->with('error', $error);
        }
        // 회원 가입 완료, 로그인 페이지로 이동
        return redirect()
            ->route('users.login')
            ->with('success', '회원가입을 완료했습니다.<br>가입하신 아이디와 비밀번호로 로그인해 주십시오.');
        }

        function logout(){
            Session::flush();   //세션 파기
            Auth::logout();     //로그 아웃
            return redirect()->route('users.login');
        }

        function withdraw(){
            $id=session('id');
            $result = User::destroy($id);
            Session::flush();   //세션 파기
            Auth::logout();     //로그 아웃
            return redirect()->route('users.login');
        }
    
        // public function userupdate(){
        // $id = Auth::id();
        // $user = User::find($id);
        // return view('useredit')->with('user', $user);
        // }

        // function userupdatepost(Request $req){
        
        //     //유효성 체크
        //     $req->validate([
        //         'name' => 'required|regex:/^[가-힣]+$/|min:2|max:30',
        //         'email' => 'required|email|max:100',
        //         'password' => 'required|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        //     ]);
        
    
        //     $data['name'] = $req->name;
        //     $data['email'] = $req->email;
        //     $data['password'] = Hash::make($req->password);
    
        //     $user = User::create($data);
        //     if (!$user) {
        //         $error = '시스템 에러가 발생하여, 회원가입에 실패했습니다.<br>잠시 후에 다시 회원가입을 시도해 주십시오.';
    
        //         return redirect()
        //             ->route('users.userupdate')
        //             ->with('error', $error);
        //     }
        //     // 회원 가입 완료, 로그인 페이지로 이동
        //     return redirect()
        //         ->route('users.login')
        //         ->with('success', '회원가입을 완료했습니다.<br>가입하신 아이디와 비밀번호로 로그인해 주십시오.');
        
        // }

        function edit() {
            $user = User::find(Auth::User()->id);
            
            return view('useredit')->with('data', $user);
        }

        function editpost(Requst $req){
            $arrkey=[];

            $baseuser = User::find(Auth::User()->id); //기존 데이터 획득
            
            //기존 패스워드 체크
            if (Hash::check($req->password, $baseuser->password))
                return redirect()->back()->with('error','기존 비밀번호를 확인해 주세요');


            //수정할 항목을 배열에 담는 처리
            if($req->name !== $baseuser->name){
                $arrkey[]= 'name';
            }
            if($req->email !== $baseuser->email){
                $arrkey[]= 'email';
            }
            if(isset($req->password)){
                $arrkey[]= 'password';
            }

            //유효성체크를 하는 모든 항목 리스트
            $chkList=[
                'name'      => 'required|regex:/^[가-힣]+$/|min:2|max:30'
                ,'email'    => 'required|email|max:100'
                ,'bpassword'=> 'regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
                ,'password' => 'required|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
            ];

            //유효성 체크할 항목 셋팅하는 처리
            $arrchk['bpassword'] = $chkList['bpassword'];
            foreach($arrKey as $val){
                $arrchk[$val]= $chkList[$val];
            }

            //유효성 체크
            $req->validate($arrchk);

            foreach($arrKey as $val){
                if($val==='password'){
                    $val=Hash::make($req->$val);
                    continue;
                }
                $baseuser->$val= $req->$val;
            }
            $baseUser ->save();

            return redirect()->route('users.edit');
        }

    }
    