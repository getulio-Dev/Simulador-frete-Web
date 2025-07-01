<!DOCTYPE html>
<html>
<head>
    <title>Resultado do Frete</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f9f9f9;
        }

        h1 {
            color: #2c3e50;
        }

        p {
            font-size: 1.2em;
            color: #34495e;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background-color: #3498db;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
        }

        a:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <h1>Resultado do Frete</h1>

    <p><strong>Valor calculado:</strong> R$ {{ number_format($valorFrete, 2, ',', '.') }}</p>

    @if(isset($eixos) && $eixos >= 2 && $eixos <= 9)
        <img src="{{ asset('imagens/' . $eixos . 'eixos.png') }}" alt="Imagem do eixo" style="width:200px;">
    @endif

    <a href="{{ url('/simulador') }}">Voltar</a>
</body>
</html>
