{{-- Quotation Footer Partial --}}
<div class="footer">
    <div class="footer-content">
        <!-- Top section with amount in words and summary table -->
        <div class="footer-top">
            <div class="left-section">
                <div class="amount-in-words">
                    Ringgit Malaysia: {{ $quotation->total_words ?? 'Amount in Words' }}
                </div>
            </div>

            <div class="right-section">
                <table class="summary-table-bordered">
                    <tr>
                        <td class="summary-label-bordered">Subtotal</td>
                        <td class="summary-value-bordered">{{ number_format($quotation->subtotal ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="summary-label-bordered">Tax</td>
                        <td class="summary-value-bordered">{{ number_format($quotation->tax_total ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="summary-label-bordered">Total</td>
                        <td class="summary-value-bordered total-row">{{ number_format($quotation->total ?? 0, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="clear"></div>
        
        <!-- Reminder section after Total -->
        <div class="note-section" style="margin-top: 5mm;">
            <strong>Reminder:</strong> Pursuant to the Solicitor's Remuneration Order 2005, interest 8% on the total sum billed will be charges from the expiration of one (1) month of the billing dates
        </div>

        <!-- Bottom section with signature -->
        <div class="footer-bottom">
            <div class="authorised-signature">
                <div class="signature-line"></div>
                <strong>Authorised Signature</strong>
            </div>
        </div>
    </div>
</div>
