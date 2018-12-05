<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Http\Controllers\UserController;

class User extends Authenticatable
{
    use Notifiable;

    public static  $cadernoID;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'grupoID', 'statusID', 'login', 'horaEnvio', 'primeiroLogin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function setCaderno($idCarderno){
        self::$cadernoID = $idCarderno;
    }

    public function getCaderno(){
        return self::$cadernoID;
    }

}
