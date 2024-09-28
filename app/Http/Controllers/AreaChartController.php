<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AreaChartController extends Controller
{
    public function areaChart()
    {
        $data = [
            'labels' => ['January', 'February', 'March', 'April', 'May'],
            'data' => [65, 59, 80, 81, 56],
        ];
        return view('dashboard', compact('data'));
    }
}
