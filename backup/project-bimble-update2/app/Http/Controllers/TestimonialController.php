<?php
// app/Http/Controllers/TestimonialController.php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::all();
        return view('testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('testimonials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = $request->file('image')->store('public/testimonials');

        Testimonial::create([
            'name' => $request->name,
            'title' => $request->title,
            'message' => $request->message,
            'image_path' => str_replace('public/', '', $path),
        ]);

        return redirect()->route('testimonials.index')->with('success', 'Testimoni berhasil ditambahkan.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only('name', 'title', 'message');

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($testimonial->image_path) {
                Storage::delete('public/' . $testimonial->image_path);
            }
            $path = $request->file('image')->store('public/testimonials');
            $data['image_path'] = str_replace('public/', '', $path);
        }

        $testimonial->update($data);

        return redirect()->route('testimonials.index')->with('success', 'Testimoni berhasil diperbarui.');
    }

    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->image_path) {
            Storage::delete('public/' . $testimonial->image_path);
        }
        $testimonial->delete();
        return redirect()->route('testimonials.index')->with('success', 'Testimoni berhasil dihapus.');
    }
}
