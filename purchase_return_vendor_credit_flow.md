# Purchase Return & Vendor Credit Flow --- Project Notes

## Purpose

This document explains the return process implemented in the procurement
system, from receiving purchased materials to returning them and
handling the financial effect through vendor credits, bill adjustments,
payments, and vendor refunds.

The main idea is simple:

**Physical return and financial adjustment are separate but connected
processes.**

-   **Purchase Return** records materials physically returned to the
    vendor.
-   **Vendor Credit** records the money/value the vendor owes us because
    of that return.
-   **Vendor Credit Application** records credit used against a vendor
    bill.
-   **Vendor Credit Refund** records actual money returned by the
    vendor.

------------------------------------------------------------------------

# 1. Main Procurement Flow

``` text
Purchase Request
      ↓
Purchase Order
      ↓
Receive Materials
      ↓
Goods Receipt
      ↓
Vendor Bill
      ↓
Vendor Payment
```

A return starts from a Goods Receipt:

``` text
Goods Receipt
      ↓
Purchase Return
      ↓
Stock decreases
      ↓
Financial adjustment
```

------------------------------------------------------------------------

# 2. Why Purchase Return Starts From Goods Receipt

A Purchase Order tells us what we **ordered**.

A Goods Receipt tells us what we actually **received**.

Therefore, a return should be based on a Goods Receipt item rather than
directly on a Purchase Order item.

Example:

``` text
PO ordered:       100 units
Received:          80 units
Returned:          10 units
Actually retained: 70 units
```

The system must never allow returning more than the quantity actually
received and still available for return.

------------------------------------------------------------------------

# 3. Purchase Return

The `purchase_returns` table represents the return document/header.

Important information includes:

-   `goods_receipt_id`
-   `return_number`
-   `return_date`
-   `status`
-   `reason`
-   `notes`

The return belongs to one Goods Receipt.

Typical statuses:

``` text
draft
completed
cancelled
```

A completed return means the materials have actually been returned and
inventory should reflect that.

------------------------------------------------------------------------

# 4. Purchase Return Items

The `purchase_return_items` table stores the individual returned
materials.

Important fields:

-   `purchase_return_id`
-   `goods_receipt_item_id`
-   `qty`
-   `unit_id`
-   `unit_cost`
-   `line_total`
-   `reason`

Each return item points back to the Goods Receipt item from which it
originated.

The line value is:

``` text
Line Total = Return Quantity × Unit Cost
```

Example:

``` text
Returned Qty = 5
Unit Cost    = 1,000

Line Total   = 5,000
```

------------------------------------------------------------------------

# 5. Inventory Effect

When a Purchase Return is completed, the returned quantity is removed
from inventory.

Conceptually:

``` text
Current Stock - Returned Quantity = New Stock
```

Example:

``` text
Current stock: 100
Return:         20

New stock:      80
```

The return quantity must be validated so that previously returned
quantities are considered. This prevents the same received stock from
being returned twice.

------------------------------------------------------------------------

# 6. Return Before Vendor Bill

This is the simplest case.

Example:

``` text
Received value: 100,000
Returned value:  20,000
```

If the Vendor Bill has **not yet been generated**, the bill should
represent only the materials retained.

``` text
Original received value = 100,000
Return                   = 20,000
--------------------------------
Vendor Bill              = 80,000
```

There is no need to create a Vendor Credit for a bill that did not yet
exist.

The return is already reflected when generating the bill.

------------------------------------------------------------------------

# 7. Return After Vendor Bill

Suppose:

``` text
Vendor Bill = 100,000
```

Later we return materials worth:

``` text
20,000
```

We should **not modify the original Vendor Bill to 80,000**.

The original bill is a historical financial document and should remain:

``` text
Vendor Bill = 100,000
```

Instead, create:

``` text
Vendor Credit = 20,000
```

This preserves the history:

``` text
Bill originally received     100,000
Returned materials            20,000
Vendor Credit                 20,000
```

------------------------------------------------------------------------

# 8. Vendor Credit

A Vendor Credit represents value the vendor owes us because of a return.

Important information includes:

-   `purchase_return_id`
-   `vendor_id`
-   `vendor_bill_id`
-   `credit_number`
-   `credit_date`
-   `amount`
-   `status`
-   `notes`

The original/source `vendor_bill_id` tells us which bill was related to
the return.

A Vendor Credit can later be:

1.  Applied against a vendor bill.
2.  Refunded as money by the vendor.
3.  Partly applied and partly refunded.

------------------------------------------------------------------------

# 9. Vendor Credit Applications

The `vendor_credit_applications` table records when a credit is used to
reduce a Vendor Bill.

Important fields:

-   `vendor_credit_id`
-   `vendor_bill_id`
-   `amount`
-   `applied_date`

Example:

``` text
Vendor Credit = 20,000

Applied to Bill A = 12,000
Remaining Credit  =  8,000
```

The same credit can potentially be used against another bill belonging
to the **same vendor**.

Example:

``` text
Credit             20,000
Applied Bill A     12,000
Applied Bill B      5,000
Remaining           3,000
```

A credit must never be applied to a bill belonging to another vendor.

------------------------------------------------------------------------

# 10. Vendor Credit Refunds

Sometimes the vendor does not apply the credit against another bill and
instead returns actual money.

For this we use:

`vendor_credit_refunds`

Important fields:

-   `vendor_credit_id`
-   `amount`
-   `refund_date`
-   `refund_method`
-   `transaction_id`
-   `reference`
-   `notes`

Example:

``` text
Vendor Credit = 20,000
Vendor sends cash/bank refund = 20,000
```

The refund record gives us proper history instead of simply changing a
number on the Vendor Credit.

This is different from `vendor_payments`.

``` text
Vendor Payment
Us → Vendor

Vendor Credit Refund
Vendor → Us
```

Therefore refunds should not be stored in `vendor_payments`.

------------------------------------------------------------------------

# 11. Credit Balance Calculation

The available Vendor Credit is calculated from its history:

``` text
Available Credit
    = Credit Amount
    - Applied Amount
    - Refunded Amount
```

Example:

``` text
Credit Amount     30,000
Applied           10,000
Refunded           5,000
------------------------
Available          15,000
```

Applied amount comes from `vendor_credit_applications`.

Refunded amount comes from `vendor_credit_refunds`.

This makes the history records the source of truth.

------------------------------------------------------------------------

# 12. Vendor Bill Due Amount

Previously the bill calculation was:

``` text
Due = Bill Total - Payments
```

That becomes incorrect once Vendor Credits exist.

The correct calculation is:

``` text
Bill Due
    = Bill Total
    - Successful Payments
    - Applied Vendor Credits
```

Example:

``` text
Bill Total        100,000
Paid               30,000
Applied Credit     20,000
-------------------------
Due                50,000
```

In the `VendorBill` model:

``` php
public function getPaidAmountAttribute()
{
    return $this->vendorPayments()
        ->where('status', 'successful')
        ->sum('amount');
}

public function getCreditedAmountAttribute()
{
    return $this->creditApplications()
        ->sum('amount');
}

public function getDueAmountAttribute()
{
    return max(
        (float) $this->total
        - (float) $this->paid_amount
        - (float) $this->credited_amount,
        0
    );
}
```

This keeps the calculation in one place.

Views and controllers can simply use:

``` php
$vendorBill->paid_amount
$vendorBill->credited_amount
$vendorBill->due_amount
```

------------------------------------------------------------------------

# 13. Vendor Bill Status

The Vendor Bill status depends on both payments and applied credits.

### Unpaid

``` text
Payments = 0
Credits  = 0
```

Status:

``` text
unpaid
```

### Partially Paid

Example:

``` text
Bill Total = 100,000
Payment    = 30,000
Credit     = 20,000
Due        = 50,000
```

Status:

``` text
partially_paid
```

### Paid / Fully Settled

Example:

``` text
Bill Total = 100,000
Payment    = 80,000
Credit     = 20,000
Due        = 0
```

Status:

``` text
paid
```

`paid` here means the bill has no remaining balance. It may have been
settled by cash payments, credits, or both.

------------------------------------------------------------------------

# 14. Payment Validation

When recording a Vendor Payment, the payment must not exceed:

``` text
$vendorBill->due_amount
```

Because `due_amount` already considers both successful payments and
applied credits.

Example:

``` text
Bill Total      100,000
Paid             30,000
Credit           20,000
Due              50,000
```

The user cannot record a payment greater than `50,000`.

This prevents overpayment.

------------------------------------------------------------------------

# 15. Credit Application Validation

When applying Vendor Credit:

1.  Credit and Bill must belong to the same vendor.
2.  Amount must be greater than zero.
3.  Amount cannot exceed available credit.
4.  Amount cannot exceed bill due amount.
5.  Bill must still have an outstanding balance.

Example:

``` text
Available Credit = 20,000
Bill Due         = 15,000
```

Maximum application:

``` text
15,000
```

Not `20,000`.

------------------------------------------------------------------------

# 16. Refund Validation

When recording a Vendor Credit Refund:

``` text
Refund Amount <= Remaining Credit
```

Example:

``` text
Credit             20,000
Already Applied    12,000
Remaining           8,000
```

Maximum refund:

``` text
8,000
```

The refund creates a `vendor_credit_refunds` history record.

------------------------------------------------------------------------

# 17. Important Relationships

## Goods Receipt

``` text
GoodsReceipt
    has many GoodsReceiptItems
    has many PurchaseReturns
```

## Purchase Return

``` text
PurchaseReturn
    belongs to GoodsReceipt
    has many PurchaseReturnItems
    has one VendorCredit
```

## Purchase Return Item

``` text
PurchaseReturnItem
    belongs to PurchaseReturn
    belongs to GoodsReceiptItem
    belongs to Unit
```

## Vendor Credit

``` text
VendorCredit
    belongs to PurchaseReturn
    belongs to Vendor
    belongs to original VendorBill
    has many VendorCreditApplications
    has many VendorCreditRefunds
```

## Vendor Bill

``` text
VendorBill
    belongs to Vendor
    belongs to PurchaseOrder
    has many VendorPayments
    has many VendorCreditApplications
```

------------------------------------------------------------------------

# 18. Main Tables Involved

``` text
purchase_orders
purchase_order_items

goods_receipts
goods_receipt_items

purchase_returns
purchase_return_items

vendor_bills
vendor_bill_items

vendor_payments

vendor_credits
vendor_credit_applications
vendor_credit_refunds
```

These tables together provide the complete flow without introducing full
accounting complexity.

------------------------------------------------------------------------

# 19. Three Important Return Scenarios

## Scenario A --- Return Before Bill

``` text
PO
 ↓
Receive 100
 ↓
Return 20
 ↓
Bill only retained 80
```

Result:

-   Inventory retains 80.
-   Bill represents 80.
-   No Vendor Credit needed.

------------------------------------------------------------------------

## Scenario B --- Return After Bill, Before Full Payment

``` text
Receive 100
 ↓
Bill 100
 ↓
Return 20
 ↓
Vendor Credit 20
```

The original bill remains 100.

Example:

``` text
Bill Total     100
Payment         30
Applied Credit  20
Due             50
```

------------------------------------------------------------------------

## Scenario C --- Return After Bill Is Fully Paid

``` text
Receive 100
 ↓
Bill 100
 ↓
Pay 100
 ↓
Return 20
 ↓
Vendor Credit 20
```

The original payment is not deleted or modified.

The Vendor Credit can now be:

``` text
Vendor Credit 20
       ↓
 ┌─────┴─────┐
 ↓           ↓
Apply to   Receive
future bill refund
```

This preserves the full history.

------------------------------------------------------------------------

# 20. Why We Do Not Make Bill Due Negative

Suppose:

``` text
Bill Total = 100
Paid       = 100
Return      = 20
```

We do **not** calculate:

``` text
Due = -20
```

The bill is already settled.

Instead:

``` text
Bill Due      = 0
Vendor Credit = 20
```

The credit exists independently and can be used later.

This is much easier to understand and keeps the bill history correct.

------------------------------------------------------------------------

# 21. UI / Sidebar Structure

The relevant sidebar structure is:

``` text
Procurement
├── Vendors
├── Purchase Requests
└── Purchase Orders

Receiving
├── Receive Materials
├── Goods Receipts
└── Purchase Returns

Finance
├── Vendor Bills
├── Vendor Payments
├── Vendor Credits
└── Payment Terms
```

There is no separate sidebar tab for Vendor Credit Refunds.

Refund history belongs inside the Vendor Credit detail page.

------------------------------------------------------------------------

# 22. Vendor Bill Financial Summary

The Vendor Bill page should clearly show:

``` text
Subtotal
Tax
----------------
Total
Paid
Vendor Credit
----------------
Outstanding
```

Example:

``` text
Subtotal          100,000
Tax                     0
-------------------------
Total             100,000
Paid               30,000
Vendor Credit      20,000
-------------------------
Outstanding        50,000
```

This makes it clear why a bill may be fully settled even if the cash
payment is lower than the original bill total.

------------------------------------------------------------------------

# 23. Vendor Credit Detail Page

A Vendor Credit detail page should show the basic credit information
plus:

### Credit Summary

``` text
Credit Amount
Applied Amount
Refunded Amount
Available Amount
```

### Application History

Shows which bills received the credit:

``` text
Bill | Date | Amount
```

### Refund History

Shows money returned by the vendor:

``` text
Date | Method | Transaction ID | Reference | Amount
```

The page can also provide actions:

``` text
Apply Credit
Record Refund
```

only when remaining credit is greater than zero.

------------------------------------------------------------------------

# 24. Simple Mental Model

The easiest way to remember the whole system is:

``` text
PURCHASE ORDER
What did we order?
        ↓

GOODS RECEIPT
What did we actually receive?
        ↓

PURCHASE RETURN
What did we physically send back?
        ↓

VENDOR CREDIT
How much does the vendor now owe us?
        ↓

 ┌───────────────┐
 ↓               ↓
CREDIT        REFUND
APPLICATION
 ↓               ↓
Reduce bill   Vendor sends
balance       money back
```

And always remember these two formulas:

``` text
Bill Due
= Bill Total
- Successful Payments
- Applied Credits
```

``` text
Available Vendor Credit
= Credit Amount
- Applied Credits
- Vendor Refunds
```

------------------------------------------------------------------------

# 25. What We Intentionally Kept Simple

This project is focused on learning and a practical procurement
workflow, so we intentionally did **not** introduce:

-   General ledger accounting
-   Debit/credit journal entries
-   Accounts payable ledger
-   Complex refund reconciliation
-   Separate refund sidebar module
-   Complicated accounting periods
-   Full ERP accounting

The current implementation is enough to understand and implement the
important business logic while keeping the project manageable.

------------------------------------------------------------------------

# Final Flow

``` text
Purchase Request
       ↓
Purchase Order
       ↓
Goods Receipt
       ↓
 ┌───────────────┐
 ↓               ↓
No Return      Purchase Return
 ↓               ↓
Vendor Bill    Stock decreases
                 ↓
          Was Bill Generated?
             ↓          ↓
            No         Yes
             ↓          ↓
        Bill Net      Vendor Credit
        Quantity          ↓
                    ┌─────┴─────┐
                    ↓           ↓
                Apply Credit   Refund
                    ↓           ↓
                Vendor Bill   Money from
                Due Reduced    Vendor
```

This is the return process implemented so far.
