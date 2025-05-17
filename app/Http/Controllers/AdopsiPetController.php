<?php

namespace App\Http\Controllers;

use App\Models\AdopsiPet;
use App\Models\Pengganti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdopsiPetController extends Controller
{
    public function store(Request $request)
    {
        // Validate with conditional rules
        $validated = $request->validate([
            'pet_name'         => 'required|string|max:255',
            'pet_category_id'  => 'required|exists:pet_categories,id',
            'breed_id'         => 'required|exists:breeds,id',
            'color'            => 'required|string|max:100',
            'age_id'           => 'required|exists:ages,id',
            'weight'           => 'required|numeric',
            'gender'           => 'required|in:female,male',
            'about_pet'        => 'nullable|string',
            'pictures'         => 'nullable|array',
            'pictures.*'       => 'image',
            'user_id'          => 'nullable|exists:users,id',
            // pengganti fields if user_id not provided
            'email'            => 'required_without:user_id|email',
            'handphonenumber'  => 'required_without:user_id|string',
            'address'          => 'required_without:user_id|string',
            'kelurahan'        => 'required_without:user_id|string',
            'kecamatan'        => 'required_without:user_id|string',
            'kota'             => 'required_without:user_id|string',
            'provinsi'         => 'required_without:user_id|string',
        ]);

        // Transaction to ensure atomicity
        $pet = DB::transaction(function() use ($validated, $request) {
            // Determine ownership
            if (!empty($validated['user_id'])) {
                $ownerId = $validated['user_id'];
                $penggantiId = null;
            } else {
                // Create Pengganti
                $pengganti = Pengganti::create([ 
                    'email'           => $validated['email'],
                    'handphonenumber' => $validated['handphonenumber'],
                    'address'         => $validated['address'],
                    'kelurahan'       => $validated['kelurahan'],
                    'kecamatan'       => $validated['kecamatan'],
                    'kota'            => $validated['kota'],
                    'provinsi'        => $validated['provinsi'],
                ]);
                $ownerId = null;
                $penggantiId = $pengganti->id;
            }

            // Create pet record without pictures first
            $pet = AdopsiPet::create([
                'pet_name'        => $validated['pet_name'],
                'pet_category_id' => $validated['pet_category_id'],
                'breed_id'        => $validated['breed_id'],
                'color'           => $validated['color'],
                'age_id'          => $validated['age_id'],
                'weight'          => $validated['weight'],
                'gender'          => $validated['gender'],
                'about_pet'       => $validated['about_pet'] ?? null,
                'user_id'         => $ownerId,
                'pengganti_id'    => $penggantiId,
                'pictures'        => [],
            ]);

            // Handle picture uploads
            $storedNames = [];
            if ($request->hasFile('pictures')) {
                foreach ($request->file('pictures') as $idx => $file) {
                    $timestamp = now()->timestamp;
                    $filename = $pet->id . '_' . $timestamp
                                . '_' . ($idx+1) . '.' . $file->extension();
                    // store in storage/app/public
                    // $file->storeAs('public', $filename);
                    $file->storeAs('', $filename, 'public');
                    $storedNames[] = $filename;
                }
                // update pet pictures
                $pet->pictures = $storedNames;
                $pet->save();
            }

            return $pet;
        });

        return response()->json(['data' => $pet], 201);
    }

    public function getAllPets() {
        // Fetch all pets, including their related data if necessary (e.g., user, category, breed, etc.)
        $pets = AdopsiPet::with(['category', 'breed', 'age', 'user', 'pengganti']) // Add any related models
                        ->whereNull('deleted_at') 
                        ->get();

        return response()->json(['data' => $pets], 200);
    }

    public function getPetDetails(Request $request){
        $validated = $request->validate([
            'id' => 'required|exists:adopsi_pets,id'
        ]);

        $pet = AdopsiPet::with(['category', 'breed', 'age', 'user', 'pengganti'])
                        ->find($validated['id']);

        if (!$pet) {
            return response()->json(['message' => 'Pet not found'], 404);
        }

        // Return the pet details as JSON response
        return response()->json(['data' => $pet], 200);
    }

    public function deletePet(Request $request)
    {
        // Validate that 'id' is provided in the request body
        $validated = $request->validate([
            'id' => 'required|exists:adopsi_pets,id' // Ensure the pet ID exists in the database
        ]);

        // Find the pet by its ID
        $pet = AdopsiPet::find($validated['id']);

        // Check if the pet exists
        if (!$pet) {
            return response()->json(['message' => 'Pet not found'], 404);
        }

        // Perform a soft delete
        $pet->delete();

        // Return success response
        return response()->json(['message' => 'Pet soft deleted successfully'], 200);
    }

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
            'color'           => 'nullable|string|max:100',
            'age_id'          => 'nullable|exists:ages,id',
            'weight'          => 'nullable|numeric',
            'gender'          => 'nullable|in:female,male',
            'about_pet'       => 'nullable|string',
            'pictures'        => 'nullable|array',
            'pictures.*'      => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $pet = AdopsiPet::find($id);
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
                $file->storeAs('', $filename, 'public');
                $storedNames[] = $filename;
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
