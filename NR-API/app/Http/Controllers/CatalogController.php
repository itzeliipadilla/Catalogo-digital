<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{

    public function getInfo(Request $request)
    {
        // Validar que el campo 'familias' esté presente en la solicitud
        $request->validate([
            'familias' => 'required|array',
            'familias.*' => 'string'
        ]);

        // Obtener las familias que coincidan con los nombres proporcionados
        $families = Family::whereIn('name', $request->familias)->pluck('id');

        // Obtener los productos asociados a esas familias y ordenarlos por 'id_family'
        $products = Product::whereIn('id_family', $families)
            ->where('active',true)
            ->orderBy('id_family')
            ->orderBy('name')
            ->get()
            ->map(function ($product) {
                $path = explode('/', $product->image);
                $product->image = end($path);
                $product->family = Family::find($product->id_family)->name;
                return $product;
            });

        return response()->json(['products' => $products]);
    }




    public function getImage($imagen)
    {
        $filePath = public_path('storage/products/' . $imagen);

        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            return response()->json([
                'error' => 'Archivo no encontrado.'
            ], 404);
        }
    }
}
