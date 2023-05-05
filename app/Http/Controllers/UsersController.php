<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Http\Requests\StoreUserRequest;

class UsersController extends Controller
{
    // Metodo obtener usuario
    public function getUsers(Request $request)
{
    try{
    $client = new \GuzzleHttp\Client([
        'base_uri' => config('services.gorest.base_uri'),
        'headers' => [
            'Authorization' => 'Bearer '.config('services.gorest.token'),
        ],
    ]);

    $queryParams = $request->query();
    $response = $client->get('public-api/users', [
        'query' => $queryParams,
    ]);
    $users = json_decode($response->getBody())->data;

    $formattedUsers = array_map(function ($user) {
        return [
            'id' => $user->id,
            'nombre' => $user->name,
            'email' => $user->email,
            'genero' => $user->gender,
            'activo' => $user->status === 'active',
        ];
    }, $users);

    return response()->json($formattedUsers);
    //manejo de errores
      } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error al editar usuario',
            'error' => $e->getMessage()
        ], 500);
    }
}



public function postUsers(Request $request)
{
    try{
    $client = new Client([
        'base_uri' => 'https://gorest.co.in/public-api/',
        'headers' => [
            'Authorization' => 'Bearer ' . env('GOREST_API_TOKEN'),
            'Accept' => 'application/json',
        ],
    ]);

    $response = $client->post('users', [
        'json' => $request->all(),
    ]);

    $usuario = json_decode($response->getBody(), true);

    // Obtener información del usuario recién creado
    $response = $client->get('users/' . $usuario['data']['id']);
    $usuario = json_decode($response->getBody(), true)['data'];

    // Formatear información en el formato deseado
    $formatted_usuario = [
        'nombre' => $usuario['name'],
        'email' => $usuario['email'],
        'genero' => $usuario['gender'],
        'activo' => ($usuario['status'] == 'active'),
    ];

    return response()->json([
        'message' => 'Usuario creado correctamente',
        'data' => $formatted_usuario,
    ], 201);
    //manejo de errores
        } catch (\Exception $e) {
         return response()->json([
        'message' => 'Error al editar usuario',
        'error' => $e->getMessage()
    ], 500);
}
}
//metodo PUT usuarios, este metodo recive como parametro el id del usuario en la ruta /usuarios/{id del usuario} 
public function putUsers(Request $request, $id)
{
    try{
    $client = new Client([
        'base_uri' => 'https://gorest.co.in/public-api/',
        'headers' => [
            'Authorization' => 'Bearer ' . env('GOREST_API_TOKEN'),
            'Accept' => 'application/json',
        ],
    ]);

    $response = $client->put('users/' . $id, [
        'json' => $request->all(),
    ]);

    $usuarioActualizado = json_decode($response->getBody(), true);

    $usuario = [
        "nombre" => $usuarioActualizado['data']['name'],
        "email" => $usuarioActualizado['data']['email'],
        "genero" => $usuarioActualizado['data']['gender'],
        "activo" => ($usuarioActualizado['data']['status'] === 'active')
    ];

    return response()->json([
        'message' => 'Usuario editado correctamente',
        'data' => $usuario,
    ]);
    } //manejo de errores
        catch (\Exception $e) {
        return response()->json([
        'message' => 'Error al editar usuario',
        'error' => $e->getMessage()
        ], 500);
}
}
//metodo PATCH usuarios, este metodo recive el email del usuario como parametro ?id=id
//metodo devuelve el usuario mas no actualiza*
public function patchByEmail(Request $request)
{
    try {
        $email = $request->query('email');

        $client = new Client([
            'base_uri' => 'https://gorest.co.in/public-api/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('GOREST_API_TOKEN'),
                'Accept' => 'application/json',
            ],
        ]);

        $response = $client->get('users', [
            'query' => ['email' => $email],
        ]);

        $usuario = json_decode($response->getBody(), true)['data'][0];

        $data = [
            'name' => $request->input('nombre'),
            'email' => $request->input('email'),
            'gender' => $request->input('genero'),
            'status' => $request->input('activo') ? 'active' : 'inactive',
        ];

        $response = $client->patch('users/' . $usuario['id'], [
            'json' => $data,
        ]);

        $usuarioActualizado = [
            'nombre' => $usuario['name'],
            'email' => $usuario['email'],
            'genero' => $usuario['gender'],
            'activo' => $usuario['status'] == 'active',
        ];

        return response()->json([
            'message' => 'Usuario actualizado correctamente',
            'data' => $usuarioActualizado,
        ]);
    } catch (\Throwable $th) {
        // Manejo de errores
        return response()->json([
            'message' => 'Error al actualizar el usuario',
            'error' => $th->getMessage(),
        ], 500);
    }
}



}



