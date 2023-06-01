<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boards;
use Illuminate\Support\Facades\Validator;

class apiListController extends Controller
{
    function getlist($id){
        $boards= Boards::find($id);
        return response()->json([$boards],200);
    }

    function postlist(Request $req){

        //유효성 체크 필요
        $boards= new Boards([
            'title'     => $req->title
            ,'content'  => $req->content
        ]);
        $boards->save();
        $arr['errorcode'] = '0';
        $arr['msg']= 'success';
        $arr['data']=$boards->only('id','title');

        return $arr;

    }

    function putlist(Request $req, $id)
    {
        $arrData=[
            'code'          => '0'
            ,'msg'          => ''
            // ,'org_data'     => []
            // ,'udt_data'     => []
        ];

        $data = $req->only('title','content');
        $data['id']=$id;

        $validator = Validator::make($data,[
            'id'        => 'required|integer|exists:boards'
            ,'title'    => 'required|between:3,30'
            ,'content'  => 'required|max:2000'
        ]);

        if($validator->fails()){
            $arrData['code'] ='E01';
            $arrData['msg'] = 'Validate Error';
            $arrData['errmsg'] = $validator->errors()->all();
            return $arrData;
        } else {
            //업데이트처리
            $boards = Boards::find($id);
            $boards->title =$req->title;
            $boards->content =$req->content;
            $boards->save();

            $arrData['code'] ='0';
            $arrData['msg'] = 'Success';

        }
        return $arrData;
        

        // 유효성체크
        // $req->validate([
        //     'title' => 'required',
        //     'content' => 'required'
        // ]);
        // $boards = Boards::find($id);

        // if ($boards) {
        //     $boards->title = $req->title;
        //     $boards->content = $req->content;
        //     $boards->save();

        //     $arr['errorcode'] = '0';
        //     $arr['msg'] = 'success';
        //     $arr['data'] = $boards->only('id', 'title');

        //     return $arr;
        // }

        // return response()->json([$boards],200);
    }

    public function deletelist($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:boards'
        ]);
    
        if ($validator->fails()) {
            $arrData['code'] = 'E01';
            $arrData['msg'] = 'Validation Error';
            $arrData['errmsg'] = $validator->errors()->all();
            return $arrData;
        }
    
        $boards = Boards::destroy($id);
    
        if ($boards) {
            $arr['errorcode'] = '0';
            $arr['msg'] = 'success';
            $arr['id'] = $id;
            return $arr;
        }
    
        return response()->json(['error' => 'Board not found'], 404);
    }
    
}
