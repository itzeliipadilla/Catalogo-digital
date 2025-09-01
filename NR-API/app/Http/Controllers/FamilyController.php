<?php

namespace App\Http\Controllers;

use App\Models\Family;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    public function get()
    {
        $families = Family::all();
        return response()->json([
            "familias" => $families
        ]);
    }
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:30',
            ]);
            $family = Family::create([
                'name' => $request->name
            ]);
            return response()->json([
                'message' => 'Familia registrada con éxito.',
                'familia' => $family
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Errores de validación',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrió un error al crear el usuario',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
