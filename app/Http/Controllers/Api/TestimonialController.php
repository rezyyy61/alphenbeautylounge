<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{

    public function index()
    {
        return Testimonial::orderBy('created_at', 'desc')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'service' => 'required',
            'text' => 'required',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $data['avatar'] = 'https://i.pravatar.cc/100?u=' . $data['name'];

        return Testimonial::create($data);
    }
    public function update(Request $request, Testimonial $testimonial)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'service' => 'required|string|max:255',
            'text' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $testimonial->update($data);

        return response()->json([
            'message' => 'Review succesvol bijgewerkt.',
            'testimonial' => $testimonial
        ]);
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return response()->json([
            'message' => 'Review succesvol verwijderd.'
        ]);
    }
}
