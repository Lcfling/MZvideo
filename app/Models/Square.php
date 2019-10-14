<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class Square extends Model
{
    //
    protected $table = 'friends_square';

    protected $commenttable="friends_square_comment";

    protected $replytable="friends_square_reply";

    //创建表
    public function createTable($tablename){
        DB::update('CREATE TABLE `'.$tablename.'` LIKE `'.$this->table.'`');
    }



    //插入广场动态
    public function insertdate(array $values)
    {
        $timeUnix=time();
        $idkey=$timeUnix.mt_rand(0,99999999);
        $tableName=$this->table.date("Ymd",$timeUnix);
        if(!Schema::hasTable($tableName)){
            $this->createTable($tableName);
        }

        //todo 根据values处理插入的数据
        $data=$values;

        $data['idkey']=$idkey;
        $data['creatime']=$timeUnix;
        DB::table($tableName)->insert($data);
        $result=DB::table($tableName)->where('idkey',$idkey)->select()->get()->toArray();
        if(!empty($result)){
            return $result;
        }else{
            return false;
        }

    }

    //获取朋友们发布的动态
    public function getFriendsSquare($userid){

        //$firendList=Ucenter::getList($userid);
        $firendList=[1,2];
        $squareList=$this->getS($firendList,time(),15);
        foreach ($squareList as &$value){
            $value['comment']=$this->getCommentByIdKey($value['idkey'],$value['creatime']);
            $value['commentcount']=15;//todo 查询总计
        }
        print_r($squareList);


    }
    public function getS($fiendList,$timeUnix,$count)
    {
        $tableName=$this->table.date("Ymd",$timeUnix);
        $prevtableName=$this->table.date("Ymd",$timeUnix-24*60*60);

        $result=DB::table($tableName)->whereIn('user_id',$fiendList)->orderBy('id', 'desc')->limit($count)->get()->toArray();

        if(count($result)<$count && Schema::hasTable($prevtableName)){
            $Newcount=$count-count($result);
            //echo $Newcount;
            //die();
            $newarr=$this->getS($fiendList,$timeUnix-24*60*60,$Newcount);

            return array_merge($result,$newarr);
        }else{
            return $result;
        }
    }
    public function getCommentByIdKey($idkey,$timeUnix){
        $tableName=$this->commenttable.date("Ymd",$timeUnix);
        $result=DB::table($tableName)->where('idkey',$idkey)->orderBy('id', 'desc')->limit(3)->get()->toArray();
        foreach ($result as &$value){
            
        }
        return $result;
    }
}
