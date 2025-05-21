function calculateStats() {
    let input = document.getElementById('inputNumbers').value;
    if (!input.trim()) {
        alert("Please enter numbers.");
        return;
    }

    let nums = input.split(',').map(Number).filter(n => !isNaN(n)).sort((a, b) => a - b);
    if (nums.length === 0) {
        alert("Invalid input.");
        return;
    }

    function mean(arr) {
        return (arr.reduce((a, b) => a + b, 0) / arr.length).toFixed(2);
    }

    function median(arr) {
        const mid = Math.floor(arr.length / 2);
        return arr.length % 2 !== 0 ? arr[mid] : ((arr[mid - 1] + arr[mid]) / 2).toFixed(2);
    }

    function mode(arr) {
        const freq = {};
        arr.forEach(n => freq[n] = (freq[n] || 0) + 1);
        const max = Math.max(...Object.values(freq));
        const modes = Object.keys(freq).filter(k => freq[k] === max);
        return modes.join(', ');
    }

    function range(arr) {
        return arr[arr.length - 1] - arr[0];
    }

    function variance(arr) {
        let m = arr.reduce((a, b) => a + b) / arr.length;
        return (arr.reduce((sum, val) => sum + (val - m) ** 2, 0) / arr.length).toFixed(2);
    }

    function stdDev(arr) {
        return Math.sqrt(variance(arr)).toFixed(2);
    }

    function quartiles(arr) {
        const mid = Math.floor(arr.length / 2);
        const q1 = median(arr.slice(0, mid));
        const q3 = median(arr.slice(arr.length % 2 === 0 ? mid : mid + 1));
        return [q1, q3];
    }

    const q = quartiles(nums);
    const iqr = (q[1] - q[0]).toFixed(2);

    document.getElementById('results').innerHTML = `
        <div style="display: flex; flex-wrap: wrap; justify-content: center;">
            <div class="stat-card">Mean<br><strong>${mean(nums)}</strong></div>
            <div class="stat-card">Median (Q2)<br><strong>${median(nums)}</strong></div>
            <div class="stat-card">Mode<br><strong>${mode(nums)}</strong></div>
            <div class="stat-card">Range<br><strong>${range(nums)}</strong></div>
            <div class="stat-card">Variance<br><strong>${variance(nums)}</strong></div>
            <div class="stat-card">Std Dev<br><strong>${stdDev(nums)}</strong></div>
            <div class="stat-card">Q1<br><strong>${q[0]}</strong></div>
            <div class="stat-card">Q3<br><strong>${q[1]}</strong></div>
            <div class="stat-card">IQR<br><strong>${iqr}</strong></div>
        </div>
    `;
    document.getElementById('results').style.display = 'block';
    document.getElementById('resetBtn').style.display = 'inline-block';
}

function resetStats() {
    document.getElementById('inputNumbers').value = '';
    document.getElementById('results').innerHTML = '';
    document.getElementById('results').style.display = 'none';
    document.getElementById('resetBtn').style.display = 'none';
    document.getElementById('inputNumbers').focus();
}