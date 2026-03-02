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
        $categories = $colocation->categories()->withCount(['expenses'])->orderBy('name')->paginate(5);

        return view('categories.index', compact('colocation', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Colocation $colocation)
    {
        $suggestions = Category::getNameSuggestions();

        return view('categories.create', compact('colocation', 'suggestions'));
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

        return redirect()->route('colocations.categories.index', $colocation)->with('status', 'Category created successfully.');
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
    public function edit(Colocation $colocation, Category $category)
    {
        return view('categories.edit', compact('colocation', 'category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Colocation $colocation, Category $category)
    {
        $category->update($request->validated());

        return redirect()->route('colocations.categories.index', $colocation)->with('status', 'Category updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Colocation $colocation, Category $category)
    {
        $category->delete();

        return redirect()->route('colocations.categories.index', $colocation)->with('status', 'Category deleted.');
    }
}
