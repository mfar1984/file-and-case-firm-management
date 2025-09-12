<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Firm;
use App\Models\CourtCase;
use App\Models\CaseFile;
use App\Models\FileType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileManagementMultiFirmTest extends TestCase
{
    use RefreshDatabase;

    protected $firm1;
    protected $firm2;
    protected $user1;
    protected $user2;
    protected $case1;
    protected $case2;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        // Create two firms
        $this->firm1 = Firm::factory()->create(['name' => 'Firm A']);
        $this->firm2 = Firm::factory()->create(['name' => 'Firm B']);

        // Create users for each firm
        $this->user1 = User::factory()->create(['firm_id' => $this->firm1->id]);
        $this->user2 = User::factory()->create(['firm_id' => $this->firm2->id]);

        // Create cases for each firm
        $this->case1 = CourtCase::factory()->create([
            'case_number' => 'C-001',
            'firm_id' => $this->firm1->id
        ]);
        
        $this->case2 = CourtCase::factory()->create([
            'case_number' => 'C-002', 
            'firm_id' => $this->firm2->id
        ]);

        // Create file types for each firm
        FileType::create([
            'code' => 'CONTRACT',
            'description' => 'Contract Document',
            'status' => 'active',
            'firm_id' => $this->firm1->id
        ]);

        FileType::create([
            'code' => 'EVIDENCE',
            'description' => 'Evidence Document',
            'status' => 'active',
            'firm_id' => $this->firm2->id
        ]);
    }

    /** @test */
    public function case_files_are_isolated_by_firm()
    {
        // Create case files for each firm
        $file1 = CaseFile::create([
            'case_ref' => 'C-001',
            'file_name' => 'contract.pdf',
            'file_path' => 'case-files/contract.pdf',
            'category_id' => 1,
            'file_size' => 1024,
            'mime_type' => 'application/pdf',
            'status' => 'IN',
            'firm_id' => $this->firm1->id
        ]);

        $file2 = CaseFile::create([
            'case_ref' => 'C-002',
            'file_name' => 'evidence.pdf',
            'file_path' => 'case-files/evidence.pdf',
            'category_id' => 2,
            'file_size' => 2048,
            'mime_type' => 'application/pdf',
            'status' => 'IN',
            'firm_id' => $this->firm2->id
        ]);

        // Test firm 1 user can only see firm 1 files
        $this->actingAs($this->user1);
        session(['current_firm_id' => $this->firm1->id]);
        
        $firm1Files = CaseFile::all();
        $this->assertCount(1, $firm1Files);
        $this->assertEquals('contract.pdf', $firm1Files->first()->file_name);

        // Test firm 2 user can only see firm 2 files
        $this->actingAs($this->user2);
        session(['current_firm_id' => $this->firm2->id]);
        
        $firm2Files = CaseFile::all();
        $this->assertCount(1, $firm2Files);
        $this->assertEquals('evidence.pdf', $firm2Files->first()->file_name);
    }

    /** @test */
    public function file_upload_assigns_correct_firm_id()
    {
        $this->actingAs($this->user1);
        session(['current_firm_id' => $this->firm1->id]);

        $file = UploadedFile::fake()->create('test.pdf', 100, 'application/pdf');

        $response = $this->post(route('file-management.store'), [
            'case_ref' => 'C-001',
            'file_type' => 1,
            'files' => [$file],
            'description' => 'Test file upload'
        ]);

        $response->assertRedirect();
        
        $uploadedFile = CaseFile::where('file_name', 'test.pdf')->first();
        $this->assertNotNull($uploadedFile);
        $this->assertEquals($this->firm1->id, $uploadedFile->firm_id);
        $this->assertEquals('C-001', $uploadedFile->case_ref);
    }

    /** @test */
    public function file_management_index_shows_only_firm_files()
    {
        // Create files for both firms
        CaseFile::create([
            'case_ref' => 'C-001',
            'file_name' => 'firm1_file.pdf',
            'file_path' => 'case-files/firm1_file.pdf',
            'category_id' => 1,
            'file_size' => 1024,
            'mime_type' => 'application/pdf',
            'status' => 'IN',
            'firm_id' => $this->firm1->id
        ]);

        CaseFile::create([
            'case_ref' => 'C-002',
            'file_name' => 'firm2_file.pdf',
            'file_path' => 'case-files/firm2_file.pdf',
            'category_id' => 2,
            'file_size' => 2048,
            'mime_type' => 'application/pdf',
            'status' => 'IN',
            'firm_id' => $this->firm2->id
        ]);

        // Test firm 1 user sees only firm 1 files
        $this->actingAs($this->user1);
        session(['current_firm_id' => $this->firm1->id]);
        
        $response = $this->get(route('file-management.index'));
        $response->assertStatus(200);
        $response->assertSee('firm1_file.pdf');
        $response->assertDontSee('firm2_file.pdf');

        // Test firm 2 user sees only firm 2 files
        $this->actingAs($this->user2);
        session(['current_firm_id' => $this->firm2->id]);
        
        $response = $this->get(route('file-management.index'));
        $response->assertStatus(200);
        $response->assertSee('firm2_file.pdf');
        $response->assertDontSee('firm1_file.pdf');
    }

    /** @test */
    public function file_types_are_isolated_by_firm()
    {
        // Test firm 1 user sees only firm 1 file types
        $this->actingAs($this->user1);
        session(['current_firm_id' => $this->firm1->id]);
        
        $firm1FileTypes = FileType::all();
        $this->assertCount(1, $firm1FileTypes);
        $this->assertEquals('CONTRACT', $firm1FileTypes->first()->code);

        // Test firm 2 user sees only firm 2 file types
        $this->actingAs($this->user2);
        session(['current_firm_id' => $this->firm2->id]);
        
        $firm2FileTypes = FileType::all();
        $this->assertCount(1, $firm2FileTypes);
        $this->assertEquals('EVIDENCE', $firm2FileTypes->first()->code);
    }
}
