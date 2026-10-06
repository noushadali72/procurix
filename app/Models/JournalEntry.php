<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['description', 'entry_date', 'reference', 'reference_no'])]
class JournalEntry extends Model
{
    protected $casts = [
        'entry_date' => 'date',
    ];

    public function lines()
    {
        return $this->hasMany(JournalEntryLine::class, 'journal_entry_id');
    }

    public function reference()
    {
        return $this->morphTo();
    }

    public function referenceUrl(): ?string
    {
        if (!$this->reference) {
            return null;
        }

        $route = match (class_basename($this->reference)) {
            'GoodsReceipt' => 'goods-receipts.show',
            'VendorBill' => 'vendor-bills.show',
            'PurchaseOrder' => 'purchase-orders.show',
            'PurchaseRequest' => 'purchase-requests.show',
            'MaterialReturn' => 'material-returns.show',
            default => null,
        };

        return $route && \Illuminate\Support\Facades\Route::has($route)
            ? route($route, $this->reference)
            : null;
    }

    public function referenceLabel(): ?string
    {
        if (!$this->reference) {
            return null;
        }

        return match (class_basename($this->reference)) {
            'GoodsReceipt' => $this->reference->grn_number ?? 'Goods Receipt',
            'VendorBill' => $this->reference->bill_number ?? 'Vendor Bill',
            'PurchaseOrder' => $this->reference->order_number ?? 'Purchase Order',
            'PurchaseRequest' => $this->reference->request_number ?? 'Purchase Request',
            'MaterialReturn' => $this->reference->return_number ?? 'Material Return',
            default => class_basename($this->reference),
        };
    }

    protected static function booted()
    {
        static::created(function (JournalEntry $entry) {
            $entry->reference_no = 'JE-' . str_pad(
                $entry->id,
                5,
                '0',
                STR_PAD_LEFT
            );

            $entry->saveQuietly();
        });
    }
}
