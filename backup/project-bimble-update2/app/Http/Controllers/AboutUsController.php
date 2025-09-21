<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutUsController extends Controller
{
    public function index()
    {
        // Kita hanya akan mengizinkan satu entri "About Us" untuk saat ini.
        // Jika belum ada, arahkan untuk membuat. Jika sudah ada, arahkan untuk mengedit.
        $aboutUs = AboutUs::first();
        if ($aboutUs) {
            return view('about-us.edit', compact('aboutUs'));
        }
        return view('about-us.create');
    }

    public function create()
    {
        // Jika sudah ada data, jangan biarkan membuat lagi.
        if (AboutUs::count() > 0) {
            return redirect()->route('about-us.index')->with('error', 'Halaman "About Us" sudah ada, Anda hanya bisa mengeditnya.');
        }
        return view('about-us.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->file('image')->store('about-us-images', 'public');

        AboutUs::create([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('about-us.index')->with('success', 'Informasi "About Us" berhasil disimpan.');
    }

    public function edit(AboutUs $aboutUs)
    {
        return view('about-us.edit', compact('aboutUs'));
    }

    public function update(Request $request, AboutUs $aboutUs)
{

    // DIUBAH: Gunakan request() helper untuk validasi yang lebih ringkas.
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // DITAMBAH: Ambil path gambar yang sudah ada sebagai default.
    $imagePath = $aboutUs->image_path;

    // DITAMBAH: Pengecekan apakah ada file baru yang diunggah.
    if ($request->hasFile('image')) {
        // Hapus gambar lama jika ada
        if ($aboutUs->image_path) {
            Storage::disk('public')->delete($aboutUs->image_path);
        }
        // Simpan gambar baru
        $imagePath = $request->file('image')->store('about-us-images', 'public');
    }

    // DIUBAH: Gunakan data yang telah divalidasi dan path gambar yang diperbarui
    $aboutUs->update([
        'title' => $validatedData['title'],
        'description' => $validatedData['description'],
        'image_path' => $imagePath,
    ]);

    return redirect()->route('about-us.index')->with('success', 'Informasi "About Us" berhasil diperbarui.');
}
}
