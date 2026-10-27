<?php

namespace App\Http\Controllers;

use App\Models\Database\Blog;
use App\Models\Database\Service;
use App\Models\Database\Testimonial;
use App\Models\Database\Visitor;
use App\Services\Visitor\VisitorStatisticsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(VisitorStatisticsService $visitorStatistics)
    {
        $totalViews = Blog::sum('views');
        $totalVisitors = $visitorStatistics->totalVisitsAllTime();

        $viewChart = Blog::selectRaw('MONTH(created_at) m, SUM(views) total')
            ->groupBy('m')
            ->pluck('total');

        $visitorChart = collect($visitorStatistics->dailyVisits(now()->startOfYear(), now()->endOfYear()))
            ->groupBy(fn ($total, $date) => (int) substr($date, 5, 2))
            ->map(fn ($values) => $values->sum());

        $totalTestimonials = Testimonial::count();
        $totalServices = Service::count();
        $totalBlogs = Blog::count();

        return view('dashboard.index', compact(
            'totalViews',
            'totalVisitors',
            'viewChart',
            'visitorChart',
            'totalTestimonials',
            'totalServices',
            'totalBlogs'
        ));
    }

    public function blogChart(Request $request, VisitorStatisticsService $visitorStatistics)
    {
        $filter = $request->filter;

        // ================= HARI =================
        if ($filter == 'hari') {

            $views = Blog::whereDate('created_at', today())
                ->selectRaw('HOUR(created_at) label, SUM(views) total')
                ->groupBy('label')
                ->pluck('total', 'label');

            $visitors = Visitor::whereDate('created_at', today())
                ->selectRaw('HOUR(created_at) label, COUNT(*) total')
                ->groupBy('label')
                ->pluck('total', 'label');

            $categories = range(0, 23);
        }

        // ================= MINGGU =================
        elseif ($filter == 'minggu') {

            $views = Blog::whereBetween('created_at', [
                now()->subDays(6)->startOfDay(),
                now()->endOfDay(),
            ])
                ->selectRaw('DATE(created_at) label, SUM(views) total')
                ->groupBy('label')
                ->pluck('total', 'label');

            $visitors = $visitorStatistics->dailyVisits(
                now()->subDays(6)->startOfDay(),
                now()->endOfDay()
            );

            $categories = [];
            $viewData = [];
            $visitorData = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');

                $categories[] = now()->subDays($i)->format('d M');
                $viewData[] = $views[$date] ?? 0;
                $visitorData[] = $visitors[$date] ?? 0;
            }

            return response()->json([
                'views' => $viewData,
                'visitors' => $visitorData,
                'categories' => $categories,
            ]);
        }

        // ================= BULAN =================
        elseif ($filter == 'bulan') {

            $views = Blog::whereMonth('created_at', now()->month)
                ->selectRaw('DAY(created_at) label, SUM(views) total')
                ->groupBy('label')
                ->pluck('total', 'label');

            $visitors = collect($visitorStatistics->dailyVisits(now()->startOfMonth(), now()->endOfMonth()))
                ->mapWithKeys(fn ($total, $date) => [(int) substr($date, 8, 2) => $total]);

            $categories = range(1, now()->daysInMonth);
        }

        // ================= TAHUN / TOTAL =================
        else {

            $views = Blog::selectRaw('MONTH(created_at) label, SUM(views) total')
                ->groupBy('label')
                ->pluck('total', 'label');

            $visitors = collect($visitorStatistics->dailyVisits(now()->startOfYear(), now()->endOfYear()))
                ->groupBy(fn ($total, $date) => (int) substr($date, 5, 2))
                ->map(fn ($values) => $values->sum());

            $categories = [
                1 => 'Jan',
                2 => 'Feb',
                3 => 'Mar',
                4 => 'Apr',
                5 => 'May',
                6 => 'Jun',
                7 => 'Jul',
                8 => 'Aug',
                9 => 'Sep',
                10 => 'Oct',
                11 => 'Nov',
                12 => 'Dec',
            ];
        }

        // isi data kosong dengan 0
        $viewData = [];
        $visitorData = [];

        foreach ($categories as $key => $label) {
            $index = is_numeric($key) ? $key : $label;

            $viewData[] = $views[$index] ?? 0;
            $visitorData[] = $visitors[$index] ?? 0;
        }

        return response()->json([
            'views' => $viewData,
            'visitors' => $visitorData,
            'categories' => array_values($categories),
        ]);
    }
}
