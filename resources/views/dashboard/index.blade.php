@extends('layout.default')
@section('content')
    <div class="">
        <canvas id="myChart"></canvas>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('myChart');

        {{--const labels = Object.keys(<?php echo json_encode($result); ?>);--}}
        {{--const data = Object.values(<?php echo json_encode($result); ?>);--}}

        let result = <?php echo json_encode($result); ?>;
        let labels = [];
        let datasets = [];

        $.each(result, function(i, item) {
            labels = Object.keys(item.datasets);
            datasets.push({
                label: item.label,
                data: Object.values(item.datasets),
                fill: false,
                borderColor: item.borderColor,
                backgroundColor: item.backgroundColor,
                tension: 0.5
            });
        });

        const setting = {
            labels: labels,
            datasets: datasets
        };

        new Chart(ctx, {
            type: 'line',
            data: setting,
            options: {
                scales: {
                    y: {
                        ticks: {
                            callback: function(value, index, ticks) {
                                return value + ' items';
                            }
                        }
                    }
                },
                layout: {
                    padding: 20
                }
            }
        });
    </script>
@endsection
