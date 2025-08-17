<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all clients with address data
        $clients = DB::table('clients')->get();
        
        foreach ($clients as $client) {
            // Create primary address from individual fields
            if (!empty($client->address_line1) || !empty($client->address_line2) || !empty($client->address_line3) || 
                !empty($client->postcode) || !empty($client->city) || !empty($client->state) || !empty($client->country)) {
                
                DB::table('client_addresses')->insert([
                    'client_id' => $client->id,
                    'address_line1' => $client->address_line1,
                    'address_line2' => $client->address_line2,
                    'address_line3' => $client->address_line3,
                    'postcode' => $client->postcode,
                    'city' => $client->city,
                    'state' => $client->state,
                    'country' => $client->country,
                    'is_primary' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Create secondary addresses from address_correspondence if exists
            if (!empty($client->address_correspondence)) {
                $addresses = explode('; ', $client->address_correspondence);
                
                foreach ($addresses as $index => $address) {
                    if (!empty(trim($address))) {
                        $parts = explode(', ', $address);
                        
                        DB::table('client_addresses')->insert([
                            'client_id' => $client->id,
                            'address_line1' => $parts[0] ?? null,
                            'address_line2' => $parts[1] ?? null,
                            'address_line3' => $parts[2] ?? null,
                            'postcode' => $parts[3] ?? null,
                            'city' => $parts[4] ?? null,
                            'state' => $parts[5] ?? null,
                            'country' => $parts[6] ?? 'Malaysia',
                            'is_primary' => false,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clear all migrated data
        DB::table('client_addresses')->truncate();
    }
};
