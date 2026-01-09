<?php

namespace App\Http\Controllers;

use App\Models\ImageSetting;
use Illuminate\Http\Request;

class ImageSettingController extends Controller
{
    public function index($type)
    {
        $imageSettings = ImageSetting::where('type', $type)->get();
        
        foreach ($imageSettings as $setting) {
            if ($setting->is_dark_mode) {
                $setting->attachment = $setting->dark_attachment;
            }
        }
    
        return response()->json($imageSettings);
    }

    public function indexLogo()
    {
        // Retrieve the image settings for 'industry-logo'
        $imageSettings = ImageSetting::where('type', 'industry-logo')->get();

                // Convert 'attachment' and 'dark_attachment' to Base64 if they are png or svg
                foreach ($imageSettings as $setting) {
                    foreach (['attachment', 'dark_attachment'] as $field) {
                        if ($setting->$field) {
                            $filePath = storage_path('app/public/' . $setting->$field);
                            
                            if (file_exists($filePath)) {
                                $fileContents = file_get_contents($filePath);
                                $base64 = base64_encode($fileContents);
        
                                // Mendapatkan ekstensi dari filename
                                $extension = pathinfo($setting->$field, PATHINFO_EXTENSION);
        
                                // Menentukan tipe MIME berdasarkan ekstensi
                                $mimeType = match (strtolower($extension)) {
                                    'png' => 'image/png',
                                    'svg' => 'image/svg+xml',
                                    default => null,
                                };
        
                                // Set the Base64 data only for PNG and SVG
                                if ($mimeType) {
                                    $setting->$field = "data:{$mimeType};base64," . $base64;
                                } else {
                                    $setting->$field = null; // or handle other formats if necessary
                                }
                            } else {
                                $setting->$field = null;
                            }
                        }
                    }
                }

        // Get the count of the retrieved items
        // $count = $imageSettings->count();

        // // If there are fewer than 5 items, duplicate items from the beginning
        // if ($count < 5) {
        //     $needed = 5 - $count;
        //     $imageSettings = $imageSettings->concat($imageSettings->take($needed));
        // }

        // // Limit the result to 5 items
        // $imageSettings = $imageSettings->take(5);

        return response()->json($imageSettings);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
        ]);

        return ImageSetting::create($validatedData);
    }

    public function show($key)
    {
        $imageSetting = ImageSetting::where('key', $key)->firstOrFail();
    
        // Adjust the attachment field based on is_dark_mode
        if ($imageSetting->is_dark_mode) {
            $imageSetting->attachment = $imageSetting->dark_attachment;
        }
    
        // Convert the attachment to base64
        $fieldsToConvert = ['attachment', 'dark_attachment'];
        foreach ($fieldsToConvert as $field) {
            if ($imageSetting->$field) {
                $filePath = storage_path('app/public/' . $imageSetting->$field);
                
                if (file_exists($filePath)) {
                    $fileContents = file_get_contents($filePath);
                    $base64 = base64_encode($fileContents);
    
                    // Get the file extension
                    $extension = pathinfo($imageSetting->$field, PATHINFO_EXTENSION);
    
                    // Determine MIME type based on the extension
                    $mimeType = match (strtolower($extension)) {
                        'png' => 'image/png',
                        'svg' => 'image/svg+xml',
                        default => null,
                    };
    
                    // Set the Base64 data only for supported formats
                    if ($mimeType) {
                        $imageSetting->$field = "data:{$mimeType};base64," . $base64;
                    } else {
                        $imageSetting->$field = null; // Handle unsupported formats if necessary
                    }
                } else {
                    $imageSetting->$field = null; // Handle missing files
                }
            }
        }
    
        return response()->json($imageSetting);
    }
    

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $imageSetting = ImageSetting::findOrFail($id);
        $imageSetting->update($validatedData);

        return $imageSetting;
    }

    public function destroy($id)
    {
        ImageSetting::destroy($id);
        return response()->noContent();
    }
}

