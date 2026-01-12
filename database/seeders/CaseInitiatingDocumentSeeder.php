<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SectionType;
use App\Models\CaseInitiatingDocument;
use App\Models\Firm;

class CaseInitiatingDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all firms
        $firms = Firm::all();
        
        // Default documents for each section type
        $documentsData = [
            'CA' => [ // Civil
                ['document_name' => 'Originating Summons', 'document_code' => 'originating_summons', 'sort_order' => 1],
                ['document_name' => 'Judgment Debtor Summons', 'document_code' => 'judgment_debtor_summons', 'sort_order' => 2],
                ['document_name' => 'Writ of Execution', 'document_code' => 'writ_of_execution', 'sort_order' => 3],
                ['document_name' => 'Writ of Summons', 'document_code' => 'writ_of_summons', 'sort_order' => 4],
                ['document_name' => 'Citizenship Investigation Report', 'document_code' => 'citizenship_investigation_report', 'sort_order' => 5],
            ],
            'CR' => [ // Criminal
                ['document_name' => 'Charge Sheet / Alternative Charge', 'document_code' => 'charge_sheet_alternative', 'sort_order' => 1],
                ['document_name' => 'Criminal Application / Motion', 'document_code' => 'criminal_application_motion', 'sort_order' => 2],
                ['document_name' => 'Summons and Charge Sheet / Alternative Charge', 'document_code' => 'summons_charge_sheet', 'sort_order' => 3],
                ['document_name' => 'Death Investigation Report (Coroner)', 'document_code' => 'death_investigation_report', 'sort_order' => 4],
            ],
            'CVY' => [ // Conveyancing
                ['document_name' => 'Letter Offer', 'document_code' => 'letter_offer', 'sort_order' => 1],
                ['document_name' => 'Sale & Purchase Agreement', 'document_code' => 'sale_purchase_agreement', 'sort_order' => 2],
                ['document_name' => 'Loan Agreement/ Facility Agreement', 'document_code' => 'loan_agreement', 'sort_order' => 3],
            ],
            'PB' => [ // Probate
                ['document_name' => 'Originating Summons (Ex-Parte)', 'document_code' => 'originating_summons_exparte', 'sort_order' => 1],
                ['document_name' => 'Form P', 'document_code' => 'form_p', 'sort_order' => 2],
                ['document_name' => 'Others', 'document_code' => 'others', 'sort_order' => 3],
            ],
        ];

        foreach ($firms as $firm) {
            foreach ($documentsData as $sectionCode => $documents) {
                // Find the section type for this firm
                $sectionType = SectionType::where('code', $sectionCode)
                    ->where('firm_id', $firm->id)
                    ->first();

                if ($sectionType) {
                    foreach ($documents as $documentData) {
                        CaseInitiatingDocument::updateOrCreate(
                            [
                                'section_type_id' => $sectionType->id,
                                'document_code' => $documentData['document_code'],
                                'firm_id' => $firm->id,
                            ],
                            [
                                'document_name' => $documentData['document_name'],
                                'sort_order' => $documentData['sort_order'],
                                'status' => 'active',
                            ]
                        );
                    }
                }
            }
        }

        // Also create global defaults (firm_id = null) if needed
        $globalSectionTypes = SectionType::whereNull('firm_id')->get();
        foreach ($globalSectionTypes as $sectionType) {
            $documents = $documentsData[$sectionType->code] ?? [];
            foreach ($documents as $documentData) {
                CaseInitiatingDocument::updateOrCreate(
                    [
                        'section_type_id' => $sectionType->id,
                        'document_code' => $documentData['document_code'],
                        'firm_id' => null,
                    ],
                    [
                        'document_name' => $documentData['document_name'],
                        'sort_order' => $documentData['sort_order'],
                        'status' => 'active',
                    ]
                );
            }
        }

        $this->command->info('Case Initiating Documents seeded successfully!');
    }
}
