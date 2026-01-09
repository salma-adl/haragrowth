<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        // Mengambil semua data social media tanpa relasi
        $testimonials = Testimonial::all(); 
        foreach ($testimonials as $testimonial) {
            if ($testimonial->image) {
                $filePath = storage_path('app/public/' . $testimonial->image);
                
                if (file_exists($filePath)) {
                    $fileContents = file_get_contents($filePath);
                    $base64 = base64_encode($fileContents);
        
                    // Mendapatkan ekstensi dari filename
                    $extension = pathinfo($testimonial->image, PATHINFO_EXTENSION);
        
                    // Menentukan tipe MIME berdasarkan ekstensi
                    $mimeType = match (strtolower($extension)) {
                        'jpg', 'jpeg' => 'image/jpeg',
                        'png' => 'image/png',
                        'gif' => 'image/gif',
                        'svg' => 'image/svg+xml',
                        default => 'application/octet-stream',
                    };
        
                    $testimonial->image = "data:{$mimeType};base64," . $base64; 
                } else {
                    $testimonial->image = null;
                }
            }
        }
        
        return $testimonials;
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            // 'title' => 'required|string|max:255',
            // 'description' => 'required|string|max:255',

        ]);

        return Testimonial::create($validatedData);
    }

    public function show($id)
    {
        $testimonial = Testimonial::where('id', $id)->firstOrFail(); 
    
        return $testimonial;
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $testimonial = Testimonial::findOrFail($id);
        $testimonial->update($validatedData);

        return $testimonial;
    }

    public function destroy($id)
    {
        Testimonial::destroy($id);
        return response()->noContent();
    }
}

