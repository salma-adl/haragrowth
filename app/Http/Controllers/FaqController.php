<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::with(['faqPoints'])
            ->get();
        return $faqs;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',

        ]);

        return Faq::create($validatedData);
    }

    public function show($name)
    {
        $faq = Faq::with(['faqPoints'])
            ->where('name', $name)
            ->firstOrFail(); 
        
        return $faq;
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $faq = Faq::findOrFail($id);
        $faq->update($validatedData);

        return $faq;
    }

    public function destroy($id)
    {
        Faq::destroy($id);
        return response()->noContent();
    }
}

