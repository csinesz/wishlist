<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Wishlist extends Model
{
    protected $table        = 'wishlists';
	protected $primaryKey   = 'i_wishlist';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('userWishlists', function (Builder $builder) {
        	if (Auth::check() && !Auth::user()->hasRole('admin')) {
		        $builder->where('wishlists.i_user', '=', Auth::id());
	        }
        });
    }


    public function user() {
        return $this->hasOne(User::class, 'i_user', 'i_user');
    }

    public function items() {

        return $this->hasManyThrough(
            'App\WishItem',
            'App\WishlistItem',
            'i_wishlist',
            'i_wish_item',
            'i_wishlist',
            'i_wish_item'
        );
    }

    public function sumGross() {
        return $this->hasManyThrough(
            'App\WishItem',
            'App\WishlistItem',
            'i_wishlist',
            'i_wish_item',
            'i_wishlist',
            'i_wish_item'
        )->sum('gross');
    }

    public function createList($request) {

        $this->name             = $request->name;
        $this->i_user           = $request->i_user;
        $this->hash             = md5($request->name  . uniqid($request->i_user));
        $this->save();
    }

    public function updateList($request) {

        $this->name             = $request->name;
        $this->save();
    }


}
