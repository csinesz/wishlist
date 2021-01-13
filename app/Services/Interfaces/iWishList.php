<?php
namespace App\Services\Interfaces;

use App\Wishlist;

Interface iWishList
{

    public function createWishList($request): Wishlist;

    public function updateWishList(Wishlist $wishlist, $request): Wishlist;

    public function deleteWishList(Wishlist $wishlist);


}
