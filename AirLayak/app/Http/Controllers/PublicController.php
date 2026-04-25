<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function home(): View
    {
        return view('public.home');
    }
    
    public function heatmap(): View
    {
        return view('public.heatmap');
    }
    
    public function reportForm(): View
    {
        return view('public.report-form');
    }
    
    public function submitReport(Request $request)
    {
        // TODO: Implement
        return back();
    }
    
    public function reportSuccess(string $token): View
    {
        return view('public.report-success', compact('token'));
    }
    
    public function guides(): View
    {
        return view('public.guides');
    }
    
    public function guideDetail(string $category, ?string $source = null): View
    {
        return view('public.guide-detail', compact('category', 'source'));
    }
    
    public function confirmForm(string $token): View
    {
        return view('public.confirm-form', compact('token'));
    }
    
    public function confirmReport(Request $request, string $token)
    {
        return back();
    }
    
    public function extensionForm(string $token): View
    {
        return view('public.extension-form', compact('token'));
    }
    
    public function extensionRespond(Request $request, string $token)
    {
        return back();
    }
}