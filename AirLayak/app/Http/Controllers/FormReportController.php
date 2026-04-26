<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormReportController extends Controller
{
        public function create()
    {
        return view('formlaporan');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category'     => 'required|in:bau,warna,sakit_perut,rasa_aneh,lainnya',
            'area_id'      => 'required|exists:areas,id',
            'water_sources'=> 'nullable|array',
            'description'  => 'nullable|string|max:500',
            'photo_path'   => 'nullable|image|max:5120',
        ]);

        return redirect()->route('homepage');
    }
}
