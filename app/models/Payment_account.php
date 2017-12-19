<?php
namespace app\models;

use Illuminate\Database\Eloquent\Model;

class Payment_account extends Model{
    protected $guarded = ['id'];

    public function method()
    {
        return $this->hasOne('\app\models\Payment_method', 'id', 'method_id');
    }
}