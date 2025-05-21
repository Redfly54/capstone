<?php
namespace App\Http\Controllers;

use App\Models\AdopsiPet;
use App\Models\Pengganti;
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

    public function getBreeds (Request $request)
    {
        $query = DB::table('breeds');
        if ($request->has('category_id')) {
            $query->where('pet_category_id', $request->category_id);
        }
        $breeds = $query->get();
        return response()->json($breeds);
    }

    public function getAges ()
    {
        $ages = DB::table('ages')->get();
        return response()->json($ages);
    }

}