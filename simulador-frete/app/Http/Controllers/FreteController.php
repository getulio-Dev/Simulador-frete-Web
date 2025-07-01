<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FreteController extends Controller
{
    public function index()
    {
        return view('simulador');
    }

    public function calcular(Request $request)
    {
        $resumo = [
            'origem' => $request->input('origem'),
            'destino' => $request->input('destino'),
            'peso' => $request->input('peso'),
            'tipo_caminhao' => $request->input('tipo_caminhao'),
            'eixos' => $request->input('eixos'),
            'distancia' => $request->input('distancia'),
        ];

        // Exemplo de cálculo do frete (ajuste conforme sua lógica)
        $frete = ($resumo['distancia'] * 2) + ($resumo['peso'] * 0.1) + ($resumo['eixos'] * 50);

        return view('simulador', compact('resumo', 'frete'));
    }
}
