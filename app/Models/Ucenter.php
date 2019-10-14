<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ucenter extends Model
{
    //

    protected $table="users";
    protected $primaryKey = 'uid';
    protected $fillable = ['username', 'mobile', 'password'];
    protected $hidden = ['password', 'remember_token'];

    public function index(){

    }


    //开启事务 保证更改唯一
    public function addMoney($money,$userid){
        DB::beginTransaction();
        $usermoney=DB::table('users_money')->where("userid",$userid)->lockForUpdate()->first();
        if($usermoney+$money>0){
            DB::table('users_money')->where("userid",$userid)->decrement('money', $money);
            DB::commit();
            return true;
        }else{
            return false;
        }
    }
    //开启事务 保证更改唯一
    public function reduceMoney($money,$userid){
        DB::beginTransaction();
        $usermoney=DB::table('users_money')->where("userid",$userid)->lockForUpdate()->first();
        if($usermoney+$money>0){
            DB::table('users_money')->where("userid",$userid)->decrement('money', $money);
            DB::commit();
            return true;
        }else{
            return false;
        }
    }
}
