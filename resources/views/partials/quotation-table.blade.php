{{-- Quotation Table Partial --}}
<div class="main-content">
    <table class="item-table">
        <thead>
            <tr>
                <th>ITEM.</th>
                <th>DESCRIPTION</th>
                <th>QTY</th>
                <th>UOM</th>
                <th>PRICE<br>(RM)</th>
                <th>DISC.</th>
                <th>AMOUNT<br>(RM)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($quotation->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}.</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ number_format($item->qty, 2) }}</td>
                    <td>{{ $item->uom }}</td>
                    <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">{{ number_format($item->discount_amount ?? 0, 2) }}</td>
                    <td class="text-right">{{ number_format($item->amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No items found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
