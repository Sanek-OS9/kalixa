<?php
namespace app\models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo('app\models\User');
    }
}