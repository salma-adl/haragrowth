<?php

namespace App\Http\Controllers;

use App\Models\SocialMedia;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    public function index()
    {
        // Mengambil semua data social media tanpa relasi
        $socialMedias = SocialMedia::all(); 
        foreach ($socialMedias as $socialMedia) {
            if ($socialMedia->logo) {
                $filePath = storage_path('app/public/' . $socialMedia->logo);
                
                if (file_exists($filePath)) {
                    $fileContents = file_get_contents($filePath);
                    $base64 = base64_encode($fileContents);
        
                    // Mendapatkan ekstensi dari filename
                    $extension = pathinfo($socialMedia->logo, PATHINFO_EXTENSION);
        
                    // Menentukan tipe MIME berdasarkan ekstensi
                    $mimeType = match (strtolower($extension)) {
                        'jpg', 'jpeg' => 'image/jpeg',
                        'png' => 'image/png',
                        'gif' => 'image/gif',
                        'svg' => 'image/svg+xml',
                        default => 'application/octet-stream',
                    };
        
                    $socialMedia->logo = "data:{$mimeType};base64," . $base64; 
                } else {
                    $socialMedia->logo = null;
                }
            }
        }        
        return $socialMedias;
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            // 'title' => 'required|string|max:255',
            // 'description' => 'required|string|max:255',

        ]);

        return SocialMedia::create($validatedData);
    }

    public function show($id)
    {
        $socialMedia = SocialMedia::where('id', $id)->firstOrFail(); 
    
        return $socialMedia;
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $socialMedia = SocialMedia::findOrFail($id);
        $socialMedia->update($validatedData);

        return $socialMedia;
    }

    public function destroy($id)
    {
        SocialMedia::destroy($id);
        return response()->noContent();
    }
}

