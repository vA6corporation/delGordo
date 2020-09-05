<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function session() {
        $user = Auth::user();
        $user = User::with('credentials')->find($user->id);
        return ['user' => $user];
    }

    public function index()
    {
        // $users = User::all();
        // return ['users' => $users];
        $users = User::paginate(18);
        return [
            'users' => $users->items(),
            'count' => $users->total(),
            'pages' => $users->lastPage(),
        ];
    }

    public function dealers()
    {
        $users = User::with('credentials')->get();
        return ['dealers' => $users];
        // return [
        //     'users' => $users->items(),
        //     'count' => $users->total(),
        //     'pages' => $users->lastPage(),
        // ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User($request->user);
        $user->password = Hash::make($request->user['password']);
        $user->save();
        return ['user' => $user];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return ['user' => $user];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // error_log(json_encode($request->user));
        $user = User::find($id);
        $user->fill($request->user);
        $user->save();
        // $user->password = Hash::make($request->user['password']);
        return ['user' => $user];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
