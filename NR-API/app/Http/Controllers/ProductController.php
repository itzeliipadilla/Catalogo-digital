<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:40',
                'unit' => 'required|string|max:20',
                'description' => 'required|string',
                'dimension' => 'required|string|max:30',
                'active' => 'required|boolean',
                'image' => 'required|file|mimes:webp,jpeg,png|max:2048',
                'id_family' => 'required|integer|exists:families,id'
            ]);

            $imagePath = '';
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
            }

            Product::create([
                'name' => $validatedData['name'],
                'unit' => $validatedData['unit'],
                'description' => $validatedData['description'],
                'dimension' => $validatedData['dimension'],
                'active' => $validatedData['active'],
                'image' => $imagePath,
                'id_family' => $validatedData['id_family']
            ]);

            return response()->json([
                'message' => 'El producto se registró exitosamente.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Errores de validación',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrió un error al crear el producto',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $product = Product::findOrFail($id);

            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $product->delete();

            return response()->json([
                'message' => 'Producto eliminado con éxito.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Producto no encontrado.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrió un error al eliminar el producto.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:40',
                'unit' => 'required|string|max:20',
                'description' => 'required|string',
                'dimension' => 'required|string|max:30',
                'active' => 'required|boolean',
                'image' => 'nullable|file|mimes:webp,jpeg,png|max:2048',
                'id_family' => 'required|integer|exists:families,id'
            ]);

            $product = Product::findOrFail($id);

            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }

                $imagePath = $request->file('image')->store('products', 'public');
                $product->image = $imagePath;
            }

            $product->name = $validatedData['name'];
            $product->unit = $validatedData['unit'];
            $product->description = $validatedData['description'];
            $product->dimension = $validatedData['dimension'];
            $product->active = $validatedData['active'];
            $product->id_family = $validatedData['id_family'];

            $product->save();

            return response()->json([
                'message' => 'Producto actualizado con éxito.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Producto no encontrado.',
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Errores de validación',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrió un error al actualizar el producto',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function get()
    {
        try {
            $products = Product::orderBy('updated_at', 'desc')->get();;

            $products = $products->map(function ($product) {
                $family = Family::find($product->id_family);
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'family' => $family ? $family->name : null,
                    'updated_at' => $product->updated_at->format('d-m-Y'),
                ];
            });

            return response()->json([
                'products' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrió un error al recuperar los productos.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getPublic($family)
    {
        try {
            if ($family == 0) {
                $products = Product::where('active', true)
                    ->inRandomOrder()
                    ->get();
            } else {
                $products = Product::where('active', true)->where('id_family', $family)->orderBy('name', 'asc')->get();
            }


            $products = $products->map(function ($product) {
                $family = Family::find($product->id_family);
                $product->family = $family->name;
                $product->image = $product->image ? Storage::disk('public')->url($product->image) : null;
                return [
                    'image' => $product->image,
                    'familia' => $family ? $family->name : null,
                    'nombre' => $product->name,
                    'descripcion' => $product->description,
                    'unidad' => $product->unit,
                    'medida' => $product->dimension,
                ];
            });

            return response()->json([
                'message' => 'Productos recuperados con éxito.',
                'products' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrió un error al recuperar los productos.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getOne($id)
    {
        $product = Product::find($id);
        $product->image = $product->image ? Storage::disk('public')->url($product->image) : null;

        return response()->json([
            'product' => $product
        ], 200);
    }
}
