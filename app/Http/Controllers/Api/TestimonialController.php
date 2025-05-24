<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function index()
    {
        return Testimonial::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'image' => 'nullable|string',
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $filename = time() . '_' . $file->getClientOriginalName();

            $path = Storage::disk('public')->putFileAs('images/testimonial', $file, $filename);

            $data['image'] = $path;
        }


        $testimonial = Testimonial::create($data);

        return response()->json($testimonial, 201);
    }

    public function show(Testimonial $testimonial)
    {
        return $testimonial;
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $data = $request->validate([
            'image' => 'nullable|string',
            'name' => 'sometimes|required|string|max:255',
            'designation' => 'sometimes|required|string|max:255',
            'message' => 'sometimes|required|string',
        ]);

        $testimonial->update($data);

        return response()->json($testimonial);
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return response()->json(['message' => 'Deleted successfully.']);
    }
}
