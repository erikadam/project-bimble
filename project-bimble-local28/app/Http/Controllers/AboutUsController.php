<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use App\Models\AboutUsImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutUsController extends Controller
{
    /**
     * Metode ini akan menangani rute index dan mengarahkan ke halaman yang benar.
     * Ini untuk memperbaiki error "undefined method index()".
     */
    public function index()
    {
        $aboutUs = AboutUs::first();
        if ($aboutUs) {
            // Jika data "About Us" sudah ada, arahkan ke halaman edit.
            return redirect()->route('about-us.edit', $aboutUs);
        }
        // Jika belum ada, arahkan ke halaman untuk membuat baru.
        return redirect()->route('about-us.create');
    }

    /**
     * Menampilkan form untuk membuat data "About Us" baru.
     */
    public function create()
    {
        $aboutUs = AboutUs::first();
        if ($aboutUs) {
            // Mencegah pembuatan data baru jika sudah ada.
            return redirect()->route('about-us.edit', $aboutUs)->with('info', 'Halaman "About Us" sudah ada, Anda hanya bisa mengeditnya.');
        }
        return view('about-us.create');
    }

    /**
     * Menyimpan data "About Us" yang baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $aboutUs = AboutUs::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('about-us-sliders', 'public');
                $aboutUs->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('dashboard')->with('success', 'Informasi "About Us" berhasil dibuat.');
    }

    public function edit(AboutUs $aboutUs)
    {
        $aboutUs->load('images');
        return view('about-us.edit', compact('aboutUs'));
    }

    public function update(Request $request, AboutUs $aboutUs)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:about_us_images,id'
        ]);

        $aboutUs->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        if ($request->has('delete_images')) {
            foreach ($validated['delete_images'] as $imageId) {
                $image = AboutUsImage::find($imageId);
                if ($image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('about-us-sliders', 'public');
                $aboutUs->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('dashboard')->with('success', 'Informasi "About Us" berhasil diperbarui.');
    }
}
