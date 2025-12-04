<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Хэрэв танд Category model байгаа бол доорх мөрийг uncomment хийгээрэй
// use App\Models\Category; 

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        // 1. Шалгах (Validation)
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // 2. Хадгалах (Жишээ код)
        // Category::create(['name' => $request->name]);

        // 3. Буцаах
        return redirect()->back()->with('success', 'Амжилттай хадгалагдлаа!'); 
    }
}