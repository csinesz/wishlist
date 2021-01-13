<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\iWishItem;
use App\WishItem;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Validator;

class WishItemController extends Controller
{

    // Readable validation texts for form response in case of any validation error
    protected function getValidationTexts(): array {
        return [
            'name.required'                => __('validation.filled',['attribute'   => __('gui.wi_name')]),
            'wishlist.required'            => __('validation.filled',['attribute'   => __('gui.wishlist')]),
            'gross.required'               => __('validation.required',['attribute' => __('gui.wi_gross')]),
            'gross.numeric'                => __('validation.numeric',['attribute'  => __('gui.wi_gross')]),
            'gross.min'                    => __('validation.min',['attribute'      => __('gui.wi_gross')]),
        ];
    }

    /**
     * Store a newly created wish item in storage. - AJAX
     *
     * @param Request $request
     * @param iWishItem $iWishItem
     *
     * @return JsonResponse
     */
    public function store(Request $request, iWishItem $iWishItem): JsonResponse {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|max:255',
                'wishlist' => 'required|exists:wishlists,i_wishlist',
                'gross' => 'required|numeric|min:0',
            ],
            $this->getValidationTexts()
        );

        if (!$validator->fails()) {
             try {
                DB::beginTransaction();
                    $iWishItem->createWishItem($request);
                DB::commit();
            } catch (Exception $e) {
                Log::error($e);
                $validator->after(function ($validator) {
                    $validator->errors()->add('error', __('gui.something_went_wrong'));
                });
            }
        }

        if ($validator->errors()->first() != "") {
            return response()->json(['errors' => $validator->errors()->all()], 200);
        }

        return response()->json(true, 200);

    }

    /**
     * Display the specified wish item. -  AJAX
     *
     * @param  WishItem  $wishItem
     * @return JsonResponse
     */
    public function show(WishItem $wishItem): JsonResponse {
        try {
            if ($wishItem->wishList == null || $wishItem->wishList->i_user != auth()->id()) {
                throw new Exception('Dont have permission to edit see others list item');
            }

            return response()->json($wishItem->toArray(), 200);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(false, 500);
        }
    }

    /**
     * Update the specified wish item in storage. - AJAX
     *
     * @param Request $request
     * @param WishItem $wishItem
     * @param iWishItem $iWishItem
     *
     * @return JsonResponse
     */
    public function update(Request $request, WishItem $wishItem, iWishItem $iWishItem): JsonResponse {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|max:255',
                'gross' => 'required|numeric|min:0',
            ],
            $this->getValidationTexts()
        );

        if (!$validator->fails()) {
             try {
                if ($wishItem->wishList == null || $wishItem->wishList->i_user != auth()->id()) {
                    throw new Exception('Dont have permission to edit see others list item');
                }

                DB::beginTransaction();
                    $iWishItem->updateWishItem($wishItem, $request);
                DB::commit();
            } catch (Exception $e) {
                Log::error($e);
                $validator->after(function ($validator) {
                    $validator->errors()->add('error', __('gui.something_went_wrong'));
                });
            }
        }

        if ($validator->errors()->first() != "") {
            return response()->json(['errors' => $validator->errors()->all()], 200);
        }

        return response()->json(true, 200);
    }

    /**
     * Remove the specified wish item from storage. - AJAX
     *
     * @param WishItem $wishItem
     * @param iWishItem $iWishItem
     *
     * @return JsonResponse
     */
    public function destroy(WishItem $wishItem, iWishItem $iWishItem): JsonResponse {
         try {

            if ($wishItem->wishList == null || $wishItem->wishList->i_user != auth()->id()) {
                throw new Exception('Dont have permission to edit see others list item');
            }

            DB::beginTransaction();
                $iWishItem->deleteWishItem($wishItem);
            DB::commit();

            return response()->json(true, 200);

        } catch (Exception $e) {
            Log::error($e);
            return response()->json(false, 500);
        }
    }
}
