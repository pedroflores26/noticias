// weather.js — API do Tempo (Open-Meteo, gratuita, sem chave)
document.addEventListener('DOMContentLoaded', function () {
    const widget = document.getElementById('weather-widget');
    if (!widget) return;

    // Ícones por código WMO
    const icones = {
        0: '☀️', 1: '🌤️', 2: '⛅', 3: '☁️',
        45: '🌫️', 48: '🌫️',
        51: '🌦️', 53: '🌦️', 55: '🌧️',
        61: '🌧️', 63: '🌧️', 65: '🌧️',
        71: '❄️', 73: '❄️', 75: '❄️',
        80: '🌦️', 81: '🌧️', 82: '⛈️',
        95: '⛈️', 96: '⛈️', 99: '⛈️',
    };

    const descricoes = {
        0: 'Céu limpo', 1: 'Poucas nuvens', 2: 'Parcialmente nublado', 3: 'Nublado',
        45: 'Neblina', 48: 'Neblina gelada',
        51: 'Garoa leve', 53: 'Garoa', 55: 'Garoa forte',
        61: 'Chuva leve', 63: 'Chuva', 65: 'Chuva forte',
        71: 'Neve leve', 73: 'Neve', 75: 'Neve forte',
        80: 'Pancadas de chuva', 81: 'Chuva moderada', 82: 'Chuva intensa',
        95: 'Tempestade', 96: 'Tempestade c/ granizo', 99: 'Tempestade forte',
    };

    // Pega localização do usuário ou usa São Paulo como padrão
    function buscarTempo(lat, lon, cidade) {
        const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true&hourly=relativehumidity_2m&timezone=America%2FSao_Paulo`;

        fetch(url)
            .then(r => r.json())
            .then(data => {
                const cw   = data.current_weather;
                const temp = Math.round(cw.temperature);
                const icon = icones[cw.weathercode] || '🌡️';
                const desc = descricoes[cw.weathercode] || '';

                widget.innerHTML = `
                    <span class="w-icon">${icon}</span>
                    <span><strong>${temp}°C</strong> · ${desc}</span>
                    <span style="opacity:.5">·</span>
                    <span>${cidade}</span>
                `;
            })
            .catch(() => {
                widget.innerHTML = '<span>🌡️</span><span>Tempo indisponível</span>';
            });
    }

    widget.innerHTML = '<span style="opacity:.5;font-size:.7rem">Carregando tempo...</span>';

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            pos => buscarTempo(pos.coords.latitude, pos.coords.longitude, 'Sua cidade'),
            ()  => buscarTempo(-23.5505, -46.6333, 'São Paulo') // fallback
        );
    } else {
        buscarTempo(-23.5505, -46.6333, 'São Paulo');
    }
});
