<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = ['title' , "description", 'user_id' , 'id'];

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

    public function comments()
    {
        return $this->hasMany('App\comments');
    }
    
    public function users()
    {
        return $this->belongsTo('App\users');
    }

}
