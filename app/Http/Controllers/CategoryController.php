<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Colocation;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Colocation $colocation)
    {
        $categories = $colocation->categories()->withCount(['expenses' => fn($q) => $q->withTrashed()])->withTrashed()->orderBy('name')->get();

        return view('categories.index', compact('colocation', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Colocation $colocation)
    {
        return view('categories.create', compact('colocation'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request, Colocation $colocation)
    {
        Category::create([
            'name' => $request->validated('name'),
            'colocation_id' => $colocation->id,
        ]);

        return redirect()->route('colocations.show', $colocation)->with('status', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Colocation $colocation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return redirect()->route('categories.index', $category->colocation)->with('status', 'Category updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index', $category->colocation)->with('status', 'Category deleted.');
    }
}
