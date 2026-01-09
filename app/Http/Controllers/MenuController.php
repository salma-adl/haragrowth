<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index($type)
    {
        $menus = Menu::with(['route', 'subMenus.route'])
            ->where('type', $type)
            ->where('is_active', true)
            // Mengurutkan berdasarkan index menu terlebih dahulu
            ->orderBy('index', 'asc')  // Mengurutkan berdasarkan index
            ->get();
        
        // Mengubah ikon subMenu menjadi format base64 dan mengurutkan subMenu
        foreach ($menus as $menu) {
            // Mengurutkan subMenus berdasarkan index, dan jika duplikat, berdasarkan name
            $menu->subMenus = $menu->subMenus->sortBy(function($subMenu) {
                return [$subMenu->index, $subMenu->name];  // Sort by index first, then name
            });
            
            // Proses konversi ikon subMenu menjadi format base64
            foreach ($menu->subMenus as $subMenu) {
                if ($subMenu->icon) {
                    $filePath = storage_path('app/public/' . $subMenu->icon);
                    
                    if (file_exists($filePath)) {
                        $fileContents = file_get_contents($filePath);
                        $base64 = base64_encode($fileContents);
            
                        // Mendapatkan ekstensi dari filename
                        $extension = pathinfo($subMenu->icon, PATHINFO_EXTENSION);
            
                        // Menentukan tipe MIME berdasarkan ekstensi
                        $mimeType = match (strtolower($extension)) {
                            'jpg', 'jpeg' => 'image/jpeg',
                            'png' => 'image/png',
                            'gif' => 'image/gif',
                            'svg' => 'image/svg+xml',
                            default => 'application/octet-stream',
                        };
            
                        $subMenu->icon = "data:{$mimeType};base64," . $base64; 
                    } else {
                        $subMenu->icon = null;
                    }
                }
            }
        }
    
        return $menus;
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

        return Menu::create($validatedData);
    }

    // public function show($id)
    // {
    //     $menu = Menu::with(['subMenus'])->findOrFail($id);
        
    //     if ($menu->attachment) {
    //         $filePath = storage_path('app/public/' . $blog->attachment);
            
    //         if (file_exists($filePath)) {
    //             $fileContents = file_get_contents($filePath);
    //             $base64 = base64_encode($fileContents);
    //             $blog->attachment = 'data:application/octet-stream;base64,' . $base64;
    //         } else {
    //             $blog->attachment = null;
    //         }
    //     }
    
    //     return $menu;
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

        $blog = Menu::findOrFail($id);
        $blog->update($validatedData);

        return $blog;
    }

    public function destroy($id)
    {
        Menu::destroy($id);
        return response()->noContent();
    }
}

