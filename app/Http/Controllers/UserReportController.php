<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserReportController extends Controller
{
    public function getUserReport()
    {
        try {
            $user = Auth::user();

            // If admin → can view all users with their reports
            if ($user->hasRole('admin') || $user->is_admin == 1) {
                $users = User::with('reports')
                    ->when(request('user_id'), function ($query, $userId) {
                        $query->where('id', $userId); // filter by user id
                    })
                    // Filter by date range if provided
                    ->when(request('from_date') && request('to_date'), function ($query) {
                        $from = request('from_date'); // expected format: YYYY-MM-DD
                        $to = request('to_date');     // expected format: YYYY-MM-DD
                        $query->whereBetween('date', [$from, $to]);
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                // Normal user → only own reports
                $users = User::with('reports')
                    ->where('id', $user->id) // user primary key
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            if ($users->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No users found',
                ], 404);
            }

            return response()->json([
                'data' => $users,
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong.',
                'status' => 500,
                'error' => $e->getMessage(), // hide in production if needed
            ], 500);
        }
    }

    public function getUserReportById($id)
    {
        try {
            // Example: eager load reports with user
            $user = User::with('reports')->find($id);

            if (!$user) {
                return response()->json([
                    'status' => 404,
                    'message' => 'User not found',
                ], 404);
            }

            return response()->json([
                'status' => 200,
                'data' => ['reports' => $user->reports],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong.',
                'status' => 500,
                'error' => $e->getMessage(), // hide in production if needed
            ], 500);
        }
    }
}
