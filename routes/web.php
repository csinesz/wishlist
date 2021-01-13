<?php

// Auth routes
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', [
    'uses'          => 'Auth\LoginController@login',
    'middleware'    => 'checkstatus',
]);
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/', function () {
    return view('welcome');
});

// Protected routes
Route::group(['middleware' => ['auth','checkstatus']], function () {

    Route::group(['middleware' => ['role:admin']], function () {
        Route::resource('users', 'UserController');
    });

    Route::group(['middleware' => ['role:user']], function () {

        // Wishlist routes
        Route::get('wishlists', 'WishListController@index')->name('wishlists.index');
        Route::get('get-wishlists', 'WishListController@getWishlists')->middleware('accesviaajax')->name('wishlists.get');

        Route::delete('wishlists/{wishlist}', 'WishListController@destroy')->name('wishlists.delete');
        Route::put('wishlists/{wishlist}', 'WishListController@update')->middleware('accesviaajax')->name('wishlists.update');
        Route::post('wishlists', 'WishListController@store')->middleware('accesviaajax')->name('wishlists.store');
        Route::get('wishlists/{wishlist}/edit', 'WishListController@edit')->name('wishlists.edit');
        Route::get('wishlists/{wishlist}/get', 'WishListController@show')->middleware('accesviaajax')->name('wishlist.get');

        // WishItem routes
        Route::get('wishitem/{wish_item}/get', 'WishItemController@show')->middleware('accesviaajax')->name('wishitem.get');
        Route::put('wishitem/{wish_item}', 'WishItemController@update')->middleware('accesviaajax')->name('wishitem.update');
        Route::post('wishitem/', 'WishItemController@store')->middleware('accesviaajax')->name('wishitem.store');
        Route::delete('wishitem/{wish_item}', 'WishItemController@destroy')->middleware('accesviaajax')->name('wishitem.delete');

    });
});

// Share link
Route::get('wishlists/share/{hash}', 'WishListController@shareWishlist')->name('wishlists.share');

