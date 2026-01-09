<?php

namespace App\Http\Controllers;

use App\Models\Industry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IndustryController extends Controller
{
    public function index()
    {
        $industries = Industry::with(['industryPoints'])
            ->get();
        foreach ($industries as $industry) {
            if ($industry->attachment) {
                $filePath = storage_path('app/public/' . $industry->attachment);
                if (file_exists($filePath)) {
                    $industry->attachment = Storage::url($industry->attachment);
                } else {
                    $industry->attachment = null;
                }
            }
        }
        return $industries;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',

        ]);

        return Industry::create($validatedData);
    }

    public function show($name)
    {
        $industry = Industry::with(['industryPoints'])
            ->where('name', $name)
            ->firstOrFail();

        return $industry;
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $industry = Industry::findOrFail($id);
        $industry->update($validatedData);

        return $industry;
    }

    public function destroy($id)
    {
        Industry::destroy($id);
        return response()->noContent();
    }
}
