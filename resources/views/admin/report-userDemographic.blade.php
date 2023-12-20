@extends('admin/master')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">User Demographic</h1>
            <canvas id="pieChart" style="height:230px"></canvas>
        </div>
    </div>
</div>


<script>
    $(function () {
        /* ChartJS
        * -------
        * Data and config for chartjs
        */
        'use strict';
        
        var doughnutPieData = {
            datasets: [{
            data: {{ json_encode($dataArray) }},
            backgroundColor: [
                'rgba(255, 159, 64, 0.5)', // Color for 'not filled'
                'rgba(255, 99, 132, 0.5)',  // Pink for 'female'
                'rgba(54, 162, 235, 0.5)'   // Blue for 'male'
            ],
            borderColor: [
                'rgba(255, 159, 64, 1)',    // Color for 'not filled'
                'rgba(255, 99, 132, 1)',    // Pink for 'female'
                'rgba(54, 162, 235, 1)'     // Blue for 'male'
            ],
            }],

            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: {!! $labelArray !!}
            
        };
        var doughnutPieOptions = {
            responsive: true,
            animation: {
            animateScale: true,
            animateRotate: true
            }
        };

        if ($("#pieChart").length) {
            var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
            var pieChart = new Chart(pieChartCanvas, {
            type: 'pie',
            data: doughnutPieData,
            options: doughnutPieOptions
            });
        }

    });
</script>

@endsection