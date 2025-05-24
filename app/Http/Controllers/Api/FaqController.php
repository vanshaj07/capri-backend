<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        return response()->json(Faq::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
            'type'     => 'nullable|string|max:100',
        ]);

        $faq = Faq::create($data);
        return response()->json($faq, 201);
    }

    public function show(Faq $faq)
    {
        return response()->json($faq);
    }

    public function update(Request $request, Faq $faq)
    {
        $data = $request->validate([
            'question' => 'sometimes|required|string|max:255',
            'answer'   => 'sometimes|required|string',
            'type'     => 'nullable|string|max:100',
        ]);

        $faq->update($data);
        return response()->json($faq);
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return response()->json(['message' => 'FAQ deleted successfully.']);
    }
}
