<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    // #[
    //     OA\Get(
    //         path: "/api/v1/user",
    //         summary: "List all users",
    //         tags: ["User"],
    //         responses: [
    //             new OA\Response(response: Response::HTTP_OK, description: "Users retrieved successfully!"),
    //             new OA\Response(response: Response::HTTP_UNAUTHORIZED, description: "Unauthorized"),
    //             new OA\Response(response: Response::HTTP_NOT_FOUND, description: "Not Found"),
    //             new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: "Server Error")
    //         ]
    //     )
    // ]
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

    // #[
    //     OA\Get(
    //         path: "/api/v1/user/{id}",
    //         summary: "Get a user by ID",
    //         tags: ["User"],
    //         parameters: [
    //             new OA\Parameter(
    //                 description: "User ID",
    //                 in: "path",
    //                 name: "id",
    //                 required: true,
    //                 schema: new OA\Schema(type: "integer")
    //             )
    //         ],
    //         responses: [
    //             new OA\Response(response: Response::HTTP_OK, description: "User retrieved success"),
    //             new OA\Response(response: Response::HTTP_UNAUTHORIZED, description: "Unauthorized"),
    //             new OA\Response(response: Response::HTTP_NOT_FOUND, description: "not found"),
    //             new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: "Server Error")
    //         ]
    //     )
    // ]
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

    // #[
    //     OA\Post(
    //         path: "/api/v1/user",
    //         summary: "Create a user",
    //         requestBody: new OA\RequestBody(
    //             required: true,
    //             content: new OA\MediaType(
    //                 mediaType: "application/x-www-form-urlencoded",
    //                 schema: new OA\Schema(
    //                     required: ["name", "email", "password", "address", "phone"],
    //                     properties: [
    //                         new OA\Property(property: 'name', description: "User name", type: "string"),
    //                         new OA\Property(property: 'email', description: "User email", type: "string"),
    //                         new OA\Property(property: 'password', description: "User password", type: "string"),
    //                         new OA\Property(property: 'address', description: "User address", type: "string"),
    //                         new OA\Property(property: 'phone', description: "User phone", type: "string"),
    //                     ]
    //                 )
    //             )
    //         ),
    //         tags: ["User"],
    //         responses: [
    //             new OA\Response(response: Response::HTTP_CREATED, description: "User has been created successfully!"),
    //             new OA\Response(response: Response::HTTP_UNPROCESSABLE_ENTITY, description: "Unprocessable entity"),
    //             new OA\Response(response: Response::HTTP_BAD_REQUEST, description: "Bad Request"),
    //             new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: "Server Error")
    //         ]
    //     )
    // ]
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

    // #[
    //     OA\Put(
    //         path: "/api/v1/user/{id}",
    //         summary: "Update a user by ID",
    //         tags: ["User"],
    //         parameters: [
    //             new OA\Parameter(
    //                 description: "User ID",
    //                 in: "path",
    //                 name: "id",
    //                 required: true,
    //                 schema: new OA\Schema(type: "integer")
    //             )
    //         ],
    //         requestBody: new OA\RequestBody(
    //             required: true,
    //             content: [
    //                 new OA\MediaType(
    //                     mediaType: "application/json",
    //                     schema: new OA\Schema(type: "object")
    //                 )
    //             ]
    //         ),
    //         responses: [
    //             new OA\Response(response: Response::HTTP_OK, description: "User updated success"),
    //             new OA\Response(response: Response::HTTP_UNAUTHORIZED, description: "Unauthorized"),
    //             new OA\Response(response: Response::HTTP_NOT_FOUND, description: "not found"),
    //             new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: "Server Error")
    //         ]
    //     )
    // ]
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

    #[
        OA\Delete(
            path: "/api/v1/user/{id}",
            summary: "Delete a user by ID",
            tags: ["User"],
            parameters: [
                new OA\Parameter(
                    description: "User ID",
                    in: "path",
                    name: "id",
                    required: true,
                    schema: new OA\Schema(type: "integer")
                )
            ],
            responses: [
                new OA\Response(response: Response::HTTP_OK, description: "User deleted success"),
                new OA\Response(response: Response::HTTP_UNAUTHORIZED, description: "Unauthorized"),
                new OA\Response(response: Response::HTTP_NOT_FOUND, description: "not found"),
                new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: "Server Error")
            ]
        )
    ]
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
