<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class BlockedPeriodController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => BlockedPeriod::orderByDesc('start_date')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validator = $this->validateInput($request);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $blocked = BlockedPeriod::create([
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
            'message'    => $request->message,
            'created_by' => Auth::id(),
        ]);

        return response()->json(['message' => 'Blok succesvol toegevoegd.', 'data' => $blocked]);
    }

    public function update(Request $request, BlockedPeriod $blockedPeriod)
    {
        $validator = $this->validateInput($request);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $blockedPeriod->update([
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
            'message'    => $request->message,
        ]);

        return response()->json(['message' => 'Blok succesvol bijgewerkt.', 'data' => $blockedPeriod]);
    }

    public function destroy(BlockedPeriod $blockedPeriod)
    {
        $blockedPeriod->delete();

        return response()->json(['message' => 'Blok verwijderd.']);
    }

    public function active(Request $request)
    {
        $today = now()->startOfDay();
        $blocks = BlockedPeriod::whereDate('end_date', '>=', $today)
            ->orWhere(function ($q) use ($today) {
                $q->whereNull('end_date')->whereDate('start_date', '>=', $today);
            })
            ->orderBy('start_date')
            ->get();

        return response()->json($blocks);
    }

    private function validateInput(Request $request)
    {
        return Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time'   => 'nullable|date_format:H:i|after:start_time',
            'message'    => 'nullable|string|max:500',
        ]);
    }
}
