<?php

namespace App;

use App\Mail\NewUserWelcomeMail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use Notifiable;

    public function following(){
        return $this->belongsToMany(Profile::class);
    }

    protected static function boot(){

        parent::boot();

        // when a user is created, we will create a profile for them with their username
        
        static::created( function($user){
            $user->profile()->create([
                'title' => $user ->username,
            ]);
            //send email verification
            Mail::to($user->email)->send(new NewUserWelcomeMail());
        }); //might need to look at documentation


    }

    public function profile(){
        return $this ->hasOne(Profile::class);
    }

    public function posts(){
        return $this ->hasMany(Post::class)->orderBy('created_at', 'DESC');
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email','username', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
