(function($) {
    "use strict";

    let salesChart = document.getElementById('sales-chart');
    if (salesChart) {
        window.Chart && new Chart(salesChart, {
            type: 'line',
            data: {
                labels: chartsConfig.sales.labels,
                datasets: [{
                    label: chartsConfig.sales.title,
                    data: chartsConfig.sales.data,
                    fill: false,
                    pointBackgroundColor: config.colors.primary_color,
                    borderWidth: 2,
                    borderColor: config.colors.primary_color,
                    lineTension: .10,
                    rtl: config.direction == "rtl" ? true : false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    },
                },
                scales: {
                    x: {
                        grid: {
                            display: true,
                            drawBorder: true
                        },
                        ticks: {
                            autoSkip: true,
                            maxTicksLimit: 17
                        }
                    },
                    y: {
                        suggestedMax: chartsConfig.sales.max,
                        grid: {
                            drawBorder: true
                        }
                    }
                }
            }
        });
    }

    let viewsChart = document.getElementById('views-chart');
    if (viewsChart) {
        window.Chart && new Chart(viewsChart, {
            type: 'bar',
            data: {
                labels: chartsConfig.views.labels,
                datasets: [{
                    label: chartsConfig.views.title,
                    data: chartsConfig.views.data,
                    fill: false,
                    backgroundColor: config.colors.primary_color,
                    borderColor: config.colors.primary_color,
                    borderWidth: 2,
                    lineTension: .10,
                    rtl: config.direction == "rtl" ? true : false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    },
                },
                scales: {
                    x: {
                        grid: {
                            display: true,
                            drawBorder: true
                        },
                        ticks: {
                            autoSkip: true,
                            maxTicksLimit: 17
                        }
                    },
                    y: {
                        suggestedMax: chartsConfig.views.max,
                        grid: {
                            drawBorder: true
                        }
                    }
                }
            }
        });
    }

    let countriesChart = document.getElementById('countries-chart');
    if (countriesChart) {
        google.charts.load('current', { 'packages': ['geochart'] });
        google.charts.setOnLoadCallback(drawRegionsMap);

        function drawRegionsMap() {
            var data = google.visualization.arrayToDataTable(chartsConfig.geo.data);
            var options = {
                colorAxis: {
                    colors: [
                        config.colors.primary_color,
                    ]
                },
            };
            var geoChart = new google.visualization.GeoChart(countriesChart);
            geoChart.draw(data, options);
        }
    }
})(jQuery);