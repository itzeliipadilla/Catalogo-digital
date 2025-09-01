<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function get()
    {
        $users = User::all();
        return response()->json([
            'users' => $users
        ], 200);
    }

    public function getOne($id)
    {
        $user = User::findOrFail($id);
        return response()->json([
            'user' => $user
        ], 200);
    }

    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:50',
                'phone' => 'required|string|size:10',
                'active' => 'required|boolean',
                'role' => 'required|string|max:25',
            ]);

            User::create([
                'name' => $validatedData['name'],
                'active' => $validatedData['active'],
                'phone' => $validatedData['phone'],
                'role' => $validatedData['role'],
                'password' => Hash::make('Passw0rd123'),
            ]);

            return response()->json([
                'message' => 'Usuario creado exitosamente',
            ], 201);
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

    public function update($id, Request $request)
    {
        try {
            $user = User::findOrFail($id);
            $validatedData = $request->validate([
                'name' => 'sometimes|required|string|max:50',
                'phone' => [
                    'sometimes',
                    'string',
                    'regex:/^\+?[0-9]{7,15}$/',
                    'size:10'
                ],
                'active' => 'sometimes|required|boolean',
                'role' => 'sometimes|required|string|max:25',
                'password' => 'nullable|string|min:8',
            ]);

            if (isset($validatedData['name'])) {
                $user->name = $validatedData['name'];
            }

            if (isset($validatedData['phone'])) {
                $user->phone = $validatedData['phone'];
            }

            if (isset($validatedData['active'])) {
                $user->active = $validatedData['active'];
            }

            if (isset($validatedData['role'])) {
                $user->role = $validatedData['role'];
            }

            if (isset($validatedData['password'])) {
                $user->password = Hash::make($validatedData['password']);
            }

            $user->save();

            return response()->json([
                'message' => 'Usuario actualizado exitosamente',
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Errores de validación',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrió un error al actualizar el usuario',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'oldPassword' => 'current_password:api',
                'newPassword' => 'required|string|confirmed',
            ]);
            $user = auth()->user();
            $user->password = Hash::make($validatedData['newPassword']);
            $user->save();

            return response()->json([
                'message' => 'Contraseña actualizada exitosamente',
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Errores de validación.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrió un error al actualizar la contraseña.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json([
                'message' => 'Usuario eliminado exitosamente',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Usuario no encontrado',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrió un error al actualizar la contraseña.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
