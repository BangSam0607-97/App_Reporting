<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\TechnicianController;

class ReportController extends Controller
{
    private function sampleReports()
    {
        return [
            ['id' => 1, 'title' => 'Monthly Sales', 'category' => 'Finance', 'status' => 'processed', 'created_at' => '2025-11-28 08:00:00', 'completed_at' => '2025-11-29 09:20:00', 'data' => ['Total' => '1,200,000', 'Transactions' => '102'], 'chart' => ['labels' => ['Week 1','Week 2','Week 3','Week 4'], 'data' => [300, 250, 350, 300], 'label' => 'Weekly Sales' ]],
            ['id' => 2, 'title' => 'Active Users', 'category' => 'IT', 'status' => 'pending', 'created_at' => Carbon::now()->subDays(0)->format('Y-m-d H:i:s'), 'data' => ['Active' => '4,600', 'New' => '240'], 'chart' => ['labels' => ['Mon','Tue','Wed','Thu','Fri'], 'data' => [800,900,850,920,930], 'label' => 'Users' ]],
            ['id' => 3, 'title' => 'Support Tickets', 'category' => 'Support', 'status' => 'error', 'created_at' => Carbon::now()->subDays(1)->format('Y-m-d H:i:s'), 'data' => ['Open' => '12', 'Closed' => '302'], 'chart' => ['labels' => ['Open','Closed'], 'data' => [12,302], 'label' => 'Tickets' ], 'completed_at' => Carbon::now()->subDays(0)->format('Y-m-d H:i:s')],
        ];
    }

    public function dashboard()
    {
        $reports = $this->sampleReports();

        // Metrics
        $today = Carbon::now();
        $todayCount = 0;
        $statusCounts = [];
        foreach ($reports as $r) {
            $created = Carbon::parse($r['created_at']);
            if ($created->isSameDay($today)) {
                $todayCount++;
            }
            $status = $r['status'] ?? 'unknown';
            if (!isset($statusCounts[$status])) $statusCounts[$status] = 0;
            $statusCounts[$status]++;
        }

        // Chart aggregations (daily/week/month)
        $dailyLabels = [];
        $dailyData = [];
        $days = 30;
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dailyLabels[] = Carbon::parse($date)->format('d M');
            $dailyData[$date] = 0;
        }
        foreach ($reports as $r) {
            $date = Carbon::parse($r['created_at'])->format('Y-m-d');
            if (isset($dailyData[$date])) $dailyData[$date]++;
        }
        $dailyDataPoints = array_values($dailyData);

        // Weekly (12 weeks)
        $weeklyLabels = [];
        $weeklyData = [];
        $weeks = 12;
        for ($i = $weeks - 1; $i >= 0; $i--) {
            $start = Carbon::now()->startOfWeek()->subWeeks($i);
            $label = $start->format('d M');
            $weeklyLabels[] = $label;
            $weeklyData[$start->format('o-W')] = 0; // isoYear-week
        }
        foreach ($reports as $r) {
            $dt = Carbon::parse($r['created_at']);
            $key = $dt->format('o-W');
            if (isset($weeklyData[$key])) $weeklyData[$key]++;
        }
        $weeklyDataPoints = array_values($weeklyData);

        // Monthly (12 months)
        $monthlyLabels = [];
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = Carbon::now()->startOfMonth()->subMonths($i);
            $label = $m->format('M Y');
            $monthlyLabels[] = $label;
            $monthlyData[$m->format('Y-m')] = 0;
        }
        foreach ($reports as $r) {
            $dt = Carbon::parse($r['created_at']);
            $key = $dt->format('Y-m');
            if (isset($monthlyData[$key])) $monthlyData[$key]++;
        }
        $monthlyDataPoints = array_values($monthlyData);

        // Technician KPIs - using TechnicianController sample data
        $techController = new TechnicianController();
        $techJobs = [];
        if (method_exists($techController, 'sampleJobs')) {
            $techJobs = $techController->sampleJobs();
        }
        $techSummary = [];
        $totalCompletedWithinSLA = 0;
        $totalCompleted = 0;
        $slaHours = 48; // SLA target
        foreach ($techJobs as $job) {
            $tech = $job['assigned_to'] ?? 'Unassigned';
            if (!isset($techSummary[$tech])) {
                $techSummary[$tech] = ['total'=>0, 'completed'=>0, 'within_sla'=>0, 'avg_hours'=>0, 'sum_hours'=>0];
            }
            $techSummary[$tech]['total']++;
            if (($job['status'] ?? '') == 'done') {
                $techSummary[$tech]['completed']++;
                $totalCompleted++;
                $created = Carbon::parse($job['created_at']);
                $completed = isset($job['report']['completed_at']) ? Carbon::parse($job['report']['completed_at']) : ($job['completed_at'] ?? null);
                if ($completed) {
                    $hours = $completed->floatDiffInHours($created);
                    $techSummary[$tech]['sum_hours'] += $hours;
                    if ($hours <= $slaHours) {
                        $techSummary[$tech]['within_sla']++;
                        $totalCompletedWithinSLA++;
                    }
                }
            }
        }
        foreach ($techSummary as $tech => &$s) {
            $s['avg_hours'] = $s['completed'] ? round($s['sum_hours'] / $s['completed'], 1) : null;
            $s['sla_pct'] = $s['completed'] ? round(($s['within_sla'] / $s['completed']) * 100, 1) : null;
        }
        unset($s);

        $overallSlaPct = $totalCompleted ? round(($totalCompletedWithinSLA / $totalCompleted) * 100, 1) : null;
        $overallAvgHours = 0;
        if ($totalCompleted) {
            $sumHours = 0; $count = 0;
            foreach ($techSummary as $s) { if ($s['completed']) { $sumHours += $s['sum_hours']; $count += $s['completed']; }}
            $overallAvgHours = $count ? round($sumHours / $count, 1) : null;
        }

        $metrics = [
            'today_count' => $todayCount,
            'status_counts' => $statusCounts,
            'overall_sla_pct' => $overallSlaPct,
            'overall_avg_hours' => $overallAvgHours,
            'sla_hours' => $slaHours,
            'tech_summary' => $techSummary,
        ];

        $chartData = [
            'daily' => ['labels' => $dailyLabels, 'data' => $dailyDataPoints],
            'weekly' => ['labels' => $weeklyLabels, 'data' => $weeklyDataPoints],
            'monthly' => ['labels' => $monthlyLabels, 'data' => $monthlyDataPoints],
        ];

        return view('dashboard', compact('reports','metrics','chartData'));
    }

    public function index(Request $request)
    {
        $reports = $this->sampleReports();
        $status = $request->query('status');
        if ($status) {
            $reports = array_values(array_filter($reports, function ($r) use ($status) { return $r['status'] == $status; }));
        }
        return view('reports.index', compact('reports'));
    }

    public function show($id)
    {
        $reports = $this->sampleReports();
        foreach ($reports as $r) {
            if ($r['id'] == $id) {
                $report = $r;
                return view('reports.show', compact('report'));
            }
        }
        abort(404);
    }
}
