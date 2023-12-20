@extends('admin/master')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">Comment Analysis</h1>
            <canvas id="scatterChart" style="height:230px"></canvas>
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

        var comments = {!! json_encode($comments) !!};
        const colorMap = {
            '1': 'rgba(255, 99, 132, 0.2)',
            '2': 'rgba(54, 162, 235, 0.2)',
            '3': 'rgba(255, 206, 86, 0.2)',
            '4': 'rgba(75, 192, 192, 0.2)',
            '5': 'rgba(153, 102, 255, 0.2)'
        };

        // Group comments by the combination of likes and rating
        const groupedComments = {};
        comments.forEach(comment => {
            const key = `Likes: ${comment.num_likes}, Rating: ${comment.rating}`;
            if (!groupedComments[key]) {
                groupedComments[key] = {
                    x: comment.num_likes,
                    y: comment.rating,
                    count: 1,
                    color: colorMap[comment.rating]
                };
            } else {
                groupedComments[key].count++;
            }
        });

        // Create datasets for each star rating
        const datasets = Object.keys(colorMap).map(rating => {
            const dataForRating = comments
                .filter(comment => comment.rating.toString() === rating)
                .map(comment => ({
                    x: comment.num_likes,
                    y: parseInt(comment.rating),
                    count: 1
                }));

            const totalCommentsForRating = dataForRating.reduce((total, data) => total + data.count, 0);

            return {
                label: `${rating} Stars (${totalCommentsForRating} Comments)`,
                data: dataForRating,
                backgroundColor: colorMap[rating],
                borderColor: colorMap[rating].replace('0.2', '1'),
                borderWidth: 1,
            };
        });

        var scatterChartData = { datasets };

        // Keep track of displayed tooltips
        let displayedTooltips = {};

        var scatterChartOptions = {
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        const key = `Likes: ${tooltipItem.xLabel}, Rating: ${tooltipItem.yLabel}`;
                        const group = groupedComments[key];
                        
                        if (!displayedTooltips[key]) {
                            displayedTooltips[key] = true;
                            return `${group.y} Stars: ${group.count} total comments (Likes: ${group.x}, Rating: ${group.y})`;
                        } else {
                            return ''; // Return empty string to hide the tooltip
                        }
                    }
                }
            },
            scales: {
                xAxes: [{
                    type: 'linear',
                    position: 'bottom',
                    ticks: {
                        beginAtZero: true,
                        precision: 0 // <-- Set precision to 0 to remove decimal places
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Number of likes'
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1,
                        max: 5
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Star rating'
                    }
                }]
            },
            onHover: (event, chartElement) => {
                if (chartElement.length === 0) {
                    displayedTooltips = {}; // Reset when the mouse leaves a data point
                }
            }
        }

        if ($("#scatterChart").length) {
            var scatterChartCanvas = $("#scatterChart").get(0).getContext("2d");
            var scatterChart = new Chart(scatterChartCanvas, {
                type: 'scatter',
                data: scatterChartData,
                options: scatterChartOptions
            });
        }

    });
</script>

@endsection