<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\CourtCase;

class QuotationSeeder extends Seeder
{
    public function run(): void
    {
        $case = CourtCase::first();
        if (!$case) {
            return;
        }

        $nextId = (Quotation::max('id') ?? 0) + 1;
        $quotation = Quotation::create([
            'case_id' => $case->id,
            'quotation_no' => 'Q-' . str_pad((string)$nextId, 5, '0', STR_PAD_LEFT),
            'quotation_date' => now()->toDateString(),
            'valid_until' => now()->addDays(30)->toDateString(),
            'payment_terms' => 'net_30',
            'status' => 'pending',
            'customer_name' => 'Sample Customer',
            'customer_phone' => '0123456789',
            'customer_email' => 'customer@example.com',
            'customer_address' => 'Sample Address',
            'subtotal' => 0,
            'discount_total' => 0,
            'tax_total' => 0,
            'total' => 0,
        ]);

        $items = [
            ['description' => 'Service A', 'qty' => 1, 'uom' => 'lot', 'unit_price' => 1000, 'discount_percent' => 0, 'tax_percent' => 0],
            ['description' => 'Service B', 'qty' => 2, 'uom' => 'unit', 'unit_price' => 250, 'discount_percent' => 0, 'tax_percent' => 0],
        ];

        $subtotal = 0;
        foreach ($items as $it) {
            $amount = ($it['qty'] * $it['unit_price']);
            $subtotal += $amount;
            QuotationItem::create([
                'quotation_id' => $quotation->id,
                'description' => $it['description'],
                'qty' => $it['qty'],
                'uom' => $it['uom'],
                'unit_price' => $it['unit_price'],
                'discount_percent' => $it['discount_percent'],
                'tax_percent' => $it['tax_percent'],
                'amount' => $amount,
            ]);
        }

        $quotation->update([
            'subtotal' => $subtotal,
            'total' => $subtotal,
        ]);
    }
}


