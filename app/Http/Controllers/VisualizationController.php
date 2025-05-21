<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VisualizationController extends Controller
{
    public function show()
    {
        return view('visualization');
    }

    public function render(Request $request)
    {
        $labels = explode(',', $request->labels);
        $values = array_map('floatval', explode(',', $request->values));
        $type = $request->chart_type;

        if ($type === 'box') {
            sort($values);
            $min = min($values);
            $max = max($values);
            $median = $this->calculateMedian($values);
            $q1 = $this->calculateMedian(array_slice($values, 0, floor(count($values) / 2)));
            $q3 = $this->calculateMedian(array_slice($values, ceil(count($values) / 2)));

            return view('visualization', compact(
                'type', 'labels', 'values', 'min', 'max', 'median', 'q1', 'q3'
            ));
        }

        return view('visualization', compact('type', 'labels', 'values'));
    }

    private function calculateMedian(array $arr)
    {
        $count = count($arr);
        $middle = floor($count / 2);
        return $count % 2 === 0
            ? ($arr[$middle - 1] + $arr[$middle]) / 2
            : $arr[$middle];
    }
}