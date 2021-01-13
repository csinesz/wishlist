<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWishlistItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wishlist_items', function (Blueprint $table) {
            $table->bigIncrements('i_wishlist_item');

            $table->unsignedBigInteger('i_wishlist')->nullable();
    		$table->foreign('i_wishlist')->references('i_wishlist')->on('wishlists');

            $table->unsignedBigInteger('i_wish_item')->nullable();
    		$table->foreign('i_wish_item')->references('i_wish_item')->on('wish_items');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wishlist_items');
    }
}
