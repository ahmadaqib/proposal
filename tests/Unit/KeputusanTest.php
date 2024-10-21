<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Keputusan;
use App\Models\TingkatDepresi;
use App\Models\Gejala;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KeputusanTest extends TestCase
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
        $keputusan = Keputusan::create($data);

        // Assert: Pastikan keputusan dibuat dengan benar
        $this->assertDatabaseHas('keputusan', [
            'kode_depresi' => 'P001',
            'kode_gejala' => 'G001',
        ]);
    }

    /** @test */
    public function it_has_depresi_relation()
    {
        // Arrange: Buat keputusan dan depresi
        $depresi = TingkatDepresi::create(['kode_depresi' => 'P001', 'depresi' => 'Gangguan Mood']);
        $keputusan = Keputusan::create([
            'kode_depresi' => 'P001',
            'kode_gejala' => 'G001',
            'mb' => 0.6,
            'md' => 0.2,
        ]);

        // Act: Ambil relasi depresi dari keputusan
        $relatedDepresi = $keputusan->depresi;

        // Assert: Pastikan relasi depresi benar
        $this->assertTrue($relatedDepresi->contains($depresi));
    }

    /** @test */
    public function it_has_gejala_relation()
    {
        // Arrange: Buat keputusan dan gejala
        $gejala = Gejala::create(['kode_gejala' => 'G001', 'gejala' => 'Merasa sedih']);
        $keputusan = Keputusan::create([
            'kode_depresi' => 'P001',
            'kode_gejala' => 'G001',
            'mb' => 0.6,
            'md' => 0.2,
        ]);

        // Act: Ambil relasi gejala dari keputusan
        $relatedGejala = $keputusan->gejala;

        // Assert: Pastikan relasi gejala benar
        $this->assertTrue($relatedGejala->contains($gejala));
    }

    /** @test */
    public function it_fills_table_correctly()
    {
        // Act: Isi tabel keputusan
        $keputusan = new Keputusan();
        $rules = $keputusan->fillTable();

        // Assert: Pastikan tabel diisi dengan benar
        $this->assertNotEmpty($rules);
        $this->assertEquals('P001', $rules[0]['kode_depresi']);
        $this->assertEquals('G001', $rules[0]['kode_gejala']);
    }
}