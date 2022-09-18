<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Roles extends Model
{
    protected $fillable = ['name' , 'id'];

    protected $prymaryKey = 'id' ;

    protected $keyType = 'string';

    public $incrementing = false;

    protected  static function boot()

    {
        parent::boot();

        static::creating(function($model){
            if( empty($model->id) ) {
                $model->id = Str::uuid();
            }
        });
    }

    public function users()
    {
        return $this->hasMany('App\users');
    }
}

