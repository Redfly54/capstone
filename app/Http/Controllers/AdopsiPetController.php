<?php

namespace App\Http\Controllers;

use App\Models\AdopsiPet;
use App\Models\Pengganti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                    $filename = $pet->id . '_' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
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
}
