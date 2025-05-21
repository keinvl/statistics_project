@extends('layouts.main')

@section('title', 'Inferential Statistics Calculator')

@section('content')
<div class="inferential-container">
    <h1 class="welcome-title">WELCOME</h1>
    <p class="welcome-subtitle">{{ Auth::user()->name }}!</p>

    <div class="inferential-box">
        <h2 class="mb-4">Inferential Statistics Calculator</h2>
        <p class="text-muted mb-4 text-center">
            Select a statistical test and enter the required values to perform calculations.
        </p>
        <hr class="mb-4">

        <h3>Confidence Interval (T-Test)</h3>
        <form action="{{ url('/confidence-interval-t') }}" method="POST">
            @csrf
            <label>Mean (x̄): <input type="number" step="any" name="mean" required></label><br>
            <label>Standard Deviation (s): <input type="number" step="any" name="std_dev" required></label><br>
            <label>Sample Size (n): <input type="number" name="n" required></label><br>
            <label>T value (α level): <input type="number" step="any" name="t" value="2.262" required></label><br>
            <button type="submit" class="btn btn-primary mt-3">Calculate CI</button>
        </form>

        @if(session('ci_t_result'))
            <p><strong>Confidence Interval:</strong> {{ session('ci_t_result')['lower'] }} to {{ session('ci_t_result')['upper'] }}</p>
        @endif

        <hr>

        <h3>Confidence Interval (Z-Test)</h3>
        <form action="{{ url('/confidence-interval') }}" method="POST">
            @csrf
            <label>Mean (x̄): <input type="number" step="any" name="mean" required></label><br>
            <label>Standard Deviation (σ): <input type="number" step="any" name="std_dev" required></label><br>
            <label>Sample Size (n): <input type="number" name="n" required></label><br>
            <label>Z value (α level): <input type="number" step="any" name="z" value="1.96" required></label><br>
            <button type="submit" class="btn btn-primary mt-3">Calculate CI</button>
        </form>

        @if(session('ci_result'))
            <p><strong>Confidence Interval:</strong> {{ session('ci_result')['lower'] }} to {{ session('ci_result')['upper'] }}</p>
        @endif

        <hr>

        <h3>One Sample Hypothesis Test (T-Test)</h3>
        <form action="{{ url('/hypothesis-test') }}" method="POST">
            @csrf
            <label>Sample Mean (x̄): <input type="number" step="any" name="mean" required></label><br>
            <label>Population Mean (μ₀): <input type="number" step="any" name="pop_mean" required></label><br>
            <label>Standard Deviation (s): <input type="number" step="any" name="std_dev" required></label><br>
            <label>Sample Size (n): <input type="number" name="n" required></label><br>
            <button type="submit" class="btn btn-primary mt-3">Calculate T-value</button>
        </form>

        @if(session('ht_result'))
            <p><strong>Calculated T:</strong> {{ session('ht_result') }}</p>
        @endif

        <hr>

        <h3>Two Sample Hypothesis Test (T-Test)</h3>
        <form action="{{ url('/two-sample-test') }}" method="POST">
            @csrf
            <label>Mean 1 (x̄₁): <input type="number" step="any" name="mean1" required></label><br>
            <label>Standard Deviation 1 (s₁): <input type="number" step="any" name="std1" required></label><br>
            <label>Sample Size 1 (n₁): <input type="number" name="n1" required></label><br><br>

            <label>Mean 2 (x̄₂): <input type="number" step="any" name="mean2" required></label><br>
            <label>Standard Deviation 2 (s₂): <input type="number" step="any" name="std2" required></label><br>
            <label>Sample Size 2 (n₂): <input type="number" name="n2" required></label><br>

            <button type="submit" class="btn btn-primary mt-3">Calculate T-value</button>
        </form>
        @if(session('ts_result'))
            <p><strong>Two Sample T:</strong> {{ session('ts_result') }}</p>
        @endif

        <hr>

        <h3>Chi-Square Test</h3>
        <form action="{{ url('/chi-square') }}" method="POST">
            @csrf
            <label>Observed Values (comma separated):<br>
                <input type="text" name="observed" placeholder="e.g. 10,20,30" required>
            </label><br>
            <label>Expected Values (comma separated):<br>
                <input type="text" name="expected" placeholder="e.g. 15,25,20" required>
            </label><br>
            <button type="submit" class="btn btn-primary mt-3">Calculate Chi-Square</button>
        </form>
    </div>
</div>
@if(session('chi_result'))
    <p><strong>Chi-Square Value:</strong> {{ session('chi_result') }}</p>
@endif

@endsection