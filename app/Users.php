<?php

namespace App;


use App\Roles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class Users extends Authenticatable implements JWTSubject
{

    use Notifiable;

    protected $fillable = ['name' , 'email', 'user_name', 'roles_id', 'password', 'email_verified_at', 'id'];

    protected $hidden =[
        'password' , 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

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

    public function roles()
    {
        return $this->belongsTo('App\roles');
    }

    public function otp_code()
    {
        return $this->hasOne('App\OtpCode' , 'user_id');
    }

     // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function comments()
    {
        return $this->hasMany('App\Comments' , 'user_id');
    }

    public function posts()
    {
        return $this->hasMany('App\Post' , 'user_id');
    }
}
