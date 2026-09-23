<?php

namespace App\Http\Controllers;

use App\Http\Requests\Warehouse\StoreWarehouseRequest;
use App\Http\Requests\Warehouse\UpdateWarehouseRequest;
use App\Models\Warehouse;
use Illuminate\Http\JsonResponse;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::latest()->paginate(10);

        return view('warehouses.index', compact('warehouses'));
    }

    public function store(StoreWarehouseRequest $request): JsonResponse
    {
        Warehouse::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Warehouse created successfully.',
        ], 201);
    }

    public function update(
        UpdateWarehouseRequest $request,
        Warehouse $warehouse
    ): JsonResponse {
        $warehouse->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Warehouse updated successfully.',
        ]);
    }

    public function destroy(Warehouse $warehouse)
    {
        try {
            $warehouse->delete();

            return redirect()
                ->route('warehouses.index')
                ->with('success', 'Warehouse deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()
                ->route('warehouses.index')
                ->with('error', 'Unable to delete warehouse.');
        }
    }
}
