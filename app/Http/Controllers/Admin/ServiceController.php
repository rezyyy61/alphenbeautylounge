<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        return Service::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:services,id',
            'price' => 'nullable|numeric',
            'image' => 'nullable|image|max:2048',
            'duration' => 'nullable|integer|min:5|max:240',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/services'), $filename);
            $data['image'] = $filename;
        }

        $service = Service::create($data);
        return response()->json($service);
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'image' => 'nullable|image|max:2048',
            'parent_id' => 'nullable|exists:services,id',
            'duration' => 'nullable|integer|min:5|max:240',
        ]);

        if ($request->hasFile('image')) {
            if ($service->image && file_exists(public_path("images/services/{$service->image}"))) {
                unlink(public_path("images/services/{$service->image}"));
            }

            $image = $request->file('image');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/services'), $filename);
            $data['image'] = $filename;
        }

        $service->update($data);
        return response()->json($service);
    }

    public function destroy(Service $service)
    {
        if ($service->image && file_exists(public_path("images/services/{$service->image}"))) {
            unlink(public_path("images/services/{$service->image}"));
        }

        $service->delete();
        return response()->json(['message' => 'Service verwijderd']);
    }
}
