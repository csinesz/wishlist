<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'password', 'username'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    protected $primaryKey = 'i_user';

    public function wishLists() {
        return $this->hasMany(Wishlist::class, 'i_user', 'i_user');
    }

    public function isAdmin() {
        return $this->hasRole('admin');
    }

    public function isUser() {
        return $this->hasRole('user');
    }


    public function createUser($request) {

        $this->name             = $request->name;
        $this->username         = $request->username;
        $this->password         = Hash::make($request->password);
        $this->save();
    }

    public function updateUser($request) {

        if ($request->name) {
            $this->name = $request->name;
        }

        if ($request->username) {
            $this->username = $request->username;
        }

        if ($request->password) {
            $this->password         = Hash::make($request->password);
        }

        if ($request->status && in_array($request->status, ['active','inactive']) && $this->i_user != auth()->id()) {
            if ($request->status == 'active') {
                $this->active = true;
            }
            else {
                $this->active = false;
            }
        }

        $this->save();
    }


}
