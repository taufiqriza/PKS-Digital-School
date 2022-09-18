<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    protected $fillable = ['otp' , 'user_id' , 'valid_until'];

    protected $prymaryKey = 'id' ;

    protected $keyType = 'string';

    public $incrementing = false;

    protected  static function boot()

    {
        parent::boot();

        static::creating(function($model){
            if( empty($model->{$model->getKeyName()}) ) {
                $model->{$model->getKeyName()} = Str::uuid();
            }
        });
    }

    public function users()
    {
        return $this->belongsTo('App\Users');
    }
}
