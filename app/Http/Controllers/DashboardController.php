<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Events\DataUpdated;

class DashboardController extends Controller
{
    public function index(Request $request)
{
    $apiKey = 'cb128df4ff5d753b3473f62fb5f1dc1c'; // Substitua pelo sua chave de acesso à API weatherstack
    $city = $request->input('city');

    // Verifique se a cidade foi fornecida
    if (!$city) {
        return view('dashboard', ['error' => 'Por favor, insira uma cidade.']);
    }
    
    // Defina a localidade apenas como a cidade para a consulta à API
    $location = $city;
    
    // Faça uma requisição à API de clima atual
    $apiUrl = "http://api.weatherstack.com/current?access_key={$apiKey}&query={$location}";

    $response = Http::get($apiUrl);
    $data = $response->json();

    // Verifique se a requisição foi bem-sucedida e se os dados estão disponíveis
    if ($response->successful() && isset($data['current'])) {
        // Extraia os dados relevantes do response
        $weatherData = [
            'country' => $data['location']['country'],
            'name' => $data['location']['name'],
            'temperature' => $data['current']['temperature'],
            'humidity' => $data['current']['humidity'],
            'wind_speed' => $data['current']['wind_speed'],
            'wind_degree' => $data['current']['wind_degree'],
            'weather_icons' => $data['current']['weather_icons'],
            'weather_description' => $data['current']['weather_descriptions']
        ];
        // Envie os dados para a view
        return view('dashboard', ['weatherData' => $weatherData]);
    } else {
        // Trate qualquer erro de requisição aqui
        return view('dashboard', ['error' => 'Erro ao obter dados meteorológicos.']);
    }
}
}
