google.charts.load('current', {'packages': ['corechart']});
async function drawChart(user) {
    const response = await fetch('../api/api_get_statistics.php?user='+user)
    console.log(response)
    const items = await response.json();
    console.log(items)
    var data = google.visualization.arrayToDataTable(items);

    var options = {
        title: 'Sales statistics',
        curveType: 'function',
        vAxis: {
            viewWindow: {
                min: 0
            },
            format: '0',
        },
        series: {
            0: { color: 'rgb(227,192,135)' },
            1: { color: '#5a1410' }
        },
    };

    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

    chart.draw(data, options);
}