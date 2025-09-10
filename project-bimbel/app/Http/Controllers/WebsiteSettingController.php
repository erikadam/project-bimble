<?php

namespace App\Http\Controllers;

use App\Models\SliderImage;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use App\Models\CompanyGoal;
class WebsiteSettingController extends Controller
{
    public function index()
    {
        $sliderImages = SliderImage::all();
        return view('pengaturan.index', compact('sliderImages'));
    }

    public function storeSliderImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $path = $request->file('image')->store('public/sliders');

        SliderImage::create([
            'path' => str_replace('public/', '', $path)
        ]);

        return back()->with('success', 'Gambar slider berhasil diunggah.');
    }

    public function destroySliderImage(SliderImage $image)
    {

        $image->delete();

        return back()->with('success', 'Gambar slider berhasil dihapus.');
    }

    /**
     * Menampilkan halaman utama dengan data yang diperlukan.
     */
    public function showHomePage()
    {
        $testimonials = Testimonial::all();
        $sliderImages = SliderImage::all();
        $visi = CompanyGoal::where('type', 'visi')->get();
        $misi = CompanyGoal::where('type', 'misi')->get();
        $tujuan = CompanyGoal::where('type', 'tujuan')->get();
        return view('welcome', compact('sliderImages', 'testimonials', 'visi', 'misi', 'tujuan'));
    }
}
