<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\iWishList;
use App\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Validator;

class WishListController extends Controller
{

    // Readable validation texts for form response in case of any validation error
    protected function getValidationTexts(): array {
        return [
            'name.required'  => __('validation.filled',['attribute' => __('gui.wl_name')]),
        ];
    }

    /**
     * Display a listing of the wish lists.
     *
     * @return View
     */
    public function index(): View {
        return view('wishlists.index');
    }

    /**
     * Return all wishlist connected to auth. user - AJAX
     *
     * @return JsonResponse
     */
    public function getWishLists(): JsonResponse {
       try {
           $wishlists = Wishlist::orderBy('name')->with('items')->get();

           foreach ($wishlists as &$wishlist) {
               $wishlist->sumGross = $wishlist->sumGross();
           }
       }
       catch (\Exception $e) {
           Log::error($e);
           return response()->json(false, 500);
       }

       return response()->json($wishlists, 200);
    }

    /**
     * Store a newly created wishlist in storage.
     *
     * @param Request $request
     * @param iWishList $iWishList
     *
     * @return JsonResponse
     */
    public function store(Request $request, iWishList $iWishList): JsonResponse {

        $validator = Validator::make($request->all(),
            [
                'name' => 'required|max:255',
            ],
            $this->getValidationTexts()
        );

        if (!$validator->fails()) {
             try {
                DB::beginTransaction();
                    $iWishList->createWishList($request);
                DB::commit();

                return response()->json(true, 200);
            } catch (\Exception $e) {
                Log::error($e);
                $validator->after(function ($validator) {
                    $validator->errors()->add('error', __('gui.something_went_wrong'));
                });
            }
        }

        if ($validator->errors()->first() != "") {
            return response()->json(['errors' => $validator->errors()->all()], 200);
        }

    }

    /**
     * Get the specified wishlist.
     *
     * @param  Wishlist  $wishlist
     * @return JsonResponse
     */
    public function show(Wishlist $wishlist): JsonResponse {
        try {
            $return = Wishlist::where('i_wishlist','=', $wishlist->i_wishlist)
                        ->with('items')
                        ->first()
                        ->toArray();
            $return['sumGross'] = $wishlist->sumGross();

            return response()->json($return, 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(false, 500);
        }
    }

    /**
     * Show the form for editing the specified wishlist.
     *
     * @param  Wishlist  $wishlist
     *
     * @return View
     */
    public function edit(Wishlist $wishlist): View {
        return view('wishlists.edit', ['wishlist' => $wishlist]);
    }

    /**
     * Update the specified wishlist in storage. - AJAX
     *
     * @param Request $request
     * @param Wishlist $wishlist
     * @param iWishList $iWishList
     *
     * @return JsonResponse
     */
    public function update(Request $request, Wishlist $wishlist, iWishList $iWishList): JsonResponse {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|max:255',
            ],
            $this->getValidationTexts()
        );

        if (!$validator->fails()) {
             try {
                DB::beginTransaction();
                    $iWishList->updateWishList($wishlist, $request);
                DB::commit();

                return response()->json(true, 200);
            } catch (\Exception $e) {
                Log::error($e);
                $validator->after(function ($validator) {
                    $validator->errors()->add('error', __('gui.something_went_wrong'));
                });
            }
        }

        if ($validator->errors()->first() != "") {
            return response()->json(['errors' => $validator->errors()->all()], 200);
        }
    }

    /**
     * Remove the specified wishlist from storage.
     *
     * @param Request $request
     * @param Wishlist $wishlist
     * @param iWishList $iWishList
     *
     * @return RedirectResponse
     */
    public function destroy(Request $request, Wishlist $wishlist, iWishList $iWishList): RedirectResponse {
        try {
            if ($wishlist->i_user != auth()->id()) {
                throw new \Exception('Dont have permission to edit see others list item');
            }

            DB::beginTransaction();
                $iWishList->deleteWishList($wishlist);
            DB::commit();
            $request->session()->flash('success', 'delete');
        }
        catch (\Exception $e) {
            Log::error($e);
            $request->session()->flash('error', 'delete');
            return redirect()->back();
        }

        return redirect(route('wishlists.index'));
    }

    /**
     * Show shared wishlist without auth.
     *
     * @param String $hash
     *
     * @return View
     */
    public function shareWishlist(string $hash): View {
        try {
            $wishlist = Wishlist::where('hash','=',$hash)->first();
        }
        catch (\Exception $e) {
            Log::error($e);
            $wishlist = [];
        }

        return view('wishlists.share', ['wishlist' => $wishlist]);
    }
}
