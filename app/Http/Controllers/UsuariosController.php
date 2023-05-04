<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class UsuariosController extends Controller
{
    public function index(Request $request)
    {
        $client = new Client([
            'base_uri' => 'https://gorest.co.in/public-api/v2/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('GOREST_API_TOKEN'),
                'Accept' => 'application/json',
            ],
        ]);

        $query = $request->query();
        if (count($query) > 0) {
            $response = $client->get('users', [
                'query' => $query,
            ]);
        } else {
            $response = $client->get('users');
        }

        $usuarios = json_decode($response->getBody(), true);

        return response()->json([
            'data' => $usuarios['data'],
            'meta' => [
                'pagination' => $usuarios['meta']['pagination'],
            ],
        ]);
    }
}

