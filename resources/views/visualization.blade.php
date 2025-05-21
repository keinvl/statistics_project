@extends('layouts.main')

@section('title', 'Data Visualization')

@section('content')
<div class="visualization-container">
    <h1 class="welcome-title">WELCOME</h1>
    <p class="welcome-subtitle">{{ Auth::user()->name }}!</p>

    <div class="visualization-box">
        <h2 class="mb-4">Data Visualization</h2>
        <p class="text-muted mb-4 text-center">
            Select a chart type and enter the required values to visualize the graph.
        </p>
        <hr class="mb-4">
        <form action="{{ route('visualization.render') }}" method="POST" class="mb-4" id="visualForm">
            @csrf
            <div class="mb-3">
                <label for="chart_type">Chart Type</label>
                <select name="chart_type" class="form-select" id="chart_type" required>
                    <option value="bar">Bar</option>
                    <option value="line">Line</option>
                    <option value="pie">Pie</option>
                    <option value="scatter">Scatter</option>
                    <option value="histogram">Histogram</option>
                    <option value="box">Box Plot</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="labels">Labels (comma separated)</label>
                <input type="text" class="form-control" name="labels" id="labels" placeholder="A,B,C,D" required>
            </div>

            <div class="mb-3">
                <label for="values">Values (comma separated)</label>
                <input type="text" class="form-control" name="values" id="values" placeholder="10,20,15,25" required>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="visualize-btn">Visualize</button>
                @if (!empty($type))
                    <button type="button" class="reset-button" id="reset-btn">Reset</button>
                @endif
            </div>
        </form>

        @if (!empty($type))
            <div id="chartContainer" class="mt-4">
                @if ($type === 'box')
                    <div id="boxPlot" style="height: 400px;"></div>
                @elseif ($type === 'histogram')
                    <div id="histogramPlot" style="height: 400px;"></div>
                @else
                    <canvas id="myChart" width="400" height="200"></canvas>
                @endif
            </div>
        @endif
    </div>
</div>

@if (!empty($type))
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Plotly CDN -->
<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
<script>
    const type = "{{ $type }}";
    const labels = {!! json_encode($labels) !!};
    const values = {!! json_encode($values) !!};

    if (type === 'box') {
        Plotly.newPlot('boxPlot', [{
            y: values,
            type: 'box',
            boxpoints: 'all',
            jitter: 0.5,
            marker: {
                color: 'rgba(54, 162, 235, 0.6)'
            }
        }], {
            title: 'Box Plot of Data Distribution',
            yaxis: { title: 'Values' }
        });
    } else if (type === 'histogram') {
        Plotly.newPlot('histogramPlot', [{
            x: values,
            type: 'histogram',
            marker: { color: 'rgba(255,99,132,0.6)' }
        }], {
            title: 'Histogram of Frequency Distribution',
            xaxis: { title: 'Data Bins' },
            yaxis: { title: 'Frequency' }
        });
    } else {
        const ctx = document.getElementById('myChart').getContext('2d');
        let chartConfig;

        if (type === 'scatter') {
            const scatterData = values.map((v, i) => ({ x: i, y: v }));
            chartConfig = {
                type: 'scatter',
                data: {
                    datasets: [{
                        label: 'Scatter Plot',
                        data: scatterData,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)'
                    }]
                },
                options: {
                    scales: {
                        x: { type: 'linear', position: 'bottom' }
                    }
                }
            };
        } else {
            chartConfig = {
                type: type,
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Data',
                        data: values,
                        backgroundColor: 'rgba(153, 102, 255, 0.6)'
                    }]
                }
            };
        }

        new Chart(ctx, chartConfig);
    }

    // RESET BUTTON HANDLER
    document.getElementById('reset-btn')?.addEventListener('click', function () {
        document.getElementById('visualForm').reset();
        document.getElementById('chartContainer').innerHTML = '';
    });

</script>
@endif
@endsection