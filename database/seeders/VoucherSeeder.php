<?php

namespace Database\Seeders;

use App\Models\Voucher;
use App\Models\VoucherItem;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample vouchers
        $vouchers = [
            [
                'payee_name' => 'TNB Berhad',
                'payee_address' => 'No. 129, Jalan Bangsar, 59200 Kuala Lumpur',
                'contact_person' => 'Ahmad Rahman',
                'phone' => '03-2296-5566',
                'email' => 'billing@tnb.com.my',
                'payment_method' => 'bank_transfer',
                'payment_date' => now()->subDays(5)->toDateString(),
                'approved_by' => 'John Doe',
                'remark' => 'Monthly electricity bill payment',
                'status' => 'paid',
                'items' => [
                    ['description' => 'Electricity Bill - December 2024', 'category' => 'Utilities', 'qty' => 1, 'uom' => 'lot', 'unit_price' => 850.00, 'discount_percent' => 0, 'tax_percent' => 0],
                ]
            ],
            [
                'payee_name' => 'Office Supplies Co',
                'payee_address' => 'No. 45, Jalan Petaling, 50000 Kuala Lumpur',
                'contact_person' => 'Siti Aminah',
                'phone' => '03-2078-9900',
                'email' => 'sales@officesupplies.com.my',
                'payment_method' => 'cheque',
                'payment_date' => now()->subDays(3)->toDateString(),
                'approved_by' => 'Jane Smith',
                'remark' => 'Office stationery and supplies',
                'status' => 'approved',
                'items' => [
                    ['description' => 'A4 Paper (10 reams)', 'category' => 'Office Supplies', 'qty' => 10, 'uom' => 'ream', 'unit_price' => 12.50, 'discount_percent' => 5, 'tax_percent' => 6],
                    ['description' => 'Printer Ink Cartridges', 'category' => 'Office Supplies', 'qty' => 4, 'uom' => 'unit', 'unit_price' => 85.00, 'discount_percent' => 0, 'tax_percent' => 6],
                ]
            ],
            [
                'payee_name' => 'Legal Books Publisher',
                'payee_address' => 'No. 88, Jalan Sultan, 50000 Kuala Lumpur',
                'contact_person' => 'Dr. Lim Wei Ming',
                'phone' => '03-2142-7788',
                'email' => 'orders@legalbooks.com.my',
                'payment_method' => 'online_banking',
                'payment_date' => now()->addDays(2)->toDateString(),
                'approved_by' => 'John Doe',
                'remark' => 'Legal reference books for library',
                'status' => 'pending',
                'items' => [
                    ['description' => 'Malaysian Civil Procedure 2024 Edition', 'category' => 'Books', 'qty' => 2, 'uom' => 'unit', 'unit_price' => 450.00, 'discount_percent' => 10, 'tax_percent' => 0],
                    ['description' => 'Contract Law in Malaysia', 'category' => 'Books', 'qty' => 1, 'uom' => 'unit', 'unit_price' => 320.00, 'discount_percent' => 0, 'tax_percent' => 0],
                ]
            ]
        ];

        foreach ($vouchers as $voucherData) {
            $items = $voucherData['items'];
            unset($voucherData['items']);

            $voucher = Voucher::create([
                'voucher_no' => Voucher::generateVoucherNo(),
                ...$voucherData,
                'subtotal' => 0,
                'tax_total' => 0,
                'total_amount' => 0,
            ]);

            $subtotal = 0;
            $taxTotal = 0;

            foreach ($items as $item) {
                $qty = $item['qty'];
                $unitPrice = $item['unit_price'];
                $discountPercent = $item['discount_percent'];
                $taxPercent = $item['tax_percent'];

                $lineTotal = $qty * $unitPrice;
                $discountAmount = $lineTotal * ($discountPercent / 100);
                $afterDiscount = $lineTotal - $discountAmount;
                $taxAmount = $afterDiscount * ($taxPercent / 100);
                $amount = $afterDiscount + $taxAmount;

                VoucherItem::create([
                    'voucher_id' => $voucher->id,
                    'description' => $item['description'],
                    'category' => $item['category'],
                    'qty' => $qty,
                    'uom' => $item['uom'],
                    'unit_price' => $unitPrice,
                    'discount_percent' => $discountPercent,
                    'tax_percent' => $taxPercent,
                    'amount' => $amount,
                ]);

                $subtotal += $afterDiscount;
                $taxTotal += $taxAmount;
            }

            $voucher->update([
                'subtotal' => $subtotal,
                'tax_total' => $taxTotal,
                'total_amount' => $subtotal + $taxTotal,
            ]);
        }
    }
}
