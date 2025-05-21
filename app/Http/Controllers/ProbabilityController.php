<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Response;

class ProbabilityController extends Controller
{
    public function show()
    {
        return view('probability');
    }

    public function calculate(Request $request)
    {
        $distribution = $request->input('distribution');
        $result = null;
        $labels = [];
        $data = [];
        $errors = [];

        switch ($distribution) {
            case 'binomial':
                $n = (int) $request->input('n');
                $p = (float) $request->input('p');
                $x = (int) $request->input('x');

                if ($n < 0 || $p < 0 || $p > 1 || $x < 0 || $x > $n) {
                    $errors[] = "Invalid parameters for Binomial distribution.";
                    break;
                }

                $result = $this->binomial($n, $p, $x);
                for ($i = 0; $i <= $n; $i++) {
                    $labels[] = $i;
                    $data[] = $this->binomialValue($n, $p, $i);
                }
                break;

            case 'poisson':
                $lambda = (float) $request->input('lambda');
                $x = (int) $request->input('x');

                if ($lambda <= 0 || $x < 0) {
                    $errors[] = "Invalid parameters for Poisson distribution.";
                    break;
                }

                $result = $this->poisson($lambda, $x);
                for ($i = 0; $i <= $x + 10; $i++) {
                    $labels[] = $i;
                    $data[] = $this->poissonValue($lambda, $i);
                }
                break;

            case 'normal':
                $mean = (float) $request->input('mean');
                $stddev = (float) $request->input('stddev');
                $x = (float) $request->input('x');

                if ($stddev <= 0) {
                    $errors[] = "Standard deviation must be greater than 0.";
                    break;
                }

                $z = ($x - $mean) / $stddev;
                $result = "Z-score: " . round($z, 4);
                break;

            default:
                $errors[] = 'Invalid distribution type.';
        }

        return view('probability', [
            'result' => $result,
            'labels' => $labels,
            'data' => $data,
            'distribution' => $distribution,
            'errors' => $errors
        ]);
        
    }

    private function binomial($n, $p, $x)
    {
        return round($this->combination($n, $x) * pow($p, $x) * pow(1 - $p, $n - $x), 4);
    }

    private function binomialValue($n, $p, $x)
    {
        return round($this->binomial($n, $p, $x), 4);
    }

    private function poisson($lambda, $x)
    {
        return round($this->poissonValue($lambda, $x), 4);
    }

    private function poissonValue($lambda, $x)
    {
        // Rumus: (λ^x * e^(-λ)) / x!
        $numerator = pow($lambda, $x) * exp(-$lambda);
        $denominator = $this->factorial($x);
        
        return $numerator / $denominator;
    }

    private function combination($n, $r)
    {
        return $this->factorial($n) / ($this->factorial($r) * $this->factorial($n - $r));
    }

    private function factorial($n)
    {
        if ($n <= 1) return 1;
        return $n * $this->factorial($n - 1);
    }
   public function exponentialDistribution(Request $request)
{
    // Get lambda from the request, default to 1 if not provided
    $lambda = $request->input('lambda', 1);

    // Initialize arrays for data
    $data = [];
    $pdf = [];
    $cdf = [];

    // Calculate PDF and CDF for x values from 0 to 10 with a step of 0.1
    for ($x = 0; $x <= 10; $x += 0.1) {
        $pdfValue = $lambda * exp(-$lambda * $x); // PDF calculation
        $cdfValue = 1 - exp(-$lambda * $x); // CDF calculation

        // Round values and store them in arrays
        $data[] = round($x, 2);
        $pdf[] = round($pdfValue, 4);
        $cdf[] = round($cdfValue, 4);
    }

    // Return the data as a JSON response
    return response()->json([
        'x' => $data,
        'pdf' => $pdf,
        'cdf' => $cdf,
    ]);
}


}