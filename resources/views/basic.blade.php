@extends('layouts.main')

@section('title', 'Basic Statistics Calculator')

@section('content')
<div class="basic-container">
    <h1 class="welcome-title">WELCOME</h1>
    <p class="welcome-subtitle">{{ Auth::user()->name }}!</p>

    <div class="calculator-box">
        <h2 class="mb-4">Basic Statistics Calculator</h2>
        <input type="text" id="inputNumbers" placeholder="Enter numbers (e.g. 5,10,14)" class="input-field">
        <button onclick="calculateStats()" class="calculate-button">Calculate</button>
        <button id="resetBtn" onclick="resetStats()" class="reset-button" style="display: none;">Reset</button>
        <div id="results" class="results-area">
            <!-- Output stats will appear here -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/basic.js') }}"></script>
@endpush