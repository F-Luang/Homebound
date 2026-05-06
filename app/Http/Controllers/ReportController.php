<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Application;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(): View
    {
        // Overview stats
        $totalPets = Pet::count();
        $totalAdopted = Pet::where('status', 'adopted')->count();
        $totalAvailable = Pet::where('status', 'available')->count();
        $totalApplications = Application::count();
        $totalAdopters = User::where('role', 'adopter')->count();
        $totalVolunteers = User::where('role', 'volunteer')->count();

        // Adoptions per month (last 6 months)
        $adoptionsPerMonth = Application::select(
            DB::raw('MONTH(submitted_at) as month'),
            DB::raw('YEAR(submitted_at) as year'),
            DB::raw('COUNT(*) as total')
        )
            ->where('status', 'completed')
            ->where('submitted_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(fn($r) => [
                'label' => \Carbon\Carbon::createFromDate($r->year, $r->month, 1)->format('M Y'),
                'total' => $r->total,
            ]);

        // Applications per month (last 6 months)
        $applicationsPerMonth = Application::select(
            DB::raw('MONTH(submitted_at) as month'),
            DB::raw('YEAR(submitted_at) as year'),
            DB::raw('COUNT(*) as total')
        )
            ->where('submitted_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(fn($r) => [
                'label' => \Carbon\Carbon::createFromDate($r->year, $r->month, 1)->format('M Y'),
                'total' => $r->total,
            ]);

        // Species breakdown
        $speciesBreakdown = Pet::select('species', DB::raw('COUNT(*) as total'))
            ->groupBy('species')
            ->get();

        // Application status breakdown
        $statusBreakdown = Application::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        // Most applied pets
        $mostAppliedPets = Pet::withCount('applications')
            ->orderByDesc('applications_count')
            ->take(5)
            ->get();

        // Recent completions
        $recentAdoptions = Application::with(['user', 'pet'])
            ->where('status', 'completed')
            ->latest('submitted_at')
            ->take(5)
            ->get();

        // Conversion rate
        $conversionRate = $totalApplications > 0
            ? round(($totalAdopted / $totalApplications) * 100, 1)
            : 0;

        return view('reports.index', compact(
            'totalPets',
            'totalAdopted',
            'totalAvailable',
            'totalApplications',
            'totalAdopters',
            'totalVolunteers',
            'adoptionsPerMonth',
            'applicationsPerMonth',
            'speciesBreakdown',
            'statusBreakdown',
            'mostAppliedPets',
            'recentAdoptions',
            'conversionRate'
        ));
    }
}