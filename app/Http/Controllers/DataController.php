<?php
namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PetCategory;
use App\Models\Breed;
use App\Models\Age;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DataController extends Controller{

    public function getPetCategories ()
    {
        $petCategories = DB::table('pet_categories')->get();
        return response()->json($petCategories);
    }

    public function addPetCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
        ]);
        $category = PetCategory::create($validated);
        return response()->json($category, 201);
    }

    public function editPetCategory(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'icon' => 'nullable|string|max:255',
        ]);
        $category = PetCategory::findOrFail($id);
        $category->update($validated);
        return response()->json($category);
    }

    public function deletePetCategory($id)
    {
        $category = PetCategory::findOrFail($id);
        $category->delete();
        return response()->json(['message' => 'Category deleted']);
    }

    public function getBreeds (Request $request)
    {
        $query = DB::table('breeds');
        if ($request->has('category_id')) {
            $query->where('pet_category_id', $request->category_id);
        }
        $breeds = $query->get();
        return response()->json($breeds);
    }

    public function addBreed(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'pet_category_id' => 'required|exists:pet_categories,id',
        ]);
        $breed = Breed::create($validated);
        return response()->json($breed, 201);
    }

    // Edit a breed
    public function editBreed(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'pet_category_id' => 'sometimes|required|exists:pet_categories,id',
        ]);
        $breed = Breed::findOrFail($id);
        $breed->update($validated);
        return response()->json($breed);
    }

    // Soft delete a breed
    public function deleteBreed($id)
    {
        $breed = Breed::findOrFail($id);
        $breed->delete();
        return response()->json(['message' => 'Breed deleted']);
    }

    public function getAges ()
    {
        $ages = DB::table('ages')->get();
        return response()->json($ages);
    }

}