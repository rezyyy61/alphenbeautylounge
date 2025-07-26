<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminAppointmentController extends Controller
{
    public function list()
    {
        $appointments = Appointment::with('service')->latest()->get();
        return response()->json([
            'count' => $appointments->count(),
            'items' => $appointments,
        ]);
    }

    public function checkConflicts(Request $request)
    {
        $request->validate([
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date',
            'start_time'  => 'nullable|date_format:H:i',
            'end_time'    => 'nullable|date_format:H:i',
        ]);

        $start = Carbon::parse($request->start_date);
        $end = $request->end_date ? Carbon::parse($request->end_date) : $start;

        $appointments = Appointment::whereDate('start_time', '>=', $start)
            ->whereDate('start_time', '<=', $end);

        if ($request->start_time && $request->end_time) {
            $appointments->where(function ($q) use ($request) {
                $q->whereTime('start_time', '<', $request->end_time)
                    ->whereTime('end_time', '>', $request->start_time);
            });
        }

        $hasConflict = $appointments->exists();

        return response()->json(['conflict' => $hasConflict]);
    }


    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return response()->json(['message' => 'Afsrpaak verwijderd']);
    }
}
