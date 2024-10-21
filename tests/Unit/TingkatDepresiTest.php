<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\TingkatDepresi;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TingkatDepresiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fills_table_correctly()
    {
        // Act: Isi tabel tingkat depresi
        $tingkatDepresi = new TingkatDepresi();
        $depresi = $tingkatDepresi->fillTable();

        // Assert: Pastikan tabel diisi dengan benar
        $this->assertNotEmpty($depresi);
        $this->assertEquals('P001', $depresi[0]['kode_depresi']);
        $this->assertEquals('Gangguan Mood', $depresi[0]['depresi']);
    }
}