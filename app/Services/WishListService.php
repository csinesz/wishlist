<?php
namespace App\Services;
use App\Services\Interfaces\iWishList;
use App\WishItem;
use App\Wishlist;
use App\WishlistItem;

class WishListService implements iWishList
{
    public function createWishList($request): Wishlist {
        $newWishlist     = new Wishlist();
        $request->i_user = auth()->id();

        $newWishlist->createList($request);

        return $newWishlist;
    }

    public function updateWishList(Wishlist $wishlist, $request): Wishlist {
        $wishlist->updateList($request);

        return $wishlist;
    }

    public function deleteWishList(Wishlist $wishlist) {

        $wishItemIDs = array_keys($wishlist->items->keyBy('i_wish_item')->toArray());

        WishlistItem::where('i_wishlist','=', $wishlist->i_wishlist)->delete();

        WishItem::whereIn('i_wish_item', $wishItemIDs)->delete();

        $wishlist->delete();
    }

}
