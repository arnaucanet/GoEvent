<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\StorePostRequest;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all(); // Show all categories
        return $categories;
    }

    public function show(Category $category)
    {
        return $category; // Shows the category
    }

    public function destroy(Category $category)
    {
        $category->delete(); // Deletes the category
    }

    public function store(Request $request)
    {
        //$this->authorize('post-edit');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'description' => ['required', 'string', 'min:2'],
            'icon' => ['nullable', 'string'],
            'active' => ['boolean'],
        ]);

        //$data['user_id'] =  auth()->user()->id;
        $category = Category::create($data); // Creates the category
        return $category;
    }

    public function update(Request $request, Category $category)
    {
        // $this->authorize('category-edit');

        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255', 'min:2'],
            'description' => ['sometimes', 'required', 'string', 'min:2'],
            'icon' => ['sometimes', 'nullable', 'string'],
            'active' => ['sometimes', 'boolean'],
        ]);

        $category->update($data); // Updates the category

        return $category;
    }
}
