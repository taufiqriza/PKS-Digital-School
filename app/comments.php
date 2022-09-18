<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class comments extends Model
{
    protected $fillable = ['content' , 'post_id' , 'user_id' , 'id'];

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

    public function posts()
    {
        return $this->belongsTo('App\Post');
    }

    public function users()
    {
        return $this->belongsTo('App\Users');
    }

}
