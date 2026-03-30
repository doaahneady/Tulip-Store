<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
            'identifier' => 'nullable|string',
            'username' => 'nullable|string',
            'email' => 'nullable|string',
        ]);

        $loginValue = trim((string) ($request->input('identifier') ?? $request->input('username') ?? $request->input('email') ?? ''));
        if ($loginValue === '') {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => [
                    'identifier' => ['The identifier field is required.'],
                ],
            ], 422);
        }

        $employee = Employee::query()
            ->where('email', $loginValue)
            ->orWhere('employee_code', $loginValue)
            ->orWhere('employee_id', $loginValue)
            ->first();

        if (! $employee) {
            $user = User::where('username', $loginValue)->first();
            if ($user) {
                $employee = Employee::where('user_id', $user->id)->first();
            }
        }

        if (! $employee || ! Hash::check((string) $request->password, (string) $employee->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        if ($employee->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Your account is not active. Please contact HR.',
            ], 403);
        }

        $token = $employee->createToken('employee-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'employee' => [
                'id' => $employee->id,
                'user_id' => $employee->user_id,
                'email' => $employee->email,
                'name' => $employee->full_name ?? null,
                'department' => $employee->department ?? null,
                'position' => $employee->position ?? null,
            ],
            'token' => $token,
        ], 200);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if (! ($user instanceof Employee)) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden',
            ], 403);
        }

        $request->user()->currentAccessToken()?->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ], 200);
    }
}

