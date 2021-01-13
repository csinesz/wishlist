<?php
namespace App\Services;
use App\Services\Interfaces\iWishItem;
use App\WishItem;
use App\WishlistItem;

class WishItemService implements iWishItem
{
    public function createWishItem($request): WishItem {
        $newWishItem = new WishItem();
        $newWishItem->createWishItem($request);

        $newWishListItem = new WishlistItem();
        $newWishListItem->i_wish_item = $newWishItem->i_wish_item;
        $newWishListItem->i_wishlist =  $request->wishlist;
        $newWishListItem->save();

        return $newWishItem;
    }

    public function updateWishItem(WishItem $wishItem, $request): WishItem {
        $wishItem->updateWishItem($request);

        return $wishItem;
    }

    public function deleteWishItem(WishItem $wishItem) {

        WishlistItem::where('i_wish_item', '=', $wishItem->i_wish_item)->delete();
        $wishItem->delete();
    }
}
