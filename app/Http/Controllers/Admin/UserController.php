<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = \App\Models\User::query()
            ->where('is_admin', false)
            ->with(['appointments' => fn ($q) => $q->with('service')->latest()])
            ->withCount('appointments')
            ->orderByDesc('created_at')
            ->paginate(10);

        $mapped = $users->getCollection()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'total_appointments' => $user->appointments_count,
                'last_appointment' => optional($user->appointments->first())->start_time?->format('d M Y H:i'),
                'total_spent' => $user->appointments->sum(fn ($a) => optional($a->service)->price ?? 0),
            ];
        });

        return response()->json([
            'data' => $mapped,
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
        ]);
    }


    public function show(User $user)
    {
        $appointments = $user->appointments()->with('service')->orderByDesc('start_time')->get();
        $total = $appointments->sum(fn ($a) => optional($a->service)->price ?? 0);

        return response()->json([
            'user' => $user,
            'appointments' => $appointments,
            'total_spent' => $total,
        ]);
    }

}
