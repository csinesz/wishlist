<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WishlistItem extends Model
{
    protected $table        = 'wishlist_items';
	protected $primaryKey   = 'i_wishlist_item';
}
