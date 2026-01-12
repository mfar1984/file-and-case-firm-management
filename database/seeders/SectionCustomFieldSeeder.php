<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SectionType;
use App\Models\SectionCustomField;
use App\Models\Firm;

class SectionCustomFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all firms
        $firms = Firm::all();
        
        // Default custom fields for each section type
        $customFieldsData = [
            'CA' => [ // Civil
                [
                    'field_name' => 'Total Claim (RM)',
                    'field_type' => 'number',
                    'placeholder' => '0',
                    'is_required' => true,
                    'sort_order' => 1,
                ],
            ],
            'CR' => [ // Criminal
                // Criminal typically doesn't have claim amount
            ],
            'CVY' => [ // Conveyancing
                [
                    'field_name' => 'Name of Property',
                    'field_type' => 'text',
                    'placeholder' => 'Enter property name',
                    'is_required' => true,
                    'sort_order' => 1,
                ],
                [
                    'field_name' => 'Purchase Price',
                    'field_type' => 'number',
                    'placeholder' => '0',
                    'is_required' => true,
                    'sort_order' => 2,
                ],
            ],
            'PB' => [ // Probate
                [
                    'field_name' => 'Total Claim (RM)',
                    'field_type' => 'number',
                    'placeholder' => '0',
                    'is_required' => true,
                    'sort_order' => 1,
                ],
            ],
        ];

        foreach ($firms as $firm) {
            foreach ($customFieldsData as $sectionCode => $fields) {
                // Find the section type for this firm
                $sectionType = SectionType::where('code', $sectionCode)
                    ->where('firm_id', $firm->id)
                    ->first();

                if ($sectionType) {
                    foreach ($fields as $fieldData) {
                        SectionCustomField::updateOrCreate(
                            [
                                'section_type_id' => $sectionType->id,
                                'field_name' => $fieldData['field_name'],
                                'firm_id' => $firm->id,
                            ],
                            [
                                'field_type' => $fieldData['field_type'],
                                'placeholder' => $fieldData['placeholder'],
                                'is_required' => $fieldData['is_required'],
                                'sort_order' => $fieldData['sort_order'],
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
            $fields = $customFieldsData[$sectionType->code] ?? [];
            foreach ($fields as $fieldData) {
                SectionCustomField::updateOrCreate(
                    [
                        'section_type_id' => $sectionType->id,
                        'field_name' => $fieldData['field_name'],
                        'firm_id' => null,
                    ],
                    [
                        'field_type' => $fieldData['field_type'],
                        'placeholder' => $fieldData['placeholder'],
                        'is_required' => $fieldData['is_required'],
                        'sort_order' => $fieldData['sort_order'],
                        'status' => 'active',
                    ]
                );
            }
        }

        $this->command->info('Section Custom Fields seeded successfully!');
    }
}
