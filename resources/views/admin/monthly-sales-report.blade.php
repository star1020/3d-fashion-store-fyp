@extends('admin/master')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">Monthly Sales</h1>
            <canvas id="barChart" style="height:230px"></canvas>
        </div>
    </div>
</div>

<script>
    $(function () {
        'use strict';
        var salesData = @json($salesArray);
        var labels = Object.keys(salesData);  // Months
        var sales = Object.values(salesData);

        var chartData = {
            labels: labels,
            datasets: [{
            label: 'Sales (RM)',
            data: sales,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 0, 0, 0.2)',
                'rgba(0, 255, 0, 0.2)',
                'rgba(0, 0, 255, 0.2)',
                'rgba(128, 128, 0, 0.2)',
                'rgba(128, 0, 128, 0.2)',
                'rgba(0, 128, 128, 0.2)'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(255, 0, 0, 1)',
                'rgba(0, 255, 0, 1)',
                'rgba(0, 0, 255, 1)',
                'rgba(128, 128, 0, 1)',
                'rgba(128, 0, 128, 1)',
                'rgba(0, 128, 128, 1)'
            ],
            borderWidth: 1,
            fill: false
            }]
        };
       
        var options = {
            scales: {
            yAxes: [{
                ticks: {
                beginAtZero: true
                }
            }]
            },
            legend: {
            display: false
            },
            elements: {
            point: {
                radius: 0
            }
            }

        };

        if ($("#barChart").length) {
            var barChartCanvas = $("#barChart").get(0).getContext("2d");
            var barChart = new Chart(barChartCanvas, {
            type: 'bar',
            data: chartData,
            options: options
            });
        }
        
    });
</script>
@endsection