<?php

namespace App\Http\Controllers;

use App\Models\sampel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class sampelController extends Controller
{
    public function index()
    {
        $sampel = sampel::latest()->get();
        return Inertia::render('sampel/index', [
            'sampel' => $sampel
        ]);
    }

    public function create()
    {
        return Inertia::render('sampel/create');
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         ''
    //     ])
    // }

    public function show()
    {
        
    }

    public function edit()
    {
        
    }

    public function update()
    {
        
    }

    public function delete()
    {
        
    }
}
