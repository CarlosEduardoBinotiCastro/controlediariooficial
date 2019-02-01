<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Http\Controllers\UserController;
use App\Notifications\ResetPassword;
use App\Http\Controllers\OrgaoRequisitanteController;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'grupoID', 'statusID', 'login', 'horaEnvio', 'primeiroLogin', 'orgaoID'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function setCaderno($trueOrFalse){
        self::$cadernoFatura = $trueOrFalse;
    }

    public function getCaderno(){
        $userController = new UserController();
        return $userController->cadernoFatura();
    }


    public function getOrgao(){
        $userController = new UserController();
        return $userController->orgaoNome();
    }

    public function sendPasswordResetNotification($token)
    {

        $this->notify(new ResetPassword($token));
    }
}
