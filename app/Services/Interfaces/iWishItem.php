<?php
namespace App\Services\Interfaces;

use App\WishItem;

Interface iWishItem
{

    public function createWishItem($request): WishItem;

    public function updateWishItem(WishItem $wishItem, $request): WishItem;

    public function deleteWishItem(WishItem $wishItem);


}
