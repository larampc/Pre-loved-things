google.charts.load('current', {'packages': ['corechart']})
const width = document.getElementsByClassName("feedback")[0].width
google.charts.setOnLoadCallback(drawChart)

async function drawChart() {
    const user = document.querySelector(".chart-user").value
    const response = await fetch('../api/api_get_statistics.php?user='+user)
    const items = await response.json()
    const data = google.visualization.arrayToDataTable(items)

    const options = {
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
        width: width,
        responsive: true,
        chartArea:{left:"5%", width: "80%"}
    }

    const chart = new google.visualization.LineChart(document.getElementById('curve_chart'))

    chart.draw(data, options)
    window.onresize = function() {
        document.getElementById('curve_chart').innerHTML = ''
        const chart = new google.visualization.LineChart(document.getElementById('curve_chart'))
        chart.draw(data, options)
    }
}