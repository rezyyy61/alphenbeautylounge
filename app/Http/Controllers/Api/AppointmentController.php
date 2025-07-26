<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\AdminAppointmentAlert;
use App\Mail\AppointmentConfirmed;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'phone'      => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'notes'      => 'nullable|string',
            'service_id' => 'required|exists:services,id',
            'day'        => 'required|date_format:Y-m-d',
            'time'       => 'required|regex:/^\d{2}:\d{2}$/',
        ]);

        $service = Service::findOrFail($data['service_id']);

        if (!$service->parent_id) {
            return response()->json(['message' => 'Je kunt alleen een afspraak maken voor een specifieke behandeling (subservice).'], 422);
        }

        $start = Carbon::createFromFormat('Y-m-d H:i', $data['day'].' '.$data['time']);
        $end = (clone $start)->addMinutes($service->duration);

        $overlap = Appointment::where('service_id', $service->id)
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end)
                    ->where('end_time', '>', $start);
            })
            ->exists();

        if ($overlap) {
            return response()->json(['message' => 'Deze tijd is al geboekt.'], 409);
        }

        $user = User::firstOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['name'],
                'phone' => $data['phone'],
                'is_guest' => true,
                'password' => bcrypt(uniqid()),
            ]
        );


        $appointment = Appointment::create([
            'name'       => $data['name'],
            'phone'      => $data['phone'],
            'email'      => $data['email'],
            'notes'      => $data['notes'],
            'service_id' => $service->id,
            'start_time' => $start,
            'end_time'   => $end,
            'user_id'    => $user->id,
        ]);

//        Mail::to($appointment->email)->send(new AppointmentConfirmed($appointment));

        $conflictingAppointments = Appointment::whereDate('start_time', $start->toDateString())
            ->where('id', '!=', $appointment->id)
            ->orderBy('start_time')
            ->get();


//        Mail::to('Alphen.beauty.lounge@gmail.com')->send(new AdminAppointmentAlert($appointment, $conflictingAppointments));

        return response()->json(['message' => 'Afspraak succesvol opgeslagen.'], 201);
    }

    public function takenTimes($day)
    {
        $startOfDay = Carbon::parse($day)->startOfDay();
        $endOfDay   = Carbon::parse($day)->endOfDay();

        $appointments = Appointment::with('service')
        ->whereBetween('start_time', [$startOfDay, $endOfDay])
            ->get();

        $slots = [];

        foreach ($appointments as $app) {
            $duration = $app->service->duration ?? 30;
            $slot = $app->start_time->copy();
            while ($slot < $app->start_time->copy()->addMinutes($duration)) {
                $slots[] = $slot->format('H:i');
                $slot->addMinutes(15);
            }
        }

        return response()->json(array_values(array_unique($slots)));
    }

}
