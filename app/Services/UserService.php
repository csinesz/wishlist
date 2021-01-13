<?php
namespace App\Services;


use App\Services\Interfaces\iUser;
use App\User;
use App\WishItem;
use App\Wishlist;
use App\WishlistItem;
use Illuminate\Support\Facades\Auth;

class UserService implements iUser
{
    public function createUser($request): User {

        $newUser = new User();
        $newUser->createUser($request);

        // Connect role to user
        if (in_array($request->role, ['admin','user'])) {
            $newUser->assignRole($request->role);
        }

        return $newUser;
    }

    public function updateUser(User $user, $request): User {

        $user->updateUser($request);

        // Remove old role and connect new to user
        if (in_array($request->role, ['admin','user'])) {
            $user->syncRoles($request->role);
        }

        return $user;
    }

    public function deleteUser(User $user) {

        if ($user->i_user == Auth::id()) {
            throw new \Exception('You can not delete yourself');
        }

        if ($user->wishLists) {

            // Get wishlist ID's which are connected to user
            $wishListIDs    = array_keys($user->wishLists->keyBy('i_wishlist')->toArray());
            $wishItemIDs  = array_keys(WishlistItem::whereIn('i_wishlist', $wishListIDs)->get()->keyBy('i_wish_item')->toArray());

            WishlistItem::whereIn('i_wishlist', $wishListIDs)->delete();
            WishItem::whereIn('i_wish_item', $wishItemIDs)->delete();
            Wishlist::where('i_user', '=', $user->i_user)->delete();
        }

        $user->delete();

    }
}
