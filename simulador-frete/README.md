# Simulador de Frete

Este é um projeto Laravel para simulação de frete rodoviário, onde o usuário informa origem, destino, peso, tipo de caminhão e quantidade de eixos, e o sistema calcula o valor do frete, mostra um resumo e exibe a rota no mapa.

## Objetivo
Permitir que transportadoras ou clientes simulem o valor do frete de cargas, visualizando a rota e o caminhão correspondente ao número de eixos.

---


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



