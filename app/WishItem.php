<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WishItem extends Model
{
    protected $table        = 'wish_items';
	protected $primaryKey   = 'i_wish_item';

    public function wishList() {
        return $this->hasOneThrough(
            'App\Wishlist',
            'App\WishlistItem',
            'i_wish_item',
            'i_wishlist',
            'i_wish_item',
            'i_wishlist'
        );
    }

    public function createWishItem($request) {
		$this->name     = $request->name;
		$this->gross    = $request->gross;
		$this->save();
	}

	public function updateWishItem($request) {
        if ($request->name) {
            $this->name = $request->name;
        }

        if (array_key_exists('gross', $request->all())) {
            $this->gross = (int)$request->gross;
        }
		$this->save();
	}


}
