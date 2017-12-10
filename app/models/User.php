<?php
namespace app\models;

use Illuminate\Database\Eloquent\Model;

class User extends Model{
    protected $guarded = ['userID'];
    protected $primaryKey = 'userID';

    public function address()
    {
        return $this->hasOne('app\models\Address', 'user_id'); 
    }

    public function orders()
    {
        return $this->hasMany('app\models\Order', 'user_id');
    }
}