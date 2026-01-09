<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with(['category', 'tags'])
            ->where('is_published', true)
            ->get();
    
        foreach ($blogs as $blog) {
            if ($blog->attachment) {
                $filePath = storage_path('app/public/' . $blog->attachment);

                if (file_exists($filePath)) {
                    $blog->attachment = Storage::url($blog->attachment);
                } else {
                    $blog->attachment = null;
                }
            }
        }
    
        return $blogs;
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

        return Blog::create($validatedData);
    }

    public function show($id)
    {
        $blog = Blog::with(['category', 'tags', 'blogComments.customer'])->findOrFail($id);
        
        if ($blog->attachment) {
            $filePath = storage_path('app/public/' . $blog->attachment);
            
            if (file_exists($filePath)) {
                $blog->attachment = Storage::url($blog->attachment);
            } else {
                $blog->attachment = null;
            }
        }
    
        return $blog;
    }

    

    public function showImage($id)
    {
        $blog = Blog::findOrFail($id);
        
        if ($blog->attachment) {
            $filePath = storage_path('app/public/' . $blog->attachment);
            
            if (file_exists($filePath)) {
                $blog->attachment = Storage::url($blog->attachment);
            } else {
                $blog->attachment = null;
            }
        }
    
        return $blog;
    }

    public function getLatestBlogs($total)
    {
        // Mengambil blog terbaru berdasarkan tanggal terbaru yang sudah dipublikasikan
        $blogs = Blog::with(['category', 'tags', 'blogComments'])
                    ->where('is_published', true) // Hanya ambil blog yang dipublikasikan
                    ->orderBy('created_at', 'desc') // Mengurutkan berdasarkan tanggal terbaru
                    ->take($total) // Ambil sejumlah data sesuai parameter $total
                    ->get();

        // Memeriksa apakah ada attachment, dan memperbarui URL-nya jika ada
        foreach ($blogs as $blog) {
            if ($blog->attachment) {
                $filePath = storage_path('app/public/' . $blog->attachment);
                
                if (file_exists($filePath)) {
                    $blog->attachment = Storage::url($blog->attachment); // Perbarui URL attachment
                } else {
                    $blog->attachment = null; // Set null jika file tidak ada
                }
            }
        }

        return $blogs;
    }


    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'attachment' => 'nullable|string',
            'content' => 'required|string',
            'is_published' => 'boolean',
        ]);

        $blog = Blog::findOrFail($id);
        $blog->update($validatedData);

        return $blog;
    }

    public function showAdjacentBlogs($id)
    {
        // Ambil data blog berdasarkan ID yang diberikan
        $blog = Blog::with(['category', 'tags', 'blogComments'])->findOrFail($id);
    
        // Ambil semua blog tanpa filter
        $allBlogs = Blog::orderBy('created_at')->get();
        
        // Temukan posisi index blog yang diminta
        $currentIndex = $allBlogs->search(function ($item) use ($id) {
            return $item->id == $id;
        });
    
        // Siapkan nilai default untuk previous dan next
        $previousBlog = null;
        $nextBlog = null;
    
        if ($currentIndex === 0) {
            $nextBlog = $allBlogs->get($currentIndex + 1);
        } 
        elseif ($currentIndex === $allBlogs->count() - 1) {
            $previousBlog = $allBlogs->get($currentIndex - 1);
        } 
        else {
            $previousBlog = $allBlogs->get($currentIndex - 1);
            $nextBlog = $allBlogs->get($currentIndex + 1);
        }
    
        return [
            'previous' => $previousBlog ? $this->prepareBlogData($previousBlog) : (object)[],
            'next' => $nextBlog ? $this->prepareBlogData($nextBlog) : (object)[],
        ];
    }
    
    private function prepareBlogData($blog)
    {
        if ($blog->attachment) {
            $filePath = storage_path('app/public/' . $blog->attachment);
            
            if (file_exists($filePath)) {
                $blog->attachment =  Storage::url($blog->attachment);
            } else {
                $blog->attachment = null;
            }
        }
    
        return $blog;
    }
    
    public function getBlogsByTag($tagId)
    {
        // Validasi apakah tag dengan ID tersebut ada
        $tag = \App\Models\Tag::findOrFail($tagId);
    
        // Ambil semua blog yang terkait dengan tag yang diberikan
        $blogs = Blog::with(['category', 'tags'])
            ->whereHas('tags', function ($query) use ($tagId) {
                $query->where('tags.id', $tagId);
            })
            ->where('is_published', true) // Pastikan hanya blog yang dipublikasikan yang diambil
            ->get();
    
        // Menambahkan logika untuk encoding attachment ke base64 jika ada
        foreach ($blogs as $blog) {
            if ($blog->attachment) {
                $filePath = storage_path('app/public/' . $blog->attachment);
                if (file_exists($filePath)) {
                    $blog->attachment = Storage::url($blog->attachment);
                } else {
                    $blog->attachment = null;
                }
                
                // if (file_exists($filePath)) {
                //     $fileContents = file_get_contents($filePath);
                //     $extension = pathinfo($blog->attachment, PATHINFO_EXTENSION);
                    
                //     // Menentukan MIME type berdasarkan ekstensi
                //     $mimeType = '';
                //     switch (strtolower($extension)) {
                //         case 'jpg':
                //         case 'jpeg':
                //             $mimeType = 'image/jpeg';
                //             break;
                //         case 'png':
                //             $mimeType = 'image/png';
                //             break;
                //         case 'gif':
                //             $mimeType = 'image/gif';
                //             break;
                //         case 'bmp':
                //             $mimeType = 'image/bmp';
                //             break;
                //         case 'webp':
                //             $mimeType = 'image/webp';
                //             break;
                //     }
    
                //     // Jika MIME type ditemukan, encode ke base64
                //     if ($mimeType) {
                //         $base64 = base64_encode($fileContents);
                //         $blog->attachment = 'data:' . $mimeType . ';base64,' . $base64;
                //     } else {
                //         $blog->attachment = null; // Jika ekstensi tidak valid
                //     }
                // } else {
                //     $blog->attachment = null; // Jika file tidak ditemukan
                // }
            }
        }
    
        return response()->json($blogs);
    }
    
    public function getBlogsByCategory($categoryId)
{
    // Validasi apakah kategori dengan ID tersebut ada
    $category = \App\Models\Category::findOrFail($categoryId);

    // Ambil semua blog yang terkait dengan category yang diberikan
    $blogs = Blog::with(['category', 'tags']) // Memuat relasi category dan tags
        ->where('category_id', $categoryId) // Filter berdasarkan category_id
        ->where('is_published', true) // Pastikan hanya blog yang dipublikasikan yang diambil
        ->get();

    // Menambahkan logika untuk encoding attachment ke base64 jika ada
    foreach ($blogs as $blog) {
        if ($blog->attachment) {
            $filePath = storage_path('app/public/' . $blog->attachment);
            if (file_exists($filePath)) {
                $blog->attachment = Storage::url($blog->attachment);
            } else {
                $blog->attachment = null;
            }

        }
    }

    return response()->json($blogs);
    }


    public function destroy($id)
    {
        Blog::destroy($id);
        return response()->noContent();
    }
}

