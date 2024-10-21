<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Diagnosa;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DiagnosaFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_diagnosa()
    {
        // Arrange: Siapkan data diagnosa
        $data = [
            'diagnosa_id' => 'D001',
            'data_diagnosa' => json_encode(['P001' => 0.8]),
            'kondisi' => json_encode(['G001' => 0.6]),
        ];

        // Act: Buat diagnosa
        $response = $this->post('/diagnosa', $data);

        // Assert: Pastikan diagnosa dibuat dengan benar
        $response->assertStatus(201);
        $this->assertDatabaseHas('diagnosas', [
            'diagnosa_id' => 'D001',
        ]);
    }
}