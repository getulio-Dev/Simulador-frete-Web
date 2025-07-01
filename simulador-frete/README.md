# Simulador de Frete

Este é um projeto Laravel para simulação de frete rodoviário, onde o usuário informa origem, destino, peso, tipo de caminhão e quantidade de eixos, e o sistema calcula o valor do frete, mostra um resumo e exibe a rota no mapa.

## Objetivo
Permitir que transportadoras ou clientes simulem o valor do frete de cargas, visualizando a rota e o caminhão correspondente ao número de eixos.

---

## Requisitos
- PHP >= 8.0
- Composer
- Laravel >= 9
- Chave de API do [OpenRouteService](https://openrouteservice.org/)

---

## Instalação

1. **Clone o repositório:**
   ```sh
   git clone <url-do-repositorio>
   cd simulador-frete
   ```
2. **Instale as dependências:**
   ```sh
   composer install
   ```
3. **Copie o arquivo de ambiente:**
   ```sh
   cp .env.example .env
   ```
4. **Gere a chave da aplicação:**
   ```sh
   php artisan key:generate
   ```
5. **Configure a chave da API do OpenRouteService**
   - No arquivo `.env`, adicione:
     ```
     ORS_KEY=sua_chave_aqui
     ```
   - E em `config/services.php`:
     ```php
     'openrouteservice' => [
         'key' => env('ORS_KEY'),
     ],
     ```
6. **Coloque as imagens dos caminhões**
   - Em `public/imagens/`, adicione arquivos como:
     - `2eixos.jpg`, `3eixos.jpg`, ..., `9eixos.jpg`

---

## Uso

1. **Inicie o servidor:**
   ```sh
   php artisan serve
   ```
2. **Acesse no navegador:**
   [http://127.0.0.1:8000/simulador](http://127.0.0.1:8000/simulador)
3. **Preencha o formulário:**
   - Origem e destino (cidade/estado)
   - Peso da carga
   - Tipo de caminhão
   - Quantidade de eixos
   - A distância é calculada automaticamente
4. **Clique em "Calcular Frete"**
   - Veja o resumo do frete, valor e imagem do caminhão
   - Veja a rota no mapa

---

## Estrutura de Pastas

- `app/Http/Controllers/FreteController.php` — Lógica do cálculo e exibição do frete
- `resources/views/simulador.blade.php` — Formulário, resumo e mapa
- `public/imagens/` — Imagens dos caminhões por eixo
- `routes/web.php` — Rotas do sistema

---

## Dicas para manutenção
- Para adicionar novos tipos de caminhão, basta adicionar novas imagens em `public/imagens/` seguindo o padrão `<eixos>eixos.jpg`.
- Para mudar a lógica do cálculo do frete, edite o método `calcular` em `FreteController.php`.
- Para trocar o visual, edite o CSS no próprio Blade ou adicione arquivos em `public/css/`.
- Para usar outra API de mapas, altere o JS no final do Blade.

---

## Licença
Este projeto é livre para uso acadêmico e pessoal.
