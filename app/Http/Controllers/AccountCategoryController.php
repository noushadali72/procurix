<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountCategory\StoreAccountCategoryRequest;
use App\Http\Requests\AccountCategory\UpdateAccountCategoryRequest;
use App\Models\AccountCategory;
use Illuminate\Http\JsonResponse;

class AccountCategoryController extends Controller
{
    public function index()
    {
        $categories = AccountCategory::withCount('accounts')
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        return view('account_categories.index', compact('categories'));
    }

    public function store(StoreAccountCategoryRequest $request): JsonResponse
    {
        AccountCategory::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Account category created successfully.',
        ]);
    }

    public function update(
        UpdateAccountCategoryRequest $request,
        AccountCategory $accountCategory
    ): JsonResponse {
        $accountCategory->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Account category updated successfully.',
        ]);
    }

    public function destroy(AccountCategory $accountCategory): JsonResponse
    {
        if ($accountCategory->accounts()->exists()) {
            return response()->json([
                'message' => 'This account category cannot be deleted because it has accounts.',
            ], 422);
        }

        $accountCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account category deleted successfully.',
        ]);
    }
}