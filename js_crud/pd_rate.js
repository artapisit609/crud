google.charts.setOnLoadCallback(drawChart);
google.charts.load('current', {'packages':['corechart']});

function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Label', 'Value'],
        ['rate', <?php echo (($row_all['total'] - 5) / (20 * 2)) * 100; ?>],
    ]);

    var options = {
        title: 'Production rate',
        legend: { position: 'none' },
        bars: 'vertical',
        colors: ['#FF6347'],
        hAxis: {
            title: '',
            minValue: 0,
            maxValue: 100
        },
        vAxis: {
            title: ''
        }
    };

    var chart = new google.visualization.BarChart(document.getElementById('productionGauge'));

    chart.draw(data, options);
}