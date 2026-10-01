<?php

namespace App\Http\Controllers;

use App\Http\Requests\Account\StoreAccountRequest;
use App\Http\Requests\Account\UpdateAccountRequest;
use App\Models\Account;
use App\Models\AccountCategory;
use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::with('category')
            ->orderBy('code')
            ->get();

        $categories = AccountCategory::orderBy('type')
            ->orderBy('name')
            ->get();

        return view('accounts.index', compact('accounts', 'categories'));
    }

    public function store(StoreAccountRequest $request): JsonResponse
    {
        Account::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Account created successfully.',
        ]);
    }

    public function update(
        UpdateAccountRequest $request,
        Account $account
    ): JsonResponse {
        $account->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Account updated successfully.',
        ]);
    }

    public function destroy(Account $account): JsonResponse
    {
        if ($account->journalEntryLines()->exists()) {
            return response()->json([
                'message' => 'This account cannot be deleted because it has transactions.',
            ], 422);
        }

        $account->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully.',
        ]);
    }
}