<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FileType;

class FileTypeSeeder extends Seeder
{
    public function run(): void
    {
        // Desired category-style file types (not file extensions)
        $fileTypes = [
            ['code' => 'contract',         'description' => 'Contract',        'status' => 'active'],
            ['code' => 'evidence',         'description' => 'Evidence',        'status' => 'active'],
            ['code' => 'correspondence',   'description' => 'Correspondence',  'status' => 'active'],
            ['code' => 'court_document',   'description' => 'Court Document',  'status' => 'active'],
            ['code' => 'invoice',          'description' => 'Invoice',         'status' => 'active'],
            ['code' => 'other',            'description' => 'Other',           'status' => 'active'],
        ];

        $codes = [];
        foreach ($fileTypes as $ft) {
            FileType::updateOrCreate(['code' => $ft['code']], $ft);
            $codes[] = $ft['code'];
        }

        // Remove any legacy extension-based rows
        FileType::whereNotIn('code', $codes)->delete();

        $this->command->info('File categories seeded: ' . count($fileTypes));
    }
}
