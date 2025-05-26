<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Pengganti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function store(Request $request)
    {
        // Validate with conditional rules
        $validated = $request->validate([
            'pet_name'         => 'required|string|max:255',
            'pet_category_id'  => 'required|exists:pet_categories,id',
            'breed_id'         => 'required|exists:breeds,id',
            'color_count'      => 'required|string|max:100',
            'age_id'           => 'required|exists:ages,id',
            'weight'           => 'required|numeric',
            'gender'           => 'required|in:Betina,Jantan',
            'about_pet'        => 'nullable|string',
            'pictures'         => 'nullable|array',
            'pictures.*'       => 'image|max:3072|mimes:jpg,png,jpeg,gif,svg',
            'user_id'          => 'nullable|exists:users,user_id',
            'email'            => 'required|email',
            'phone'            => 'required|string',
            'address'          => 'required|string',
            'kelurahan'        => 'required|string',
            'kecamatan'        => 'required|string',
            'kota'             => 'required|string',
            'provinsi'         => 'required|string',
        ]);

        // Transaction to ensure atomicity
        $pet = DB::transaction(function() use ($validated, $request) {

            // Create pet record without pictures first
            $pet = Post::create([
                'pet_name'        => $validated['pet_name'],
                'pet_category_id' => $validated['pet_category_id'],
                'breed_id'        => $validated['breed_id'],
                'color_count'     => $validated['color_count'],
                'age_id'          => $validated['age_id'],
                'weight'          => $validated['weight'],
                'gender'          => $validated['gender'],
                'about_pet'       => $validated['about_pet'] ?? null,
                'user_id'         => $validated['user_id'],     
                'pictures'        => [],
                'email'           => $validated['email'],
                'phone'           => $validated['phone'],
                'address'         => $validated['address'],
                'kelurahan'       => $validated['kelurahan'],
                'kecamatan'       => $validated['kecamatan'],
                'kota'            => $validated['kota'],
                'provinsi'        => $validated['provinsi'],
            ]);

            // Handle picture uploads
            $storedNames = [];
            if ($request->hasFile('pictures')) {
                foreach ($request->file('pictures') as $idx => $file) {
                    $timestamp = now()->timestamp;
                    $filename = $pet->id . '_' . $timestamp
                                . '_' . ($idx+1) . '.' . $file->extension();
                    // store in storage/app/public/images
                    // $file->storeAs('public/images', $filename);
                    $file->storeAs('images', $filename, 'public');
                    $storedNames[] = 'images/' . $filename;
                }
                // update pet pictures
                $pet->pictures = $storedNames;
                $pet->save();
            }

            return $pet;
        });

        return response()->json(['data' => $pet], 201);
    }

    public function getAllPets(Request $request) {
        // Fetch all pets, including their related data if necessary (e.g., user, category, breed, etc.)
        $query = Post::with(['category', 'breed', 'age', 'user'])
                 ->whereNull('deleted_at');

        // If user_id is provided in the query, filter by it
        if ($request->has('user_id')) {
            $query->where('user_id', $request->query('user_id'));
        }

        $pets = $query->get();

        return response()->json(['data' => $pets], 200);
    }

    public function getPetDetails($id)
    {
        $pet = Post::with(['category', 'breed', 'age', 'user'])
                    ->find($id);

        if (!$pet) {
            return response()->json(['message' => 'Pet not found'], 404);
        }

        return response()->json(['data' => $pet], 200);
    }

    public function deletePet($id)
    {
        $pet = Post::find($id);

        if (!$pet) {
            return response()->json(['message' => 'Pet not found'], 404);
        }

        $pet->delete();

        return response()->json(['message' => 'Pet soft deleted successfully'], 200);
    }

    // // Determine ownership
    //         if (!empty($validated['user_id'])) {
    //             $ownerId = $validated['user_id'];
    //             $penggantiId = null;
    //         } else {
    //             // Create Pengganti
    //             $pengganti = Pengganti::create([ 
    //                 'email'           => $validated['email'],
    //                 'handphonenumber' => $validated['handphonenumber'],
    //                 'address'         => $validated['address'],
    //                 'kelurahan'       => $validated['kelurahan'],
    //                 'kecamatan'       => $validated['kecamatan'],
    //                 'kota'            => $validated['kota'],
    //                 'provinsi'        => $validated['provinsi'],
    //             ]);
    //             $ownerId = null;
    //             $penggantiId = $pengganti->id;
    //         }

    // public function updatePet(Request $request, $id)
    // {
    //     // dd($id);
    //     // dd( $request->all());
    //     // Validate the input data
    //     $validated = $request->validate([
    //         'pet_name'         => 'nullable|string|max:255',
    //         'pet_category_id'  => 'nullable|exists:pet_categories,id',
    //         'breed_id'         => 'nullable|exists:breeds,id',
    //         'color'            => 'nullable|string|max:100',
    //         'age_id'           => 'nullable|exists:ages,id',
    //         'weight'           => 'nullable|numeric',
    //         'gender'           => 'nullable|in:female,male',
    //         'about_pet'        => 'nullable|string',
    //         'pictures'         => 'nullable|array',
    //         'pictures.*'       => 'image',
    //         // Validate other fields as needed
    //     ]);

    //     // dd('Validated data:', $validated);

    //     // Find the pet by ID
    //     $pet = AdopsiPet::find($id);

    //     // dd($pet);

    //     // If the pet is not found, return a 404 response
    //     if (!$pet) {
    //         return response()->json(['message' => 'Pet not found'], 404);
    //     }

    //     $pet->fill($validated);
    //     $pet->save();

    //     // Update the pet details
    //     // $pet->update([
    //     //     'pet_name'        => $validated['pet_name'] ?? null,
    //     //     'pet_category_id' => $validated['pet_category_id'] ?? null,
    //     //     'breed_id'        => $validated['breed_id'] ?? null,
    //     //     'color'           => $validated['color'] ?? null,
    //     //     'age_id'          => $validated['age_id'] ?? null,
    //     //     'weight'          => $validated['weight'] ?? null,
    //     //     'gender'          => $validated['gender'] ?? null,
    //     //     'about_pet'       => $validated['about_pet'] ?? null,
    //     // ]);

    //     if ($request->hasFile('pictures')) {
    //         // Delete old pictures from storage if they exist
    //         if (!empty($pet->pictures)) {
    //             // Loop through each old picture and delete it from the storage
    //             foreach ($pet->pictures as $oldPicture) {
    //                 // Construct the full path to the file (relative to the 'public' disk)
    //                 $filePath = 'public/' . $oldPicture;

    //                 // Check if the file exists and delete it
    //                 if (Storage::disk('public')->exists($filePath)) {
    //                     Storage::disk('public')->delete($filePath);
    //                 }
    //             }
    //         }
    //     }

    //     // Handle picture updates (if any)
    //     $storedNames = [];
    //     if ($request->hasFile('pictures')) {
    //         foreach ($request->file('pictures') as $idx => $file) {
    //             // Generate a unique file name
    //             $filename = $pet->id . '_' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
    //                         . '_' . ($idx + 1) . '.' . $file->extension();

    //             // Store the picture in public storage
    //             $file->storeAs('', $filename, 'public');
    //             $storedNames[] = $filename;
    //         }

    //         // Update the pet's pictures
    //         $pet->pictures = $storedNames;
    //         $pet->save();
    //     }

    //     // Return the updated pet record
    //     return response()->json(['data' => $pet], 200);
    // }

    public function updatePet(Request $request, $id)
    {
        $validated = $request->validate([
            'pet_name'        => 'nullable|string|max:255',
            'pet_category_id' => 'nullable|exists:pet_categories,id',
            'breed_id'        => 'nullable|exists:breeds,id',
            'color_count'     => 'nullable|string|max:100',
            'age_id'          => 'nullable|exists:ages,id',
            'weight'          => 'nullable|numeric',
            'gender'          => 'nullable|in:Betina,Jantan',
            'about_pet'       => 'nullable|string',
            'pictures'        => 'nullable|array',
            'pictures.*'      => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $pet = Post::find($id);
        if (!$pet) {
            return response()->json(['message' => 'Pet not found'], 404);
        }

        // Isi atribut dari validated (partial update)
        

        // Handle pictures update jika ada
        if ($request->hasFile('pictures')) {
            // Hapus gambar lama jika ada dan berupa array
            if (is_array($pet->pictures)) {
                foreach ($pet->pictures as $oldPicture) {
                    $filePath = $oldPicture;
                    if (Storage::disk('public')->exists($filePath)) {
                        Storage::disk('public')->delete($filePath);
                         Log::info("Deleted old picture: " . $filePath);
                    }else{
                         // Log jika gambar lama tidak ditemukan
                         Log::warning("Old picture not found: " . $filePath);
                    }
                }
            }

            $storedNames = [];
            foreach ($request->file('pictures') as $idx => $file) {
                $timestamp = now()->timestamp;
                $filename = $pet->id . '_' . $timestamp
                                . '_' . ($idx+1) . '.' . $file->extension();

                Log::info("Saving file: " . $filename);
                $file->storeAs('images', $filename, 'public');
                $storedNames[] = 'images/' . $filename;
            }
                Log::info("Pictures to save: ", $storedNames);

            // $pet->pictures = $storedNames;

        }

        $pet->fill($validated);
        $pet->pictures = $storedNames;
        $pet->save();

        return response()->json(['data' => $pet], 200);
    }



}
