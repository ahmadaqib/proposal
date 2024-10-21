<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\TingkatDepresi;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TingkatDepresiFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_tingkat_depresi()
    {
        // Arrange: Siapkan data tingkat depresi
        $data = [
            'kode_depresi' => 'P005',
            'depresi' => 'Depresi Sangat Berat',
        ];

        // Act: Buat tingkat depresi
        $response = $this->post('/tingkat-depresi', $data);

        // Assert: Pastikan tingkat depresi dibuat dengan benar
        $response->assertStatus(201);
        $this->assertDatabaseHas('tingkat_depresi', [
            'kode_depresi' => 'P005',
            'depresi' => 'Depresi Sangat Berat',
        ]);
    }
}