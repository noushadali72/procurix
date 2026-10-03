<?php
namespace App\Services;

use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\StockMovement;
use App\Models\Unit;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class StockMovementService
{

    public function material(RawMaterial $material, Unit $unit, string $direction, string $type, float|int $qty, ?Model $reference = null, ?string $notes = null)
    : StockMovement {
        return $this->record([
            'raw_material_id' => $material->id,
            'unit_id' => $unit->id,
            'direction' => $direction,
            'qty' => $qty,
            'type' => $type,
            'notes' => $notes,
        ], $reference);
    }

    public function product(Product $product, Unit $unit, string $direction, string $type, float|int $qty, ?Model $reference = null, ?string $notes = null)
    : StockMovement {
        return $this->record([
            'product_id' => $product->id,
            'unit_id' => $unit->id,
            'direction' => $direction,
            'qty' => $qty,
            'type' => $type,
            'notes' => $notes,
        ], $reference);
    }

    public function record(array $data = [], ?Model $reference = null): StockMovement
    {

        $data['created_by'] = Auth::id();
        try {
            $movement = StockMovement::create([
                'product_id' => $data['product_id'] ?? null,
                'raw_material_id' => $data['raw_material_id'] ?? null,
                'unit_id' => $data['unit_id'],
                'qty' => $data['qty'],
                'type'=>$data['type'],
                'direction' => $data['direction'],
                'notes' => $data['notes']??null,
                'created_by' => $data['created_by'],
            ]);

            if ($reference) {
                $movement->reference()->associate($reference);
                $movement->save();
                }
            return $movement;
        } catch (Exception $e) {
            throw new RuntimeException("Unable to record stock movement: " . $e->getMessage());
        }
    }
}
