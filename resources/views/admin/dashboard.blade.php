@extends('admin/master')
@section('content')
    <div class="page-header">
        <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-home"></i>
        </span> Dashboard
        </h3>
        <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
            <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
            </li>
        </ul>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-danger card-img-holder text-white">
            <div class="card-body">
            <img src="{{asset('admin/assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
            <h4 class="font-weight-normal mb-3">Weekly Sales <i class="mdi mdi-chart-line mdi-24px float-right"></i>
            </h4>
            <h2 class="mb-5">RM {{$weeklySalesTotal}}</h2>
            <h6 class="card-text">{{$weeklySalesPercentageChange}}</h6>
            </div>
        </div>
        </div>
        <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-info card-img-holder text-white">
            <div class="card-body">
            <img src="{{asset('admin/assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
            <h4 class="font-weight-normal mb-3">Weekly Comments <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
            </h4>
            <h2 class="mb-5">{{$weeklyReviewCount}}</h2>
            <h6 class="card-text">{{$weeklyReviewPercentageChange}}</h6>
            </div>
        </div>
        </div>
        <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-success card-img-holder text-white">
            <div class="card-body">
            <img src="{{asset('admin/assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
            <h4 class="font-weight-normal mb-3">Weekly Visitors <i class="mdi mdi-diamond mdi-24px float-right"></i>
            </h4>
            <h2 class="mb-5">{{$weeklyVisitorCount}}</h2>
            <h6 class="card-text">{{$weeklyVisitorPercentageChange}}</h6>
            </div>
        </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
            <div class="clearfix">
                <h4 class="card-title float-left">Weekly Visit, Sales, and Comment Statistics</h4>
                <div id="visit-sale-chart-legend" class="rounded-legend legend-horizontal legend-top-right float-right"></div>
            </div>
            <canvas id="visit-sale-chart" class="mt-4"></canvas>
            </div>
        </div>
        </div>
        <div class="col-md-5 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
            <h4 class="card-title">Sales Analysis of Popular Product</h4>
            <canvas id="traffic-chart"></canvas>
            <div id="traffic-chart-legend" class="rounded-legend legend-vertical legend-bottom-left pt-4"></div>
            </div>
        </div>
        </div>
    </div>
    
    <!-- Custom js for this page -->
    {{-- <script src="{{asset('admin/assets/js/dashboard.js')}}"></script> --}}

    <script>
    (function($) {
        'use strict';
        $(function() {

            Chart.defaults.global.legend.labels.usePointStyle = true;
            
            if ($("#visit-sale-chart").length) {
                Chart.defaults.global.legend.labels.usePointStyle = true;
                var ctx = document.getElementById('visit-sale-chart').getContext("2d");

                var gradientStrokeViolet = ctx.createLinearGradient(0, 0, 0, 181);
                gradientStrokeViolet.addColorStop(0, 'rgba(218, 140, 255, 1)');
                gradientStrokeViolet.addColorStop(1, 'rgba(154, 85, 255, 1)');
                var gradientLegendViolet = 'linear-gradient(to right, rgba(218, 140, 255, 1), rgba(154, 85, 255, 1))';
                
                var gradientStrokeBlue = ctx.createLinearGradient(0, 0, 0, 360);
                gradientStrokeBlue.addColorStop(0, 'rgba(54, 215, 232, 1)');
                gradientStrokeBlue.addColorStop(1, 'rgba(177, 148, 250, 1)');
                var gradientLegendBlue = 'linear-gradient(to right, rgba(54, 215, 232, 1), rgba(177, 148, 250, 1))';

                var gradientStrokeRed = ctx.createLinearGradient(0, 0, 0, 300);
                gradientStrokeRed.addColorStop(0, 'rgba(255, 191, 150, 1)');
                gradientStrokeRed.addColorStop(1, 'rgba(254, 112, 150, 1)');
                var gradientLegendRed = 'linear-gradient(to right, rgba(255, 191, 150, 1), rgba(254, 112, 150, 1))';

                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['MON', 'TUE', 'WED', 'THUR', 'FRI', 'SAT', 'SUN'],
                        datasets: [
                        {
                            label: "Visit",
                            borderColor: gradientStrokeViolet,
                            backgroundColor: gradientStrokeViolet,
                            hoverBackgroundColor: gradientStrokeViolet,
                            legendColor: gradientLegendViolet,
                            pointRadius: 0,
                            fill: false,
                            borderWidth: 1,
                            fill: 'origin',
                            categoryPercentage: 0.5,
                            barPercentage: 0.5,
                            data: JSON.parse('{{ $visitChartData }}')
                        },
                        {
                            label: "Sales",
                            borderColor: gradientStrokeRed,
                            backgroundColor: gradientStrokeRed,
                            hoverBackgroundColor: gradientStrokeRed,
                            legendColor: gradientLegendRed,
                            pointRadius: 0,
                            fill: false,
                            borderWidth: 1,
                            fill: 'origin',
                            categoryPercentage: 0.5,
                            barPercentage: 0.5,
                            data: JSON.parse('{{ $salesChartData }}')
                        },
                        {
                            label: "Comment",
                            borderColor: gradientStrokeBlue,
                            backgroundColor: gradientStrokeBlue,
                            hoverBackgroundColor: gradientStrokeBlue,
                            legendColor: gradientLegendBlue,
                            pointRadius: 0,
                            fill: false,
                            borderWidth: 1,
                            fill: 'origin',
                            categoryPercentage: 0.5,
                            barPercentage: 0.5,
                            data: JSON.parse('{{ $reviewChartData }}')
                        },
                    ]
                    },
                    options: {
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                                    var salesIncome = @json($salesIncome);
                                    var value = tooltipItem.yLabel;
                                    if (datasetLabel == "Sales") {
                                        value += ' (RM' + salesIncome[tooltipItem.index] + ')';
                                    }
                                    return datasetLabel + ': ' + value;
                                }
                            }
                        },
                        responsive: true,
                        legend: false,
                        legendCallback: function(chart) {
                            var text = []; 
                            text.push('<ul>'); 
                            for (var i = 0; i < chart.data.datasets.length; i++) { 
                                text.push('<li><span class="legend-dots" style="background:' + 
                                        chart.data.datasets[i].legendColor + 
                                        '"></span>'); 
                                if (chart.data.datasets[i].label) { 
                                    text.push(chart.data.datasets[i].label); 
                                } 
                                text.push('</li>'); 
                            } 
                            text.push('</ul>'); 
                            return text.join('');
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    display: false,
                                    min: 0,
                                    stepSize: 5,
                                    max: 30
                                },
                                gridLines: {
                                    drawBorder: false,
                                    color: 'rgba(235,237,242,1)',
                                    zeroLineColor: 'rgba(235,237,242,1)'
                                }
                            }],
                            xAxes: [{
                                gridLines: {
                                    display:false,
                                    drawBorder: false,
                                    color: 'rgba(0,0,0,1)',
                                    zeroLineColor: 'rgba(235,237,242,1)'
                                },
                                ticks: {
                                    padding: 20,
                                    fontColor: "#9c9fa6",
                                    autoSkip: true,
                                },
                            }]
                        }
                    },
                    elements: {
                        point: {
                        radius: 0
                        }
                    }
                })
                $("#visit-sale-chart-legend").html(myChart.generateLegend());
            }
            
            if ($("#traffic-chart").length) {
                var gradientStrokeBlue = ctx.createLinearGradient(0, 0, 0, 181);
                gradientStrokeBlue.addColorStop(0, 'rgba(54, 215, 232, 1)');
                gradientStrokeBlue.addColorStop(1, 'rgba(177, 148, 250, 1)');
                var gradientLegendBlue = 'linear-gradient(to right, rgba(54, 215, 232, 1), rgba(177, 148, 250, 1))';

                var gradientStrokeRed = ctx.createLinearGradient(0, 0, 0, 50);
                gradientStrokeRed.addColorStop(0, 'rgba(255, 191, 150, 1)');
                gradientStrokeRed.addColorStop(1, 'rgba(254, 112, 150, 1)');
                var gradientLegendRed = 'linear-gradient(to right, rgba(255, 191, 150, 1), rgba(254, 112, 150, 1))';

                var gradientStrokeGreen = ctx.createLinearGradient(0, 0, 0, 300);
                gradientStrokeGreen.addColorStop(0, 'rgba(6, 185, 157, 1)');
                gradientStrokeGreen.addColorStop(1, 'rgba(132, 217, 210, 1)');
                var gradientLegendGreen = 'linear-gradient(to right, rgba(6, 185, 157, 1), rgba(132, 217, 210, 1))';      

                var gradientStrokeYellow = ctx.createLinearGradient(0, 0, 0, 300);
                gradientStrokeYellow.addColorStop(0, 'rgba(255, 211, 64, 1)');
                gradientStrokeYellow.addColorStop(1, 'rgba(255, 162, 105, 1)');
                var gradientLegendYellow = 'linear-gradient(to right, rgba(255, 211, 64, 1), rgba(255, 162, 105, 1))';   

                var gradientStrokePurple = ctx.createLinearGradient(0, 0, 0, 300);
                gradientStrokePurple.addColorStop(0, 'rgba(207, 181, 255, 1)');
                gradientStrokePurple.addColorStop(1, 'rgba(147, 109, 255, 1)');
                var gradientLegendPurple = 'linear-gradient(to right, rgba(207, 181, 255, 1), rgba(147, 109, 255, 1))';   

                var gradientStrokePink = ctx.createLinearGradient(0, 0, 0, 300);
                gradientStrokePink.addColorStop(0, 'rgba(255, 147, 224, 1)');
                gradientStrokePink.addColorStop(1, 'rgba(196, 113, 245, 1)');
                var gradientLegendPink = 'linear-gradient(to right, rgba(255, 147, 224, 1), rgba(196, 113, 245, 1))'; 
                
                var gradientStrokeOrange = ctx.createLinearGradient(0, 0, 0, 300);
                gradientStrokeOrange.addColorStop(0, 'rgba(255, 187, 119, 1)');
                gradientStrokeOrange.addColorStop(1, 'rgba(255, 140, 104, 1)');
                var gradientLegendOrange = 'linear-gradient(to right, rgba(255, 187, 119, 1), rgba(255, 140, 104, 1))';

                var gradientStrokeGrey = ctx.createLinearGradient(0, 0, 0, 300);
                gradientStrokeGrey.addColorStop(0, 'rgba(196, 196, 196, 1)');
                gradientStrokeGrey.addColorStop(1, 'rgba(130, 130, 130, 1)');
                var gradientLegendGrey = 'linear-gradient(to right, rgba(196, 196, 196, 1), rgba(130, 130, 130, 1))';

                var gradientStrokeBrown = ctx.createLinearGradient(0, 0, 0, 300);
                gradientStrokeBrown.addColorStop(0, 'rgba(255, 196, 135, 1)');
                gradientStrokeBrown.addColorStop(1, 'rgba(255, 145, 99, 1)');
                var gradientLegendBrown = 'linear-gradient(to right, rgba(255, 196, 135, 1), rgba(255, 145, 99, 1))';

                var gradientStrokeLime = ctx.createLinearGradient(0, 0, 0, 300);
                gradientStrokeLime.addColorStop(0, 'rgba(141, 255, 122, 1)');
                gradientStrokeLime.addColorStop(1, 'rgba(106, 255, 145, 1)');
                var gradientLegendLime = 'linear-gradient(to right, rgba(141, 255, 122, 1), rgba(106, 255, 145, 1))';


                var trafficChartData = {
                    datasets: [{
                    data: <?= json_encode(array_values($productSalesCount)) ?>,
                    backgroundColor: [
                        gradientStrokeBlue,
                        gradientStrokeGreen,
                        gradientStrokeRed,
                        gradientStrokeYellow,
                        gradientStrokePurple,
                        gradientStrokePink,
                        gradientStrokeOrange,
                        gradientStrokeGrey,
                        gradientStrokeBrown,
                        gradientStrokeLime
                    ],
                    hoverBackgroundColor: [
                        gradientStrokeBlue,
                        gradientStrokeGreen,
                        gradientStrokeRed,
                        gradientStrokeYellow,
                        gradientStrokePurple,
                        gradientStrokePink,
                        gradientStrokeOrange,
                        gradientStrokeGrey,
                        gradientStrokeBrown,
                        gradientStrokeLime
                    ],
                    borderColor: [
                        gradientStrokeBlue,
                        gradientStrokeGreen,
                        gradientStrokeRed,
                        gradientStrokeYellow,
                        gradientStrokePurple,
                        gradientStrokePink,
                        gradientStrokeOrange,
                        gradientStrokeGrey,
                        gradientStrokeBrown,
                        gradientStrokeLime
                    ],
                    legendColor: [
                        gradientLegendBlue,
                        gradientLegendGreen,
                        gradientLegendRed,
                        gradientLegendYellow,
                        gradientLegendPurple,
                        gradientLegendPink,
                        gradientLegendOrange,
                        gradientLegendGrey,
                        gradientLegendBrown,
                        gradientLegendLime
                    ]
                    }],
                
                    // These labels appear in the legend and in the tooltips when hovering different arcs
                    labels: <?= json_encode(array_keys($productSalesCount)) ?>
                };
                var trafficChartOptions = {
                    responsive: true,
                    animation: {
                    animateScale: true,
                    animateRotate: true
                    },
                    legend: false,
                    legendCallback: function(chart) {
                    var text = []; 
                    text.push('<ul>'); 
                    for (var i = 0; i < trafficChartData.datasets[0].data.length; i++) { 
                        text.push('<li><span class="legend-dots" style="background:' + 
                        trafficChartData.datasets[0].legendColor[i] + 
                                    '"></span>'); 
                        if (trafficChartData.labels[i]) { 
                            text.push(trafficChartData.labels[i]); 
                        }
                        text.push('<span class="float-right"> '+trafficChartData.datasets[0].data[i]+""+'</span>')
                        text.push('</li>'); 
                    } 
                    text.push('</ul>'); 
                    return text.join('');
                    }
                };
                var trafficChartCanvas = $("#traffic-chart").get(0).getContext("2d");
                var trafficChart = new Chart(trafficChartCanvas, {
                    type: 'doughnut',
                    data: trafficChartData,
                    options: trafficChartOptions
                });
                $("#traffic-chart-legend").html(trafficChart.generateLegend());      
            }
            if ($("#inline-datepicker").length) {
                $('#inline-datepicker').datepicker({
                    enableOnReadonly: true,
                    todayHighlight: true,
                });
            }
        });
    })(jQuery);
    </script>
@endsection


