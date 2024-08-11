<?php

namespace App\Http\Controllers;

use App\Exports\ExportAnalytic;
use App\Models\Analytic;
use App\Models\ParameterTesting;
use App\Models\Sample;
use App\Models\TypeTesting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $data['pendingSamples'] = Sample::get()->where('status', 'Pending')->count();
        $data['totalSamples'] = Sample::get()->count();
        $data['sampleOnProgress'] = Sample::where('status', 'On Process')->get()->count();
        $data['sampleDoneProgress'] = Sample::where('status', 'Done')->get()->count();
        $data['AnalyticsThisMonth'] = Analytic::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
        $data['AnalyticsThisYear'] = Analytic::whereYear('created_at', $currentYear)
            ->count();

        $percentageCompleted = $data['totalSamples'] > 0 ? ($data['sampleDoneProgress']  / $data['totalSamples']) * 100 : 0;

        $data['percentageCompleted'] = number_format($percentageCompleted);

        return view('dashboard', $data);
    }

    public function areaAnalytic()
    {
        $analytics = Analytic::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total_analytics')
        )
            ->groupBy('year', 'month')
            ->get();

        $months = [
            "Jan", "Feb", "Mar", "Apr", "May", "Jun",
            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
        ];

        $dataAnalytic = [];
        $year = now()->year;

        foreach ($months as $index => $month) {
            $monthNumber = $index + 1;
            $orderData = $analytics->firstWhere('month', $monthNumber);

            $dataAnalytic[] = [
                'year' => $year,
                'month' => $month,
                'total_analytics' => $orderData ? $orderData->total_analytics : 0,
            ];
        }
        return response()->json(['analytics' => $dataAnalytic]);
    }

    public function parameterTestingChart()
    {
        $data['parameterTesting'] = ParameterTesting::withCount('samples')->get();
        $data['typeSamples'] = TypeTesting::withCount('samples')->get();

        return response()->json($data);
    }

    public function exportAnalytic(Request $request)
    {
        return Excel::download(new ExportAnalytic, 'sample.xlsx');
    }
}
