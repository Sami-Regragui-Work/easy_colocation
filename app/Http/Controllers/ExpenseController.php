<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Category;
use App\Models\Colocation;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Colocation $colocation, Category $category)
    {
        $query = Expense::with(['payer', 'category'])->where('category_id', $category->id);

        if ($request->filled('month')) {
            $query->whereMonth('start_at', $request->month);
        }

        $expenses = $query->latest('start_at')->paginate(3);

        return view('expenses.index', compact('colocation', 'category', 'expenses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Colocation $colocation, Category $category)
    {
        $members = $colocation->members;
        return view('expenses.create', compact('colocation', 'category', 'members'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseRequest $request, Colocation $colocation, Category $category)
    {
        Expense::create($request->validated());

        return redirect()->route('colocations.categories.expenses.index', [$colocation, $category])->with('status', 'Expense created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Colocation $colocation, Category $category, Expense $expense)
    {
        return view('expenses.show', compact('colocation', 'category', 'expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Colocation $colocation, Category $category, Expense $expense)
    {
        $members = $colocation->members;
        return view('expenses.edit', compact('colocation', 'category', 'expense', 'members'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseRequest $request, Colocation $colocation, Category $category, Expense $expense)
    {
        $expense->update($request->validated());
        return redirect()->route('colocations.categories.expenses.index', [$colocation, $category])->with('status', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Colocation $colocation, Category $category, Expense $expense)
    {
        $expense->delete();
        return redirect()->route('colocations.categories.expenses.index', [$colocation, $category])->with('status', 'Expense deleted.');
    }
}
