<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InferentialController extends Controller
{
    public function index() {
        return view('inferential');
    }

    public function confidenceInterval(Request $request) {
        $mean = $request->mean;
        $std_dev = $request->std_dev;
        $n = $request->n;
        $z = $request->z;

        $margin = $z * ($std_dev / sqrt($n));
        $lower = $mean - $margin;
        $upper = $mean + $margin;

        return back()->with('ci_result', compact('lower', 'upper'));
    }

    public function confidenceIntervalT(Request $request) {
        $mean = $request->mean;
        $std_dev = $request->std_dev;
        $n = $request->n;
        $t = $request->t;

        $margin = $t * ($std_dev / sqrt($n));
        $lower = $mean - $margin;
        $upper = $mean + $margin;

        return back()->with('ci_t_result', compact('lower', 'upper'));
    }
    
    public function hypothesisTest(Request $request) {
        $mean = $request->mean;
        $pop_mean = $request->pop_mean;
        $std_dev = $request->std_dev;
        $n = $request->n;

        $t = ($mean - $pop_mean) / ($std_dev / sqrt($n));

        return back()->with('ht_result', $t);
    }

    public function twoSampleTest(Request $request)
    {
        $mean1 = $request->mean1;
        $std1 = $request->std1;
        $n1 = $request->n1;

        $mean2 = $request->mean2;
        $std2 = $request->std2;
        $n2 = $request->n2;

        $numerator = $mean1 - $mean2;
        $denominator = sqrt(($std1 ** 2 / $n1) + ($std2 ** 2 / $n2));
        $t_value = $numerator / $denominator;

        return redirect('/inferential')->with('ts_result', round($t_value, 4));
    }

    public function chiSquare(Request $request) {
        $observed = array_map('floatval', explode(',', $request->input('observed')));
        $expected = array_map('floatval', explode(',', $request->input('expected')));


        $chi_square = 0;
        foreach ($observed as $i => $obs) {
            $exp = $expected[$i];
            $chi_square += pow($obs - $exp, 2) / $exp;
        }

        return back()->with('chi_result', $chi_square);
    }
}