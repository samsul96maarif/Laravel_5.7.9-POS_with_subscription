<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // fungsi untuk mengecek apakakah admin atau bukan
    public function isAdmin()
    {
      // mengecek bila kondisi role "1" berarti true berarti dia admin
      return $this->admin;
    }

    public function users()
    {
      $users = user::all()
      ->where('admin', false)
      ->where('role', 1);

      return $this->$users;
    }
}
