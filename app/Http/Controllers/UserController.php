<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function createUser(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|unique:users,name',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'message' => 'Error',
                'error' => $validatedData->errors(),
                'status' => 400
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'is_admin' => 0,
            'password' => bcrypt($request->password),
        ]);

        return response()->json([
            'data' => $user,
            'message' => 'Created user successfully',
            'status' => 200
        ]);
    }

    public function getUserProfile()
    {
        $user = Auth::user();

        return response()->json($user);
    }

    public function getUser()
    {
        try {
            $user = Auth::user();

            if ($user->hasRole('admin') || $user->is_admin == 1) {
                // Admin → show all users
                // $users = User::all();
                $users = User::when(request('name'), function ($query, $name) {
                    $query->where(function ($q) use ($name) {
                        $q->where('name', 'like', "%{$name}%");
                    });
                })->get();
            } else {
                // Normal user → show only self
                $users = User::where('id', $user->id)->get();
            }

            if ($users->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No users found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $users
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response(['message' => 'User deleted successfully', 'status' => 200]);
        }
        return response(['message' => 'User not found', 'status' => 404]);
    }
    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            $user->update($request->all());
            return response(
                [
                    'data' => $user,
                    'message' => 'User updated successfully',
                    'status' => 200
                ],
                200
            );
        }
        return response(['message' => 'User not found', "status" => 404], 404);
    }
}
