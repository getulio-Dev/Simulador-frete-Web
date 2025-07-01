<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Simulador de Frete</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap (dark theme) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <style>
        body {
            background-color: #111;
            color: #fff;
        }
        label {
            color: #fff;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .leaflet-container {
            height: 300px;
            border-radius: 10px;
            margin-top: 20px;
        }
        .resumo {
            margin: 30px auto 0 auto;
            display: block;
        }
        .resumo-texto {
            flex: 1;
        }
        .resumo-imagem {
            max-width: 300px;
            border-radius: 10px;
        }
        #map {
            height: 300px;
            border-radius: 10px;
            margin-top: 20px;
            width: 100%;
        }
    </style>
</head>
<body class="p-4">

<div class="container">
    <!-- Formulário centralizado -->
    <div class="d-flex justify-content-center">
        <div class="bg-dark rounded p-4 mb-4" style="max-width: 400px; width: 100%;">
            <h2 class="text-center">Simulador de Frete</h2>
            <form method="POST" action="{{ route('simular') }}">
                @csrf
                <div class="mb-3">
                    <label>Origem (Cidade/Estado):</label>
                    <input type="text" name="origem" id="origem" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Destino (Cidade/Estado):</label>
                    <input type="text" name="destino" id="destino" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Peso (kg):</label>
                    <input type="number" name="peso" class="form-control" step="0.01" required>
                </div>

                <div class="mb-3">
                    <label>Tipo de Caminhão:</label>
                    <select name="tipo_caminhao" id="tipo_caminhao" class="form-select" required>
                        <option value="leve">Leve</option>
                        <option value="medio">Médio</option>
                        <option value="pesado">Pesado</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Quantidade de Eixos:</label>
                    <input type="number" name="eixos" id="eixos" min="2" max="9" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Distância (km):</label>
                    <input type="number" step="0.1" name="distancia" id="distancia" class="form-control" required readonly>
                </div>

                <button type="submit" class="btn btn-success w-100">Calcular Frete</button>
            </form>
        </div>
    </div>

    <!-- Resumo do frete centralizado -->
    @if(isset($frete) && isset($resumo))
    <div class="d-flex justify-content-center">
        <div class="bg-dark rounded resumo p-3" style="max-width: 400px; width: 100%;">
            <div class="resumo-texto">
                <h4>Resumo do Frete</h4>
                <p><strong>Origem:</strong> {{ $resumo['origem'] }}</p>
                <p><strong>Destino:</strong> {{ $resumo['destino'] }}</p>
                <p><strong>Peso:</strong> {{ $resumo['peso'] }} kg</p>
                <p><strong>Distância:</strong> {{ $resumo['distancia'] }} km</p>
                <p><strong>Tipo de Caminhão:</strong> {{ ucfirst($resumo['tipo_caminhao']) }}</p>
                <p><strong>Quantidade de Eixos:</strong> {{ $resumo['eixos'] }}</p>
                <h5 class="text-success">Valor do Frete: R$ {{ $frete }}</h5>
            </div>
            <div class="text-center mt-3">
                @if(isset($resumo['eixos']))
                    <img src="{{ asset('imagens/' . intval($resumo['eixos']) . 'eixos.jpg') }}" alt="Caminhão com {{ $resumo['eixos'] }} eixos" class="resumo-imagem" style="max-width: 100%;">
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Mapa centralizado -->
    <div class="d-flex justify-content-center">
        <div style="max-width: 400px; width: 100%;">
            <!-- <h5 class="text-center mt-4">Visualização do Caminhão</h5> --> 
            <div id="map"></div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    const map = L.map('map').setView([-14.2350, -51.9253], 4);
    const orsApiKey = "{{ config('services.openrouteservice.key') }}";

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    async function getCoords(city) {
        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(city)}&limit=1`;
        const res = await fetch(url);
        const data = await res.json();
        if (data.length > 0) {
            return {
                lat: parseFloat(data[0].lat),
                lon: parseFloat(data[0].lon)
            };
        }
        return null;
    }

    async function calcularDistancia() {
        const origem = document.getElementById('origem').value;
        const destino = document.getElementById('destino').value;

        if (!origem || !destino) return;

        const coordOrigem = await getCoords(origem);
        const coordDestino = await getCoords(destino);

        if (!coordOrigem || !coordDestino) return;

        const body = {
            coordinates: [
                [coordOrigem.lon, coordOrigem.lat],
                [coordDestino.lon, coordDestino.lat]
            ]
        };

        const response = await fetch('https://api.openrouteservice.org/v2/directions/driving-car/geojson', {
            method: 'POST',
            headers: {
                'Authorization': orsApiKey,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(body)
        });

        const data = await response.json();
        const distancia_metros = data.features[0].properties.summary.distance;
        const distancia_km = (distancia_metros / 1000).toFixed(2);

        document.getElementById('distancia').value = distancia_km;

        const coords = data.features[0].geometry.coordinates.map(c => [c[1], c[0]]);
        L.polyline(coords, {
            color: 'lime',
            weight: 6,
            opacity: 0.9,
            dashArray: '10,5'
        }).addTo(map);
        map.fitBounds(coords);
    }

    document.getElementById('destino').addEventListener('blur', calcularDistancia);
</script>

<script>
function atualizarImagemCaminhao() {
    const tipo = document.getElementById('tipo_caminhao')?.value;
    const eixos = parseInt(document.getElementById('eixos')?.value);
    const imgEl = document.getElementById('imagem_caminhao');

    const mapa = {
        'leve': {
            2: 'TOCO.jpg',
            3: 'TRUCK.jpg'
        },
        'medio': {
            4: 'CAVALOS.jpg',
            5: 'CAVALOS2.jpg',
            6: 'CAVALOSIMPLES.jpg'
        },
        'pesado': {
            7: 'BITRUCK.jpg',
            8: 'VUCHR.jpg',
            9: '3_4.jpg'
        }
    };

    const nomeImagem = mapa[tipo]?.[eixos];

    if (nomeImagem) {
        const caminho = `/imagens/${nomeImagem}`;
        fetch(caminho, { method: 'HEAD' }).then(res => {
            if (res.ok) {
                imgEl.src = caminho;
                imgEl.style.display = 'block';
            } else {
                imgEl.style.display = 'none';
            }
        });
    } else {
        imgEl.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('tipo_caminhao')?.addEventListener('change', atualizarImagemCaminhao);
    document.getElementById('eixos')?.addEventListener('change', atualizarImagemCaminhao);

    // Atualiza a imagem automaticamente após cálculo do frete
    atualizarImagemCaminhao();
});
</script>

<script>
function trocarImagemEixo() {
    var eixos = document.getElementById('eixos').value;
    var img = document.getElementById('imagem-eixo');
    if (eixos >= 2 && eixos <= 9) {
        img.src = '/imagens/' + eixos + 'eixos.png';
        img.style.display = 'block';
    } else {
        img.style.display = 'none';
    }
}
</script>

</body>
</html>
