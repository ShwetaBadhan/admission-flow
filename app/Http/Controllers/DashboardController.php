<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\College;
use App\Models\Admission;
use App\Models\Consultant;
use App\Models\Course;
use App\Models\AdmissionRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $currentStart = $now->copy()->startOfWeek();
        $currentEnd = $now->copy()->endOfWeek();
        $lastStart = $now->copy()->subWeek()->startOfWeek();
        $lastEnd = $now->copy()->subWeek()->endOfWeek();

        $calculateChange = function ($current, $previous) {
            if ($previous == 0) return $current > 0 ? 100.00 : 0.00;
            return round((($current - $previous) / $previous) * 100, 2);
        };

        // 📊 Top Cards Data
        $leadsCurrent = Lead::count();
        $leadsThisWeek = Lead::whereBetween('created_at', [$currentStart, $currentEnd])->count();
        $leadsLastWeek = Lead::whereBetween('created_at', [$lastStart, $lastEnd])->count();
        $leadsChange = $calculateChange($leadsThisWeek, $leadsLastWeek);

        $collegesCurrent = College::count();
        $collegesThisWeek = College::whereBetween('created_at', [$currentStart, $currentEnd])->count();
        $collegesLastWeek = College::whereBetween('created_at', [$lastStart, $lastEnd])->count();
        $collegesChange = $calculateChange($collegesThisWeek, $collegesLastWeek);

        $coursesCurrent = Course::count();
        $coursesThisWeek = Course::whereBetween('created_at', [$currentStart, $currentEnd])->count();
        $coursesLastWeek = Course::whereBetween('created_at', [$lastStart, $lastEnd])->count();
        $coursesChange = $calculateChange($coursesThisWeek, $coursesLastWeek);

        $consultantsCurrent = Consultant::count();
        $consultantsThisWeek = Consultant::whereBetween('created_at', [$currentStart, $currentEnd])->count();
        $consultantsLastWeek = Consultant::whereBetween('created_at', [$lastStart, $lastEnd])->count();
        $consultantsChange = $calculateChange($consultantsThisWeek, $consultantsLastWeek);

        
        // 🎯 ADMISSIONS DATA (Accepted Status)
        $totalAdmissions = AdmissionRequest::where('status', 'accepted')->count();


        // For percentage change, compare with previous period
        $admissionsThisWeek = AdmissionRequest::where('status', 'accepted')
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->count();

        $admissionsLastWeek = AdmissionRequest::where('status', 'accepted')
            ->whereBetween('created_at', [$lastStart, $lastEnd])
            ->count();

       
        if ($admissionsThisWeek == 0 && $admissionsLastWeek == 0 && $totalAdmissions > 0) {
            // Show growth based on all-time data
            $admissionsChange = 100.00; // New admissions!
        } else {
            $admissionsChange = $calculateChange($admissionsThisWeek, $admissionsLastWeek);
        }

        // 📈 REVENUE ANALYTICS (Weekly/Monthly/Yearly)
        // Assuming each accepted admission has a value (you can adjust this)
        $weeklyRevenue = $this->getRevenueData('week');
        $monthlyRevenue = $this->getRevenueData('month');
        $yearlyRevenue = $this->getRevenueData('year');

        // 🎯 LEAD SOURCES (Traffic Sources)
        $leadSources = DB::table('leads')
            ->join('lead_sources', 'leads.lead_source_id', '=', 'lead_sources.id')
            ->select('lead_sources.name', DB::raw('COUNT(leads.id) as count'))
            ->groupBy('lead_sources.id', 'lead_sources.name')
            ->orderBy('count', 'desc')
            ->get();

            // 📋 Recently Created Leads (Latest 5)
$recentLeads = Lead::latest('created_at')->take(5)->get();

// 📊 Leads by Status (For Pie Chart)
$leadStatusStats = Lead::select('status', DB::raw('COUNT(*) as count'))
    ->groupBy('status')
    ->get();

$statusLabels = $leadStatusStats->pluck('status')->map(fn($s) => ucfirst($s))->toArray();
$statusCounts = $leadStatusStats->pluck('count')->toArray();

$consultants = Consultant::latest('created_at')->take(10)->get();
        return view('pages.dashboard', compact(
            'leadsCurrent',
            'leadsChange',
            'collegesCurrent',
            'collegesChange',
            'coursesCurrent',
            'coursesChange',
            'consultantsCurrent',
            'consultantsChange',
            'totalAdmissions',
            'admissionsChange',
            'weeklyRevenue',
            'monthlyRevenue',
            'yearlyRevenue',
            'leadSources',
            'recentLeads',
            'statusLabels',
            'statusCounts',
             'consultants'
        ));
    }

    private function getRevenueData($period)
    {
        $now = Carbon::now();

        if ($period === 'week') {
            // Show last 6 months for weekly tab (more meaningful than days)
            $startDate = $now->copy()->subMonths(5)->startOfMonth();
            $endDate = $now->copy()->endOfMonth();
            $groupByExpression = "DATE_FORMAT(created_at, '%Y-%m')";
        } elseif ($period === 'month') {
            // Show current month by weeks
            $startDate = $now->copy()->startOfMonth();
            $endDate = $now->copy()->endOfMonth();
            $groupByExpression = "WEEK(created_at)";
        } else {
            // Show current year by month
            $startDate = $now->copy()->startOfYear();
            $endDate = $now->copy()->endOfYear();
            $groupByExpression = "DATE_FORMAT(created_at, '%Y-%m')";
        }

        // Get accepted admissions
        $data = AdmissionRequest::where('status', 'accepted')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw("$groupByExpression as period"),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy(DB::raw($groupByExpression))
            ->orderBy('period')
            ->get();

        // Create labels and values
        $labels = [];
        $values = [];
        $dataByPeriod = $data->pluck('count', 'period')->toArray();

        if ($period === 'week') {
            // Show last 6 months
            for ($i = 5; $i >= 0; $i--) {
                $date = $now->copy()->subMonths($i);
                $periodKey = $date->format('Y-m');
                $labels[] = $date->format('M'); // "Jan", "Feb", etc.
                $values[] = $dataByPeriod[$periodKey] ?? 0;
            }
        } elseif ($period === 'month') {
            // Show weeks of current month
            $weeksInMonth = ceil($now->daysInMonth / 7);
            for ($week = 1; $week <= $weeksInMonth; $week++) {
                $labels[] = "Week $week";
                // You can add logic to get actual week data here
                $values[] = 0; // Placeholder
            }
        } else {
            // Show all 12 months of year
            for ($month = 1; $month <= 12; $month++) {
                $date = $now->copy()->setMonth($month);
                $periodKey = $date->format('Y-m');
                $labels[] = $date->format('M'); // "Jan", "Feb", etc.
                $values[] = $dataByPeriod[$periodKey] ?? 0;
            }
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'total' => array_sum($values)
        ];
    }
}
