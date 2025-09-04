<?php

namespace App\Services;

use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Storage;

class WarrantToActPdfService
{
    /**
     * Generate Warrant To Act PDF by stamping data on provided template.
     *
     * @param string $templateAbsolutePath Absolute path to the source PDF template
     * @param string $outputRelativePath   Relative path under public disk to save the PDF (e.g. documents/WARRANT_TO_ACT_*.pdf)
     * @param array  $data  [name, nric, address_lines => [..], name_sign, nric_sign, date]
     */
    public function generate(string $templateAbsolutePath, string $outputRelativePath, array $data): void
    {
        $pdf = new Fpdi('P', 'mm');
        // Use absolute coordinates (no margins/autobreak)
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetAutoPageBreak(false);

        // Import template and get page count
        $pdf->setSourceFile($templateAbsolutePath);
        $pageCount = $pdf->setSourceFile($templateAbsolutePath);

        // Import page 1
        $tplIdx1 = $pdf->importPage(1);
        $pdf->AddPage();
        $pdf->useTemplate($tplIdx1, 0, 0, 210);

        // Font setup (Century Gothic 9). If custom TTF is present, use it; else fallback to Helvetica
        $fontPath = storage_path('app/private/fonts/CenturyGothic.ttf');
        if (file_exists($fontPath)) {
            // Register dynamic font
            $pdf->AddFont('CenturyGothic', '', $fontPath, true);
            $pdf->SetFont('CenturyGothic', '', 9);
        } else {
            $pdf->SetFont('Helvetica', '', 9);
        }

        // Page 1 stamps (absolute coordinates; A4 210x297mm)
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(29, 17);  // Name (slightly left & up)
        $pdf->Write(5, (string)($data['name'] ?? ''));

        $pdf->SetXY(29, 21);  // NRIC
        $pdf->Write(5, (string)($data['nric'] ?? ''));

        // Address lines (up to 3) – align with Name/NRIC column
        $addr = $data['address_lines'] ?? [];
        $y = 25; // just below NRIC row
        foreach (array_slice($addr, 0, 3) as $line) {
            $pdf->SetXY(29, $y);
            $pdf->Write(4, (string)$line);
            $y += 4; // tighter spacing to group address
        }

        // Date (optional)
        if (!empty($data['date'])) {
            $pdf->SetXY(162, 21);
            $pdf->Write(5, (string)$data['date']);
        }

        // Signature block
        $pdf->SetXY(29, 193); // NAME (signature block)
        $pdf->Write(5, (string)($data['name_sign'] ?? ($data['name'] ?? '')));

        $pdf->SetXY(29, 197); // NRIC
        $pdf->Write(5, (string)($data['nric_sign'] ?? ($data['nric'] ?? '')));

        // Import remaining pages (page 2 onwards)
        for ($pageNum = 2; $pageNum <= $pageCount; $pageNum++) {
            $tplIdx = $pdf->importPage($pageNum);
            $pdf->AddPage();
            $pdf->useTemplate($tplIdx, 0, 0, 210);

            // Add specific content for page 2
            if ($pageNum === 2) {
                // BETWEEN block (Page 2)
                $pdf->SetXY(22, 36);
                $pdf->Write(5, (string)($data['name'] ?? ''));

                // Address to the right of 'residing at' (single line on Page 2)
                $xAddr = 22;
                $y = 40;
                $addressFull = strtoupper(trim(implode(', ', array_filter($addr))));
                $pdf->SetXY($xAddr, $y);
                $pdf->Write(5, (string)$addressFull);
            }

            // Add specific content for other pages if needed
            // Page 3 and beyond will just use the template without additional content
        }

        // Save to public disk
        $tmpPath = storage_path('app/'.uniqid('wta_', true).'.pdf');
        $pdf->Output($tmpPath, 'F');
        $stream = fopen($tmpPath, 'r');
        Storage::disk('public')->put($outputRelativePath, $stream);
        fclose($stream);
        @unlink($tmpPath);
    }
}


