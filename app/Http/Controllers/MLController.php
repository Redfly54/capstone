<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class MLController extends Controller
{
    public function recommend(Request $request, $user_id)
    {
        $user = User::with('reference')->where('user_id', $user_id)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $reference = $user->reference;

        $data = [
            'jenis'     => $reference->animalType->name,
            'breed'     => $reference->Breed->name,
            'gender'    => $reference->animal_gender,
            'usia'      => $reference->age->category,
            'warna'     => $reference->color_count,
        ];

    $mlApiUrl = env('VITE_ML_API') . '/recommend';
    $response = Http::post($mlApiUrl, $data);

    // Return the ML API response (or handle errors as needed)
    if ($response->successful()) {
        $resultData = $response->json();
        $result = Result::create([
            'user_id' => $user->user_id,
            'posts'   => $resultData,
        ]);

        return response()->json([
            'message' => 'Result stored successfully',
            'result'  => $result,
        ]);

    } else {
        return response()->json(['message' => 'ML API error', 'error' => $response->body()], 500);
    }
    }
}
