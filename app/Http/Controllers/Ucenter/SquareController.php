<?php

namespace App\Http\Controllers\Ucenter;

use App\Models\Square;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class SquareController extends Controller
{
    //

    public function publish(Request $request){

        $input = $request->input();
        $model=new Square();
        for ($i=0;$i<10000000;$i++){
            $value=array('user_id'=>1,'content'=>$i,'type'=>1);
            $res=$model->insertdate($value);
        }

        print_r($res);
    }
    public function index(Request $request){
        $model=new Square();
        $model->getFriendsSquare($request->get("content"));
    }
    public function test(Request $request){
        echo (int)(1566316814/(60*60*24*7));
    }
}
