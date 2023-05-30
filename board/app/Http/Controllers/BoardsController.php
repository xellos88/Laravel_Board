<?php
// -------------------------------------
// 프로젝트명 :laravel_board
// 디렉토리    :Controllers
// 파일명      boardscontroller.php
// 이력        v001 0526 ~~~
//             v002 0530 ~~~
// ---------------------------------------
// v002 del
// v002 update start
// v002 update end
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Boards;

class BoardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = Boards::select(['id','title','hits','created_at','updated_at'])->orderBy('hits', 'desc')->get();
        return view('list')->with('data', $result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('write');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $req
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        // v002 add start 
        $req -> validate([
            'title'     => 'required|between:3,30'
            ,'content'  => 'required|max:1000'
        ]);
        // v002 add end

        $boards = new Boards([
            'title'     => $req->input('title')
            ,'content'  => $req->input('content')
        ]);
        $boards->save();
        return redirect('/boards');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $boards= Boards::find($id);
        $boards->hits++;
        $boards->save();

        return view('detail')->with('data', Boards::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $board = Boards::find($id);
        return view('edit')->with('data', $board);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //-------------v002 add start
        //ID를 리퀘스트 객체에 머지
        $arr=['id'=>$id];
        $request->merge($arr);
        $request->request->add($arr);
        //-------------v002 add end

        $request->validate([
            'id'        => 'required|interger'
            ,'title'    => 'required|between:3,30'
            ,'content'  => 'required|max:1000'
        ]);

        // 유효성 검사 방법2
        // $validator = Validator::make(
        //     $request->only('id', 'title', 'content'),
        //     [
        //         'id' => 'required|integer',
        //         'title' => 'required|between:3,30',
        //         'content' => 'required|max:1000',
        //     ]
        // );

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

        // $board = Boards::find($id);
        // $board->title = $request->title;
        // $board->content = $request->content;
        // $board->save();

        // return redirect('/boards/'.$id)->with('data', Boards::findOrFail($id));

        $board = Boards::find($id)->update($request->only(['title', 'content']));

        return redirect()->route('boards.show', ['board' => $id]);

    }
    

    // $boards = DB:table('boards')
    // ->where('id', $id)
    // ->update(['title'=> $request->title, 'content' => $requst->content]);
    // return view('detail')->with('data', Boards::findOrFail($id))

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Boards::destroy($id);

        $board = Boards::find($id)->delete();
        //$board->delete();
        return redirect('/boards');
    }
    
}

