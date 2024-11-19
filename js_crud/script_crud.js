document.addEventListener("DOMContentLoaded", function() {
    var toggleSidebarBtn = document.getElementById('toggleSidebar');
    var sidebar = document.querySelector('.sidebar');
    var content = document.querySelector('.content');

    toggleSidebarBtn.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
    });

    content.addEventListener('click', function() {
        if (!sidebar.classList.contains('collapsed')) {
            sidebar.classList.add('collapsed');
        }
    });

    sidebar.classList.add('collapsed');
});

google.charts.load('current', {'packages':['gauge']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Label', 'Value'],
        ['Production', [production_percentage]],
    ]);

    var options = {
        redFrom: 90, redTo: 100,
        yellowFrom:75, yellowTo: 90,
        minorTicks: 5
    };

    var chart = new google.visualization.Gauge(document.getElementById('productionGauge'));

    chart.draw(data, options);
}

window.onscroll = function() {myFunction()};

var header = document.getElementById("myHeader");
var sidebar2 = document.querySelector(".sidebar");
var sticky = header.offsetTop;

function myFunction() {
    if (window.pageYOffset > sticky) {
        header.classList.add("sticky");
        sidebar.classList.add("sticky-sidebar");
    } else {
        header.classList.remove("sticky");
        sidebar.classList.remove("sticky-sidebar");
    }
}
