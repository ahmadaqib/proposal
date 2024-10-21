<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\KondisiUser;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KondisiUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fills_table_correctly()
    {
        // Act: Isi tabel kondisi user
        $kondisiUser = new KondisiUser();
        $cfUser = $kondisiUser->fillTable();

        // Assert: Pastikan tabel diisi dengan benar
        $this->assertNotEmpty($cfUser);
        $this->assertEquals('Tidak Tahu', $cfUser[0]['kondisi']);
        $this->assertEquals(0.0, $cfUser[0]['nilai']);
    }
}