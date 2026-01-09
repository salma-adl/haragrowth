<?php

namespace App\Http\Controllers;

use App\Models\ParameterSetting;
use Illuminate\Http\Request;

class ParameterSettingController extends Controller
{
    public function index()
    {
        $parameterSetting = ParameterSetting::all(); // Mengambil satu record pertama
        return $parameterSetting;
    }

    public function indexType($type)
    {
        $parameterSettings = ParameterSetting::where('type', $type)->get();
        return response()->json($parameterSettings);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            // 'title' => 'required|string|max:255',
            // 'description' => 'required|string|max:255',

        ]);

        return ParameterSetting::create($validatedData);
    }

    public function show($key)
    {
        $parameterSetting = ParameterSetting::where('key', $key)->firstOrFail(); 
    
        return $parameterSetting;
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $parameterSetting = ParameterSetting::findOrFail($id);
        $parameterSetting->update($validatedData);

        return $parameterSetting;
    }

    public function destroy($id)
    {
        ParameterSetting::destroy($id);
        return response()->noContent();
    }
}

