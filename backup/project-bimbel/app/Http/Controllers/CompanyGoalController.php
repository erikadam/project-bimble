<?php
// app/Http/Controllers/CompanyGoalController.php

namespace App\Http\Controllers;

use App\Models\CompanyGoal;
use Illuminate\Http\Request;

class CompanyGoalController extends Controller
{
    public function index()
    {
        $visi = CompanyGoal::where('type', 'visi')->get();
        $misi = CompanyGoal::where('type', 'misi')->get();
        $tujuan = CompanyGoal::where('type', 'tujuan')->get();
        return view('company-goals.index', compact('visi', 'misi', 'tujuan'));
    }

    public function create()
    {
        return view('company-goals.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:visi,misi,tujuan',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        CompanyGoal::create($request->all());
        return redirect()->route('company-goals.index')->with('success', 'Poin berhasil ditambahkan.');
    }

    public function edit(CompanyGoal $companyGoal)
    {
        return view('company-goals.edit', ['goal' => $companyGoal]);
    }

    public function update(Request $request, CompanyGoal $companyGoal)
    {
        $request->validate([
            'type' => 'required|in:visi,misi,tujuan',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        $companyGoal->update($request->all());
        return redirect()->route('company-goals.index')->with('success', 'Poin berhasil diperbarui.');
    }

    public function destroy(CompanyGoal $companyGoal)
    {
        $companyGoal->delete();
        return redirect()->route('company-goals.index')->with('success', 'Poin berhasil dihapus.');
    }
}
