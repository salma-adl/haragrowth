<?php

namespace App\Http\Controllers;

use App\Models\Metric;
use Illuminate\Http\Request;

class MetricController extends Controller
{
    public function index()
    {
        $metrics = Metric::with(['metricPoints'])
            ->get();
        return $metrics;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            // 'title' => 'required|string|max:255',
            // 'description' => 'required|string|max:255',

        ]);

        return Metric::create($validatedData);
    }

    public function show($id)
    {
        $metric = Metric::with(['metricPoints'])
            ->where('id', $id)
            ->firstOrFail(); 
        
        return $metric;
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $metric = Metric::findOrFail($id);
        $metric->update($validatedData);

        return $metric;
    }

    public function destroy($id)
    {
        Metric::destroy($id);
        return response()->noContent();
    }
}

