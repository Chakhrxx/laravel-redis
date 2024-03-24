<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
// use OpenApi\Attributes as OA;

class UserController extends Controller
{

    // Display a listing of the resource.
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
        return response()->json($users, 200);
    }

    // Store a newly created resource in storage.
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
        return response()->json(['message' => 'User has been created successfully!']);
    }

    // Display the specified resource.
    public function show(User $user)
    {
        $cachedUser = unserialize(Redis::get('user-' . $user));
        if ($cachedUser) {
            return response()->json($cachedUser, 200);
        }

        Redis::set('user-' . $user, serialize($user));
        Redis::expire('user-' . $user, 60);

        return response()->json($user, 200);
    }

    // Update the specified resource in storage.
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
        return response()->json(['message' => 'User has been updated successfully!']);
    }

    // Remove the specified resource from storage.
    public function destroy(User $user)
    {
        $user->delete();
        Redis::del('users');
        Redis::del('user-' . $user);
        return response()->json(['message' => 'User has been deleted successfully!']);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $users = User::where('name', 'LIKE', "$query%")->paginate(10);

        return view('pages.user.index', ['users' => $users]);
    }
}
