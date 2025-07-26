<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Testimonial;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user() || !$request->user()->is_admin) {
            abort(403, 'Toegang geweigerd');
        }

        return Inertia::render('Admin/Dashboard');
    }

    public function summary()
    {
        $today = Carbon::today();
        $startOfWeek = $today->copy()->startOfWeek();
        $endOfWeek = $today->copy()->endOfWeek();

        $stats = [
            ['label' => 'Totaal klanten', 'value' => User::where('is_admin', false)->count()],
            ['label' => 'Afspraken deze week', 'value' => Appointment::whereBetween('start_time', [$startOfWeek, $endOfWeek])->count()],
            ['label' => 'Omzet deze maand', 'value' => '€ ' . Appointment::whereMonth('start_time', $today->month)
                    ->whereHas('service')
                    ->get()
                    ->sum(fn ($a) => optional($a->service)->price ?? 0)],
            ['label' => 'Reviews ontvangen', 'value' => Testimonial::count()],
        ];


        $todayAppointments = Appointment::with('service')
            ->whereDate('start_time', $today)
            ->orderBy('start_time')
            ->get()
            ->map(fn ($a) => [
                'id' => $a->id,
                'time' => $a->start_time->format('H:i'),
                'name' => $a->name,
                'service' => optional($a->service)->title ?? '—',
            ]);

        $labels = collect(range(-7, 7))->map(fn ($i) =>
        now()->addDays($i)->format('Y-m-d')
        );

        $values = $labels->map(fn ($date) =>
        Appointment::whereDate('start_time', $date)->count()
        );

        $topCustomers = User::where('is_admin', false)
            ->withCount('appointments')
            ->with(['appointments' => fn ($q) => $q->with('service')])
            ->orderByDesc('appointments_count')
            ->take(5)
            ->get()
            ->map(function ($user) {
                $totalSpent = $user->appointments->sum(fn ($a) => optional($a->service)->price ?? 0);

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'total_appointments' => $user->appointments_count,
                    'total_spent' => $totalSpent,
                ];
            });

        return response()->json([
            'stats' => $stats,
            'today_appointments' => $todayAppointments,
            'chart' => [
                'labels' => $labels,
                'values' => $values,
            ],
            'top_customers' => $topCustomers,
        ]);
    }


}
