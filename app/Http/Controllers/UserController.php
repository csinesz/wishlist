<?php

namespace App\Http\Controllers;

use App\Exceptions\UserException;
use App\Services\Interfaces\iUser;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Validator;
use Illuminate\View\View;
class UserController extends Controller
{

    // Readable validation texts for form response in case of any validation error
    protected function getValidationTexts() {
        return [
            'name.required'                => __('validation.filled',['attribute'  => __('gui.user_name')]),
            'username.required'            => __('validation.filled',['attribute'  => __('gui.user_username')]),
            'password.required'            => __('validation.filled',['attribute'  => __('gui.user_password')]),
            'username.unique'              => __('validation.unique',['attribute'  => __('gui.user_username')]),
            'admin.required'               => __('validation.filled',['attribute'  => __('gui.user_is_admin')]),
            'admin.boolean'                => __('validation.boolean',['attribute' => __('gui.user_is_admin')]),
            'status.required'              => __('validation.filled',['attribute'  => __('gui.user_status')]),
            'status.in'                    => __('validation.in',['attribute'      => __('gui.user_status')]),
        ];
    }

    /**
     * Display a listing of users.
     *
     * @param Request $request
     * @return View
     *
     */
    public function index(Request $request): View {
        try {
            $users = User::orderBy('name')->get();
        }
        catch (\Exception $e) {
            Log::error($e);
            $request->session()->flash('error', 'system');
            $users = [];
        }

        return view('users.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new user.
     *
     * @return View
     */
    public function create(): View {
        return view('users.store');
    }

    /**
     * Store a newly created user in storage.
     *
     * @param Request $request
     * @param iUser $iUser
     *
     * @return RedirectResponse
     */
    public function store(Request $request, iUser $iUser): RedirectResponse {

        $validator = Validator::make($request->all(),
            [
                'name' => 'required|max:255',
                'username' => 'required|max:255|',
                'role' => 'required|in:admin,user',
                'status' => 'required|in:active,inactive',
                'password' => [
                    'required',
                    'confirmed',
                    'string',
                    'min:10',                   // must be at least 10 characters in length
                    'regex:/[a-z]/',            // must contain at least one lowercase letter
                    'regex:/[A-Z]/',            // must contain at least one uppercase letter
                    'regex:/[0-9]/',            // must contain at least one digit
                    'regex:/[@$!%*#?&.-_,]/',   // must contain a special character
                ],
            ],
            $this->getValidationTexts()
        );

        if (!$validator->fails()) {

             try {

                $check = User::where('username','=', $request->username)->first();
                if ($check != null) {
                    throw new UserException(__('gui.user_username_taken'));
                }

                DB::beginTransaction();
                    $iUser->createUser($request);
                DB::commit();

            } catch (UserException $e) {
                Log::error($e);
                $validator->after(function ($validator) use ($e) {
                    $validator->errors()->add('username', $e->getMessage());
                });
            } catch (\Exception $e) {
                Log::error($e);
                $validator->after(function ($validator) {
                    $validator->errors()->add('error', __('gui.something_went_wrong'));
                });
            }
        }

        if ($validator->fails()) {
            return redirect(route('users.create'))->withErrors($validator)->withInput();
        }

        $request->session()->flash('success', 'create');
		return redirect(route('users.index'));
    }

    /**
     * Display the specified user.
     *
     * @param  User  $user
     * @return View
     */
    public function show(User $user): View {
		return view('users.store', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  User  $user
     * @return View
     */
    public function edit(User $user): View {
		return view('users.store', ['user' => $user]);
    }

    /**
     * Update the specified user in storage.
     *
     * @param Request $request
     * @param User $user
     * @param iUser $iUser
     *
     * @return RedirectResponse
     */
    public function update(Request $request, User $user, iUser $iUser): RedirectResponse {

        $validator = Validator::make($request->all(),
            [
                'name' => 'required|max:255',
                'username' => 'required|max:255',
                'role' => 'required|in:admin,user',
                'status' => 'required|in:active,inactive',
                'password' => [
                    'nullable',
                    'confirmed',
                    'string',
                    'min:10',                   // must be at least 10 characters in length
                    'regex:/[a-z]/',            // must contain at least one lowercase letter
                    'regex:/[A-Z]/',            // must contain at least one uppercase letter
                    'regex:/[0-9]/',            // must contain at least one digit
                    'regex:/[@$!%*#?&.-_,]/',   // must contain a special character
                ],
            ],
            $this->getValidationTexts()
        );


        if (!$validator->fails()) {
			try {

                if ($user->username != $request->username) {
                    $check = User::where('username','=', $request->username)->first();
                    if ($check != null) {
                        throw new UserException(__('gui.user_username_taken'));
                    }
                }

                DB::beginTransaction();
                    $iUser->updateUser($user, $request);
                DB::commit();
            } catch (UserException $e) {
                Log::error($e);
                $validator->after(function ($validator) use ($e) {
                    $validator->errors()->add('username', $e->getMessage());
                });
			} catch (\Exception $e) {
				Log::error($e);
				$validator->after(function ($validator) {
					$validator->errors()->add('error', __('gui.something_went_wrong'));
				});
			}
		}

        if ($validator->fails()) {
            return redirect(route('users.edit',[$user]))->withErrors($validator)->withInput();
        }

        $request->session()->flash('success', 'update');
		return redirect(route('users.index'));
    }

    /**
     * Remove the specified user from storage.
     *
     * @param Request $request
     * @param User $user
     * @param iUser $iUser
     *
     * @return RedirectResponse
     */
    public function destroy(Request $request, User $user, iUser $iUser): RedirectResponse {
        try {

            if ($user->id == auth()->id()) {
                throw new \Exception('You cannot delete yourself: ' . $user->id);
            }

            DB::beginTransaction();
                $iUser->deleteUser($user);
                $request->session()->flash('success', 'delete');
            DB::commit();
        }
        catch (\Exception $e) {
            Log::error($e);
            $request->session()->flash('error', 'delete');
            return redirect()->back();
        }

        return redirect(route('users.index'));

    }

}
