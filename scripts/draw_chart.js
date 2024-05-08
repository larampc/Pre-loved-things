google.charts.load('current', {'packages': ['corechart']});

async function drawChart(user) {
    const response = await fetch('../api/api_get_statistics.php?user='+user)
    const items = await response.json();
    let data = google.visualization.arrayToDataTable(items);

    let options = {
        title: 'Sales statistics in '+ new Date().getFullYear() ,
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

    let chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

    chart.draw(data, options);
}