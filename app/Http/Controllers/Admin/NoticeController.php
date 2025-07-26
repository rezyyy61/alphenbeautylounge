<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NoticeController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Notice::orderByDesc('date')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'message' => 'nullable|string|max:500',
            'reason' => 'nullable|string|max:255',
            'show_day_before' => 'boolean',
            'expires_at' => 'required|date|after_or_equal:date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $notice = Notice::create([
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'message' => $request->message,
            'reason' => $request->reason,
            'show_day_before' => $request->show_day_before ?? true,
            'expires_at' => $request->expires_at,
        ]);

        return response()->json(['message' => 'Tijd succesvol geblokkeerd.', 'data' => $notice]);
    }


    public function active()
    {
        $today = now()->toDateString();

        $notices = Notice::where('expires_at', '>=', $today)
            ->orderBy('date', 'asc')
            ->get();

        return response()->json($notices);
    }


    public function destroy(Notice $notice)
    {
        $notice->delete();

        return response()->json(['message' => 'Blokkade verwijderd.']);
    }
}

