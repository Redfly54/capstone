<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;


class UserController extends Controller
{
    public function index()
    {
        $users = User::with('reference')->get();
        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);
    }

    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6', 
            'phone' => 'required|string|max:13|min:10',
            'alamat' => 'required|string|max:255',
            'kelurahan' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:30',
            'kota' => 'required|string|max:30',
            'provinsi' => 'required|string|max:30',
            'animal_type' => 'required|string|max:30',
            'breed' => 'required|string|max:30',
            'animal_gender' => 'required|string|max:30',
            'age_group' => 'required|string|max:30',
            'color_count' => 'required|integer|max:30',
        ]);

            $validatedData['user_id'] = strtoupper(Str::random(2)) . str_pad(random_int(0, 999), 3, '0', STR_PAD_LEFT);            $validatedData['password'] = bcrypt($validatedData['password']);
            $validatedData['picture'] = 'images/default.png';
            $validatedData['description'] = 'Hello, I love animals and I am looking for a new friend that needs a forever home.';

            $user = User::create($validatedData);

            $user->reference()->create([
            'animal_type' => $validatedData['animal_type'],
            'breed' => $validatedData['breed'],
            'animal_gender' => $validatedData['animal_gender'],
            'age_group' => $validatedData['age_group'],
            'color_count' => $validatedData['color_count'],
        ]);

        // Return the user with the reference data
        return response()->json($user->load('reference'), 201);

        } catch (\Exception $e) {
            // Log the error and return a response
            \Log::error('Registration Error: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred during registration.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        // Validate the request
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate the user
        if (!auth()->attempt($credentials)) {
            return response()->json(['message' => 'Invalid email or password'], 401);
        }

        // Generate a token for the authenticated user
        $user = auth()->user();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return the token and user info
        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function profile()
    {
        $user = auth()->user(); 

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

         return response()->json($user);
    }

    public function updateDescription(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $request->validate([
            'description' => 'required|string|max:255',
        ]);

        $user->description = $request->description;
        $user->save();

        return response()->json(['message' => 'Description updated successfully', 'user' => $user]);
    }

    public function updatePicture(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $request->validate([
            'picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:40960', 
        ]);

        $path = $request->file('picture')->store('images', 'public');

        $user->picture = $path;
        $user->save();

        return response()->json([
            'message' => 'Profile picture updated successfully',
            'user' => $user
        ]);
    }

}