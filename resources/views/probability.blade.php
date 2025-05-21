@extends('layouts.main')

@section('title', 'Probability Distribution')

@section('content')
<div class="container mt-5">

    <div class="probability-container">
        
        <h1 class="welcome-title">WELCOME</h1>
        <p class="welcome-subtitle">{{ Auth::user()->name }}!</p>

        <div class="probability-box">
            <h2 class="mb-4">Probability Distribution Calculator</h2>
            {{-- Select Distribution --}}
            <form method="POST" action="{{ route('probability.calculate') }}">
                @csrf

                <div class="mb-3">
                    <label for="distribution">Select Distribution</label>
                    <select class="form-select" name="distribution" id="distribution" onchange="updateFormInputs()">
                        <option value="binomial" {{ old('distribution', $distribution ?? '') == 'binomial' ? 'selected' : '' }}>Binomial</option>
                        <option value="poisson" {{ old('distribution', $distribution ?? '') == 'poisson' ? 'selected' : '' }}>Poisson</option>
                        <option value="normal" {{ old('distribution', $distribution ?? '') == 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="exponential" {{ old('distribution', $distribution ?? '') == 'exponential' ? 'selected' : '' }}>Exponential</option>
                    </select>
                </div>

                <div id="input-fields">
                    {{-- Input fields akan digenerate oleh JS updateFormInputs() --}}
                </div>

                <button type="submit" id="btnCalculate" class="calculate-button">Calculate</button>
            </form>

            @if (!empty($errors))
                <div class="alert alert-danger mt-4">
                    <ul class="mb-0">
                        @foreach ($errors as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            @if (!empty($result))
                <div class="alert alert-success mt-4">
                    <strong>Result:</strong> {!! $result !!}
                </div>
            @endif

            {{-- Chart untuk Binomial, Poisson, Normal --}}
            @if (!empty($labels) && !empty($data) && ($distribution ?? '') !== 'exponential')
                <canvas id="chart" class="mt-5"></canvas>
            @endif

            {{-- Container chart khusus untuk Exponential --}}
            <div id="exponential-charts" class="mt-4"></div>

        </div>
    </div>
</div>

{{-- Load Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Fungsi generate input field sesuai distribusi, dengan isi nilai lama (old) jika ada
    function updateFormInputs() {
        const dist = document.getElementById('distribution').value;
        const inputDiv = document.getElementById('input-fields');
        inputDiv.innerHTML = '';

        // Fungsi bantu ambil nilai lama dari old() Laravel, fallback default
        function oldValue(name, def='') {
            // Di blade nanti kita inject nilai old ke JS object
            return oldInputs[name] !== undefined ? oldInputs[name] : def;
        }

        const htmls = {
            binomial: `
                <label for="n">n (trials)</label>
                <input class="form-control" name="n" id="n" type="number" placeholder="Enter numbers (e.g. 2,3,4,...)" class="input-field" required value="${oldValue('n')}">

                <label for="p">p (probability)</label>
                <input class="form-control" name="p" id="p" type="number" placeholder="Enter numbers (e.g. 0.2,0.3,0.4,...)" class="input-field" step="0.01" min="0" max="1" required value="${oldValue('p')}">

                <label for="x">x (successes)</label>
                <input class="form-control" name="x" id="x" type="number" placeholder="Enter numbers (e.g. 2,3,4,...)" class="input-field" required value="${oldValue('x')}">
            `,
            poisson: `
                <label for="lambda">&#955; (lambda)</label>
                <input class="form-control" name="lambda" id="lambda" type="number" step="0.01" min="0" placeholder="Enter numbers (e.g. 2,3,4,...)" class="input-field" required value="${oldValue('lambda')}">

                <label for="x">x</label>
                <input class="form-control" name="x" id="x" type="number" placeholder="Enter numbers (e.g. 2,3,4,...)" class="input-field" required value="${oldValue('x')}">
            `,
            normal: `
                <label for="mean">Mean</label>
                <input class="form-control" name="mean" id="mean" type="number" placeholder="Enter numbers (e.g. 10,55,...)" class="input-field" required value="${oldValue('mean')}">

                <label for="stddev">Standard Deviation</label>
                <input class="form-control" name="stddev" id="stddev" type="number" min="0.0001" step="0.0001" placeholder="Enter numbers (e.g. 2,3,...)" class="input-field" required value="${oldValue('stddev')}">

                <label for="x">X</label>
                <input class="form-control" name="x" id="x" type="number" placeholder="Enter numbers (e.g. 2,3,4,...)" class="input-field" required value="${oldValue('x')}">
            `,
            exponential: `
                <label for="lambda">Nilai Lambda (λ):</label>
                <input type="number" id="lambda" name="lambda" value="${oldValue('lambda', 1)}" min="0.1" step="0.1" placeholder="Enter numbers (e.g. 2,3,4,...)"  class="form-control"  style="width: 200px;">

                <button type="button" onclick="loadExponential()" class="calculate-button"> Show Graph </button>
            `
        };

        inputDiv.innerHTML = htmls[dist];

        const btnCalculate = document.getElementById('btnCalculate');
        if (dist === 'exponential') {
            btnCalculate.style.display = 'none'; // hide Calculate for exponential
            document.getElementById('exponential-charts').innerHTML = '';
        } else {
            btnCalculate.style.display = 'inline-block';
            document.getElementById('exponential-charts').innerHTML = '';
        }
    }

    // Ambil old inputs dari backend Laravel dan parse jadi JS object
    const oldInputs = @json(session()->getOldInput());

    // Jalankan saat halaman selesai load
    window.onload = updateFormInputs;

    // Chart.js untuk Binomial, Poisson, Normal
    @if (!empty($labels) && !empty($data) && ($distribution ?? '') !== 'exponential')
    const ctx = document.getElementById('chart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Probability',
                data: {!! json_encode($data) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    @endif

    // Chart.js untuk Exponential
    let expPdfChart, expCdfChart;
    function loadExponential() {
        const lambda = document.getElementById('lambda').value;

        fetch(`/exponential-distribution?lambda=${lambda}`)
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(data => {
                const labels = data.x;
                const pdf = data.pdf;
                const cdf = data.cdf;

                const container = document.getElementById('exponential-charts');
                container.innerHTML = '';

                const canvasPdf = document.createElement('canvas');
                canvasPdf.id = 'exponentialPdfChart';
                canvasPdf.height = 200;
                container.appendChild(canvasPdf);

                const canvasCdf = document.createElement('canvas');
                canvasCdf.id = 'exponentialCdfChart';
                canvasCdf.height = 200;
                container.appendChild(canvasCdf);

                if (expPdfChart) expPdfChart.destroy();
                if (expCdfChart) expCdfChart.destroy();

                const ctxPdf = canvasPdf.getContext('2d');
                const ctxCdf = canvasCdf.getContext('2d');

                expPdfChart = new Chart(ctxPdf, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: `PDF (λ = ${lambda})`,
                            data: pdf,
                            borderColor: 'blue',
                            fill: false,
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: { display: true, text: 'Probability Density Function (PDF)' }
                        },
                        scales: { y: { beginAtZero: true } }
                    }
                });

                expCdfChart = new Chart(ctxCdf, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: `CDF (λ = ${lambda})`,
                            data: cdf,
                            borderColor: 'green',
                            fill: false,
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: { display: true, text: 'Cumulative Distribution Function (CDF)' }
                        },
                        scales: { y: { beginAtZero: true, max: 1 } }
                    }
                });
            })
            .catch(error => console.error('Error fetching exponential distribution data:', error));
    }

    // Jika halaman di-load dengan exponential sudah dipilih dan nilai lambda ada, langsung tampilkan grafik
    window.addEventListener('load', () => {
        if (document.getElementById('distribution').value === 'exponential') {
            loadExponential();
        }
    });
</script>

<style>
    /* Optional styling */
    #exponential-charts canvas {
        margin-top: 20px;
        max-width: 100%;
    }
</style>
@endsection