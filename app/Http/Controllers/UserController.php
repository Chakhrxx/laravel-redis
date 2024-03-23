<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $key = 'users';
        if (!Redis::exists($key)) {
            $users = User::orderBy('id', 'desc')->paginate(10);
            Redis::set($key, serialize($users));
            Redis::expire($key, 60);
        } else {
            $users = unserialize(Redis::get($key));
        }
        return view('pages.user.index', ['users' => $users]);
    }

    public function create()
    {
        return view('pages.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'phone' => $request->input('phone'),
            'password' => Hash::make($request->input('password'), ['rounds' => 10]),
        ]);
        Redis::del('users');
        Redis::setex('message', 4, 'User has been created successfully!');
        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $cachedUser = Redis::get('user-' . $user);
        if ($cachedUser) {
            return response()->json(json_decode($cachedUser), 200);
        }

        Redis::setex('user-' . $user, 60, json_encode($user));
        return response()->json($user, 200);
    }

    public function edit(User $user)
    {
        return view('pages.user.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {

        if ($request->input('password') === "") {
            $password = $user->password;
        } else {
            $password = Hash::make($request->input('password'), ['rounds' => 10]);
        }

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'phone' => $request->input('phone'),
            'password' => $password,
        ]);

        Redis::del('users');
        Redis::del('user-' . $user);
        Redis::setex('method', 4, 'PUT');
        Redis::setex('message', 4, 'User has been updated successfully!');

        return  redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        Redis::del('users');
        Redis::del('user-' . $user);
        Redis::setex('method', 4, 'DEL');
        Redis::setex('message', 4, 'User has been deleted successfully!');

        return redirect()->route('user.index');
    }
    public function search(Request $request)
    {
        $query = $request->input('query');
        $users = User::where('name', 'LIKE', "$query%")->paginate(10);

        return view('pages.user.index', ['users' => $users]);
    }
}
