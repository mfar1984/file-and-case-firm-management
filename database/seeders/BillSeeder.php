<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\BillItem;
use Illuminate\Database\Seeder;

class BillSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample bills
        $bills = [
            [
                'vendor_name' => 'Telekom Malaysia Berhad',
                'vendor_address' => 'Menara TM, Jalan Pantai Baharu, 50672 Kuala Lumpur',
                'vendor_phone' => '1-300-88-9515',
                'vendor_email' => 'corporate@tm.com.my',
                'bill_date' => now()->subDays(10)->toDateString(),
                'due_date' => now()->addDays(20)->toDateString(),
                'category' => 'Telecommunications',
                'description' => 'Monthly internet and phone services',
                'remark' => 'Unifi Business Plan 500Mbps',
                'status' => 'pending',
                'items' => [
                    ['description' => 'Unifi Business 500Mbps', 'category' => 'Internet', 'qty' => 1, 'uom' => 'month', 'unit_price' => 399.00, 'discount_percent' => 0, 'tax_percent' => 6],
                    ['description' => 'Business Phone Line', 'category' => 'Telecommunications', 'qty' => 2, 'uom' => 'line', 'unit_price' => 45.00, 'discount_percent' => 0, 'tax_percent' => 6],
                ]
            ],
            [
                'vendor_name' => 'Syarikat Air Selangor',
                'vendor_address' => 'Menara SYABAS, No. 1, Jalan Pantai Murni 8/1, 40000 Shah Alam',
                'vendor_phone' => '15300',
                'vendor_email' => 'customer@airselangor.com',
                'bill_date' => now()->subDays(15)->toDateString(),
                'due_date' => now()->subDays(1)->toDateString(),
                'category' => 'Utilities',
                'description' => 'Water supply bill',
                'remark' => 'Commercial water usage',
                'status' => 'overdue',
                'items' => [
                    ['description' => 'Water Usage - 45 cubic meters', 'category' => 'Water', 'qty' => 45, 'uom' => 'm³', 'unit_price' => 2.35, 'discount_percent' => 0, 'tax_percent' => 0],
                    ['description' => 'Service Charge', 'category' => 'Service', 'qty' => 1, 'uom' => 'lot', 'unit_price' => 8.50, 'discount_percent' => 0, 'tax_percent' => 0],
                ]
            ],
            [
                'vendor_name' => 'KL Cleaning Services Sdn Bhd',
                'vendor_address' => 'No. 123, Jalan Ampang, 50450 Kuala Lumpur',
                'vendor_phone' => '03-4251-8899',
                'vendor_email' => 'admin@klcleaning.com.my',
                'bill_date' => now()->subDays(7)->toDateString(),
                'due_date' => now()->addDays(23)->toDateString(),
                'payment_date' => now()->subDays(2)->toDateString(),
                'payment_method' => 'bank_transfer',
                'payment_reference' => 'TXN20250108001',
                'category' => 'Maintenance',
                'description' => 'Office cleaning services',
                'remark' => 'Monthly office cleaning contract',
                'status' => 'paid',
                'items' => [
                    ['description' => 'Office Cleaning - Daily Service', 'category' => 'Cleaning', 'qty' => 22, 'uom' => 'day', 'unit_price' => 35.00, 'discount_percent' => 5, 'tax_percent' => 6],
                    ['description' => 'Deep Cleaning - Monthly', 'category' => 'Cleaning', 'qty' => 1, 'uom' => 'service', 'unit_price' => 250.00, 'discount_percent' => 0, 'tax_percent' => 6],
                ]
            ],
            [
                'vendor_name' => 'Legal Software Solutions',
                'vendor_address' => 'Suite 15-3, Menara Axiata, No. 9, Jalan Stesen Sentral 5, 50470 KL',
                'vendor_phone' => '03-2776-3000',
                'vendor_email' => 'support@legalsoftware.com.my',
                'bill_date' => now()->subDays(5)->toDateString(),
                'due_date' => now()->addDays(25)->toDateString(),
                'category' => 'Software',
                'description' => 'Legal practice management software subscription',
                'remark' => 'Annual subscription renewal',
                'status' => 'pending',
                'items' => [
                    ['description' => 'LegalPro Enterprise License', 'category' => 'Software License', 'qty' => 5, 'uom' => 'user', 'unit_price' => 180.00, 'discount_percent' => 10, 'tax_percent' => 6],
                    ['description' => 'Cloud Storage 500GB', 'category' => 'Cloud Service', 'qty' => 1, 'uom' => 'package', 'unit_price' => 120.00, 'discount_percent' => 0, 'tax_percent' => 6],
                ]
            ]
        ];

        foreach ($bills as $billData) {
            $items = $billData['items'];
            unset($billData['items']);

            $bill = Bill::create([
                'bill_no' => Bill::generateBillNo(),
                ...$billData,
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

                BillItem::create([
                    'bill_id' => $bill->id,
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

            $bill->update([
                'subtotal' => $subtotal,
                'tax_total' => $taxTotal,
                'total_amount' => $subtotal + $taxTotal,
            ]);
        }
    }
}
