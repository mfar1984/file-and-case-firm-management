<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CaseFile;
use Carbon\Carbon;

class CaseFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $caseRefs = ['C-001', 'C-002', 'C-003', 'C-004', 'C-005', 'C-006', 'C-007', 'C-008', 'C-009', 'C-010'];
        $fileTypes = ['contract', 'evidence', 'correspondence', 'court_document', 'invoice', 'other'];
        $statuses = ['IN', 'OUT'];
        $rackLocations = ['Rack A-01', 'Rack A-02', 'Rack A-03', 'Rack B-01', 'Rack B-02', 'Rack C-01', 'Rack C-02'];
        $takenBy = ['Ahmad Firm', 'Siti Partner', 'Fatimah Staff', 'Omar Staff', 'Zainab Firm'];
        
        $files = [
            // Contract Files
            [
                'case_ref' => 'C-001',
                'file_name' => 'Sale_Purchase_Agreement_2025.pdf',
                'file_path' => 'storage/cases/C-001/contracts/sale_purchase_agreement.pdf',
                'file_type' => 'contract',
                'file_size' => '2.5 MB',
                'mime_type' => 'application/pdf',
                'description' => 'Sale and Purchase Agreement for Commercial Property in Petaling Jaya',
                'status' => 'IN',
                'taken_by' => null,
                'purpose' => null,
                'expected_return' => null,
                'actual_return' => null,
                'rack_location' => 'Rack A-01'
            ],
            [
                'case_ref' => 'C-002',
                'file_name' => 'Employment_Contract_Ali_Ahmad.docx',
                'file_path' => 'storage/cases/C-002/contracts/employment_contract.docx',
                'file_type' => 'contract',
                'file_size' => '1.8 MB',
                'mime_type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'description' => 'Employment Contract for Senior Manager Position',
                'status' => 'OUT',
                'taken_by' => 'Ahmad Firm',
                'purpose' => 'Contract Review Meeting with HR Department',
                'expected_return' => Carbon::now()->addDays(3),
                'actual_return' => null,
                'rack_location' => null
            ],
            
            // Evidence Files
            [
                'case_ref' => 'C-003',
                'file_name' => 'Bank_Statements_2024.pdf',
                'file_path' => 'storage/cases/C-003/evidence/bank_statements.pdf',
                'file_type' => 'evidence',
                'file_size' => '5.2 MB',
                'mime_type' => 'application/pdf',
                'description' => 'Bank Statements for Financial Investigation Case',
                'status' => 'IN',
                'taken_by' => null,
                'purpose' => null,
                'expected_return' => null,
                'actual_return' => null,
                'rack_location' => 'Rack A-02'
            ],
            [
                'case_ref' => 'C-004',
                'file_name' => 'Witness_Statement_Siti_Binti_Ahmad.pdf',
                'file_path' => 'storage/cases/C-004/evidence/witness_statement.pdf',
                'file_type' => 'evidence',
                'file_size' => '3.1 MB',
                'mime_type' => 'application/pdf',
                'description' => 'Witness Statement for Traffic Accident Case',
                'status' => 'OUT',
                'taken_by' => 'Siti Partner',
                'purpose' => 'Court Hearing Preparation',
                'expected_return' => Carbon::now()->addDays(1),
                'actual_return' => null,
                'rack_location' => null
            ],
            
            // Correspondence Files
            [
                'case_ref' => 'C-005',
                'file_name' => 'Client_Email_Correspondence.zip',
                'file_path' => 'storage/cases/C-005/correspondence/client_emails.zip',
                'file_type' => 'correspondence',
                'file_size' => '8.7 MB',
                'mime_type' => 'application/zip',
                'description' => 'Compressed Email Correspondence with Client',
                'status' => 'IN',
                'taken_by' => null,
                'purpose' => null,
                'expected_return' => null,
                'actual_return' => null,
                'rack_location' => 'Rack A-03'
            ],
            [
                'case_ref' => 'C-006',
                'file_name' => 'Legal_Notice_Response.pdf',
                'file_path' => 'storage/cases/C-006/correspondence/legal_notice_response.pdf',
                'file_type' => 'correspondence',
                'file_size' => '2.9 MB',
                'mime_type' => 'application/pdf',
                'description' => 'Response to Legal Notice from Opposing Party',
                'status' => 'OUT',
                'taken_by' => 'Fatimah Staff',
                'purpose' => 'Client Consultation Meeting',
                'expected_return' => Carbon::now()->addDays(5),
                'actual_return' => null,
                'rack_location' => null
            ],
            
            // Court Documents
            [
                'case_ref' => 'C-007',
                'file_name' => 'Court_Order_Interim_Injunction.pdf',
                'file_path' => 'storage/cases/C-007/court_documents/court_order.pdf',
                'file_type' => 'court_document',
                'file_size' => '1.5 MB',
                'mime_type' => 'application/pdf',
                'description' => 'Interim Injunction Order from High Court',
                'status' => 'IN',
                'taken_by' => null,
                'purpose' => null,
                'expected_return' => null,
                'actual_return' => null,
                'rack_location' => 'Rack B-01'
            ],
            [
                'case_ref' => 'C-008',
                'file_name' => 'Affidavit_in_Support.pdf',
                'file_path' => 'storage/cases/C-008/court_documents/affidavit.pdf',
                'file_type' => 'court_document',
                'file_size' => '4.2 MB',
                'mime_type' => 'application/pdf',
                'description' => 'Affidavit in Support of Application',
                'status' => 'OUT',
                'taken_by' => 'Omar Staff',
                'purpose' => 'Document Filing at Court Registry',
                'expected_return' => Carbon::now()->addDays(2),
                'actual_return' => null,
                'rack_location' => null
            ],
            
            // Invoice Files
            [
                'case_ref' => 'C-009',
                'file_name' => 'Legal_Fees_Invoice_Q1_2025.pdf',
                'file_path' => 'storage/cases/C-009/invoices/legal_fees_q1.pdf',
                'file_type' => 'invoice',
                'file_size' => '1.2 MB',
                'mime_type' => 'application/pdf',
                'description' => 'Quarterly Legal Fees Invoice for Client',
                'status' => 'IN',
                'taken_by' => null,
                'purpose' => null,
                'expected_return' => null,
                'actual_return' => null,
                'rack_location' => 'Rack B-02'
            ],
            [
                'case_ref' => 'C-010',
                'file_name' => 'Disbursement_Expenses.xlsx',
                'file_path' => 'storage/cases/C-010/invoices/disbursement_expenses.xlsx',
                'file_type' => 'invoice',
                'file_size' => '856 KB',
                'mime_type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'description' => 'Disbursement and Expenses Spreadsheet',
                'status' => 'OUT',
                'taken_by' => 'Zainab Firm',
                'purpose' => 'Accounting Department Review',
                'expected_return' => Carbon::now()->addDays(7),
                'actual_return' => null,
                'rack_location' => null
            ],
            
            // Additional Files for Variety
            [
                'case_ref' => 'C-001',
                'file_name' => 'Property_Valuation_Report.pdf',
                'file_path' => 'storage/cases/C-001/evidence/property_valuation.pdf',
                'file_type' => 'evidence',
                'file_size' => '6.8 MB',
                'mime_type' => 'application/pdf',
                'description' => 'Independent Property Valuation Report',
                'status' => 'IN',
                'taken_by' => null,
                'purpose' => null,
                'expected_return' => null,
                'actual_return' => null,
                'rack_location' => 'Rack C-01'
            ],
            [
                'case_ref' => 'C-002',
                'file_name' => 'HR_Policy_Documents.zip',
                'file_path' => 'storage/cases/C-002/other/hr_policies.zip',
                'file_type' => 'other',
                'file_size' => '12.3 MB',
                'mime_type' => 'application/zip',
                'description' => 'HR Policy Documents and Guidelines',
                'status' => 'IN',
                'taken_by' => null,
                'purpose' => null,
                'expected_return' => null,
                'actual_return' => null,
                'rack_location' => 'Rack C-02'
            ],
            [
                'case_ref' => 'C-003',
                'file_name' => 'Financial_Analysis_Report.xlsx',
                'file_path' => 'storage/cases/C-003/evidence/financial_analysis.xlsx',
                'file_type' => 'evidence',
                'file_size' => '2.1 MB',
                'mime_type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'description' => 'Financial Analysis Report for Investigation',
                'status' => 'OUT',
                'taken_by' => 'Ahmad Firm',
                'purpose' => 'Expert Witness Consultation',
                'expected_return' => Carbon::now()->addDays(4),
                'actual_return' => null,
                'rack_location' => null
            ],
            [
                'case_ref' => 'C-004',
                'file_name' => 'Police_Report_Accident.pdf',
                'file_path' => 'storage/cases/C-004/evidence/police_report.pdf',
                'file_type' => 'evidence',
                'file_size' => '1.8 MB',
                'mime_type' => 'application/pdf',
                'description' => 'Police Report for Traffic Accident Case',
                'status' => 'IN',
                'taken_by' => null,
                'purpose' => null,
                'expected_return' => null,
                'actual_return' => null,
                'rack_location' => 'Rack A-01'
            ]
        ];

        foreach ($files as $file) {
            CaseFile::create($file);
        }

        $this->command->info('Case Files created successfully!');
        $this->command->info('Total files created: ' . count($files));
        $this->command->info('Files IN: ' . CaseFile::where('status', 'IN')->count());
        $this->command->info('Files OUT: ' . CaseFile::where('status', 'OUT')->count());
        $this->command->info('Case References: ' . CaseFile::distinct('case_ref')->count());
    }
}
