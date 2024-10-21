<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Keputusan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KeputusanFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_keputusan()
    {
        // Arrange: Siapkan data keputusan
        $data = [
            'kode_depresi' => 'P001',
            'kode_gejala' => 'G001',
            'mb' => 0.6,
            'md' => 0.2,
        ];

        // Act: Buat keputusan
        $response = $this->post('/keputusan', $data);

        // Assert: Pastikan keputusan dibuat dengan benar
        $response->assertStatus(201);
        $this->assertDatabaseHas('keputusan', [
            'kode_depresi' => 'P001',
            'kode_gejala' => 'G001',
        ]);
    }
}