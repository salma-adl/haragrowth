<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RouteController extends Controller
{
    public function index($routeName)
    {
        $menus = Route::with(['metaTag'])
            ->where('route_name', $routeName)
            ->get();
        
            $menus->each(function ($menu) {
                if ($menu->metaTag) {
                    if ($menu->metaTag->og_image) {
                        $menu->metaTag->og_image = Storage::url($menu->metaTag->og_image);
                    }
                    if ($menu->metaTag->twitter_image) {
                        $menu->metaTag->twitter_image = Storage::url($menu->metaTag->twitter_image);
                    }
                }
            });
        return response()->json($menus);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'attachment' => 'nullable|string', // atau 'file' jika file upload
            'content' => 'required|string',
            'is_published' => 'boolean',
        ]);

        return Route::create($validatedData);
    }

    // public function show($id)
    // {
    //     $Route = Route::with(['subMenus'])->findOrFail($id);
        
    //     if ($Route->attachment) {
    //         $filePath = storage_path('app/public/' . $blog->attachment);
            
    //         if (file_exists($filePath)) {
    //             $fileContents = file_get_contents($filePath);
    //             $base64 = base64_encode($fileContents);
    //             $blog->attachment = 'data:application/octet-stream;base64,' . $base64;
    //         } else {
    //             $blog->attachment = null;
    //         }
    //     }
    
    //     return $Route;
    // }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'attachment' => 'nullable|string',
            'content' => 'required|string',
            'is_published' => 'boolean',
        ]);

        $blog = Route::findOrFail($id);
        $blog->update($validatedData);

        return $blog;
    }

    public function destroy($id)
    {
        Route::destroy($id);
        return response()->noContent();
    }
}

