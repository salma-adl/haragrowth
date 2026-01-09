<?php

namespace App\Http\Controllers;

use App\Models\Compliance;
use Illuminate\Http\Request;

class ComplianceController extends Controller
{
    public function index()
    {
        $compliances = Compliance::all();
        return $compliances;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            // 'title' => 'required|string|max:255',
            // 'description' => 'required|string|max:255',

        ]);

        return Compliance::create($validatedData);
    }

    public function show($id)
    {
        $compliance = Compliance::where('id', $id)->firstOrFail(); 
    
        return $compliance;
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $compliance = Compliance::findOrFail($id);
        $compliance->update($validatedData);

        return $compliance;
    }

    public function destroy($id)
    {
        Compliance::destroy($id);
        return response()->noContent();
    }
}

