<div class="h-full">
    <canvas id="accuracy-chart" class="w-full h-full"></canvas>
</div>
<script>
    const data = {
        labels: {!! $graph_data->row !!},
        datasets: [{
            label: '{{t('Distance error')}} - km',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            data: {!! $graph_data->distance !!},
            yAxisID: 'y',
            cubicInterpolationMode: 'monotone',
            tension: 0.4
        },
        {
            label: '{{t('Correct country')}} - %',
            backgroundColor: 'rgb(86,171,252)',
            borderColor: 'rgb(86,171,252)',
            data: {!! $graph_data->cc !!},
            yAxisID: 'y1',
            cubicInterpolationMode: 'monotone',
            tension: 0.4
        }
        ]
    };
    const chart = new Chart('accuracy-chart', {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            elements: {
                point:{
                    radius: 0
                }
            },
            interaction: {
                mode: 'index',
                intersect: false,
            },
            stacked: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Moving average over the last 500 rounds played.'
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Distance - km'
                    },
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Correct Country - %'
                    },
                    min: 0,
                    // grid line settings
                    grid: {
                        drawOnChartArea: false, // only want the grid lines for one axis to show up
                    },
                },
                x: {
                    title: {
                        display: true,
                        text: 'Round #'
                    },
                },
            }
        },
    })
</script>