<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::all()->map(function ($blog) {
            return $this->formatBlog($blog);
        });

        return response()->json($blogs);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'heading' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'detail_description' => 'nullable|string',
            'detail_image' => 'nullable|image|max:2048',
            'type' => 'nullable|string|max:100',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = Storage::disk('public')->put('images/blogs', $request->file('image'));
        }

        if ($request->hasFile('detail_image')) {
            $data['detail_image'] = Storage::disk('public')->put('images/blogs/details', $request->file('detail_image'));
        }

        $blog = Blog::create($data);

        return response()->json($this->formatBlog($blog), 201);
    }

    public function show(Blog $blog)
    {
        return response()->json($this->formatBlog($blog));
    }

    public function update(Request $request, Blog $blog)
    {
        $data = $request->validate([
            'heading' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'image' => 'nullable|image|max:2048',
            'detail_description' => 'nullable|string',
            'detail_image' => 'nullable|image|max:2048',
            'type' => 'nullable|string|max:100',
        ]);

        if ($request->hasFile('image')) {
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }
            $data['image'] = Storage::disk('public')->put('images/blogs', $request->file('image'));
        }

        if ($request->hasFile('detail_image')) {
            if ($blog->detail_image && Storage::disk('public')->exists($blog->detail_image)) {
                Storage::disk('public')->delete($blog->detail_image);
            }
            $data['detail_image'] = Storage::disk('public')->put('images/blogs/details', $request->file('detail_image'));
        }

        $blog->update($data);

        return response()->json($this->formatBlog($blog));
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();

        return response()->json(['message' => 'Blog deleted successfully.']);
    }

    private function formatBlog(Blog $blog)
    {
        return [
            'id' => $blog->id,
            'heading' => $blog->heading,
            'description' => $blog->description,
            'image' => $blog->image ? asset('storage/' . $blog->image) : null,
            'detail_description' => $blog->detail_description,
            'detail_image' => $blog->detail_image ? asset('storage/' . $blog->detail_image) : null,
            'type' => $blog->type,
            'created_at' => $blog->created_at,
            'updated_at' => $blog->updated_at,
        ];
    }
}
