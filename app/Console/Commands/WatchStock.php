<?php

namespace App\Console\Commands;

use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use App\Models\RawMaterial;
use Exception;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('stock:watch')]
#[Description('This command is used to continuosly monitor the stocks if any raw materials stocks runs out and hits the minimum stock levels it will auto generate Purchase request.')]
class WatchStock extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('watching stocks...');
        $raw_materials = RawMaterial::with('unit')->whereColumn('stock', '<=', 'minimum_stock')->get();
       
        foreach ($raw_materials as $material) {
            try {
                $exists = PurchaseRequest::whereIn('status',['draft','pending','active'])->whereHas('items',function($query) use($material){
                    $query->where('raw_material_id',$material->id);
                })->exists();
                if($exists){
                    return;
                }else{
                     $this->info('Low stock raw material found!');
                }
                $pr = PurchaseRequest::create([
                    'raw_material_id' => $material->id,
                    'status' => 'draft',
                ]);
                $pr->items()->create([
                    'raw_material_id'=>$material->id,
                    'qty'=>10,
                    'unit_id'=>$material->unit->id
                ]);

                if ($pr) {
                    $this->info("A Purchase request created for Material ID: {$material->id}");
                }
            } catch (\Exception $e) {
                $this->error("Failed to create PR: " . $e->getMessage());
            }
        }
    }
}
