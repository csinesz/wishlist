<?php

namespace App\Providers;

use App\Services\Interfaces\iUser;
use App\Services\Interfaces\iWishItem;
use App\Services\Interfaces\iWishList;
use App\Services\UserService;
use App\Services\WishItemService;
use App\Services\WishListService;
use Illuminate\Support\ServiceProvider;

class WishlistServiceProvider extends ServiceProvider
{
	public $singletons = [
        iUser::class                => UserService::class,
        iWishList::class            => WishListService::class,
        iWishItem::class            => WishItemService::class
    ];

	/**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Library\Services\Interfaces\iWishItem', function () {
        	return new WishItemService();
        });

        $this->app->singleton('App\Library\Services\Interfaces\iWishList', function () {
        	return new WishListService();
        });

        $this->app->singleton('App\Library\Services\Interfaces\iUser', function () {
        	return new UserService();
        });

    }

}
