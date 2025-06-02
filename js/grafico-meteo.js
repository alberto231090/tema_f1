
document.addEventListener("DOMContentLoaded", async function () {
    document.querySelectorAll(".grafico-meteo").forEach(async function (container) {
        const circuitName = container.dataset.circuito;
        if (!circuitName) return;

        const circuitoToCountry = {
            "Sakhir": "Bahrain",
            "Jeddah": "Saudi Arabia",
            "Melbourne": "Australia",
            "Suzuka": "Japan",
            "Shanghai": "China",
            "Miami": "United States"
        };

        const countryName = circuitoToCountry[circuitName] || circuitName;

        const graphContainer = document.createElement("div");
        container.innerHTML = "";
        container.appendChild(graphContainer);
        graphContainer.innerHTML = `⏳ Caricamento temperatura storica per ${circuitName}...`;

        try {
            const sessionRes = await fetch(`https://api.openf1.org/v1/sessions?country_name=${encodeURIComponent(countryName)}&session_name=Race`);
            const sessions = await sessionRes.json();
            const sessionIds = sessions.map(s => s.session_key);

            const temperatureData = [];

            for (let sessionId of sessionIds) {
                const weatherRes = await fetch(`https://api.openf1.org/v1/weather?session_key=${sessionId}`);
                const weather = await weatherRes.json();

                if (weather.length > 0) {
                    const temps = weather.map(w => w.track_temperature).filter(t => t !== null);
                    if (temps.length > 0) {
                        const maxTemp = Math.max(...temps);
                        temperatureData.push({
                            session_key: sessionId,
                            date: new Date(weather[0].date).toISOString().split("T")[0],
                            max_track_temp: maxTemp
                        });
                    }
                }
            }

            if (temperatureData.length === 0) {
                graphContainer.innerHTML = "<p style='color: white;'>⚠️ Nessun dato disponibile per questo circuito.</p>";
                return;
            }

            temperatureData.sort((a, b) => new Date(a.date) - new Date(b.date));

            graphContainer.innerHTML = '<canvas height="120"></canvas>';
            const ctx = graphContainer.querySelector('canvas').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: temperatureData.map(d => d.date),
                    datasets: [{
                        label: `Max Track Temp (${circuitName})`,
                        data: temperatureData.map(d => d.max_track_temp),
                        borderColor: 'white',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.2
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            labels: {
                                color: 'white'
                            }
                        }
                    },
                    scales: {
                        y: {
                            ticks: { color: 'white' },
                            title: { display: true, text: "°C", color: 'white' },
                            beginAtZero: false
                        },
                        x: {
                            ticks: { color: 'white' },
                            title: { display: true, text: "Data", color: 'white' }
                        }
                    }
                }
            });
        } catch (err) {
            console.error("❌ Errore caricamento dati meteo:", err);
            graphContainer.innerHTML = "<p style='color: red;'>Errore nel caricamento dei dati meteo.</p>";
        }
    });
});
