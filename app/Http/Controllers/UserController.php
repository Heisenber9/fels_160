<?php

namespace App\Http\Controllers;

use Gate;
use Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', [
            'except' => [
                'show',
                'index',
                'destroy',
                'setAdmin',
            ]
        ]);
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $users = User::paginate(config('fels.paginate'));
        return view('user.index', compact('users'));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $currentUser = Auth::user();
        $user = User::findOrFail($id);

        return view('user.show', compact('user', 'currentUser'));
    }

    /**
     * @param UpdateUserRequest $request
     * @param $id
     * @return mixed
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        if (Gate::allows('update-info', $id)) {
            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $filename = str_slug($user->name) . '-' . $avatar->getClientOriginalName();
                $request->file('avatar')->move(base_path() . '/public/images/avatar/', $filename);
                $user->avatar = 'images/avatar/' . $filename;
            }

            $user->save();

            $request->session()->flash('success', trans('session.user_update_success'));

            return back();
        }

        abort(403, trans('error.403'));
    }

    /**
     * Delete a user.
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if ($user->delete()) {
            return redirect()->action('UserController@index')->withSuccess(trans('session.user_delete_success'));
        }

        return redirect()->back()
            ->withErrors(trans('session.delete_user_fail'));
    }

    /**
     *  Make a user become administrator.
     * @param $id
     * @return mixed
     */
    public function setAdmin($id)
    {
        $user = User::find($id);

        if ($user) {
            $updatedUser = $user->update(['is_admin' => true]);

            if ($updatedUser) {
                return redirect()->back()
                    ->withSuccess(trans('session.add_admin_success'));
            }

            return redirect()->back()
                ->withErrors(trans('session.add_admin_fail'));
        }

        return redirect()->action('UserController@show')
            ->withErrors(trans('session.not_found'));
    }
}
