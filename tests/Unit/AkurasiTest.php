<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Keputusan;
use App\Models\TingkatDepresi;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AkurasiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_tests_akurasi_diagnosa()
    {
        // Arrange: Siapkan data uji
        $dataUji = [
            [
                'gejala' => ['G001' => 0.6, 'G002' => 0.4],
                'expected' => 'P001'
            ],
            [
                'gejala' => ['G001' => 0.6, 'G006' => 1.0],
                'expected' => 'P002'
            ],
            // Tambahkan lebih banyak data uji sesuai kebutuhan
        ];

        // Isi tabel keputusan dan tingkat depresi
        $this->seedDatabase();

        foreach ($dataUji as $data) {
            // Act: Lakukan diagnosa
            $diagnosa = $this->diagnosa($data['gejala']);

            // Assert: Bandingkan hasil diagnosa dengan hasil yang diharapkan
            $this->assertEquals($data['expected'], $diagnosa['kode_depresi']);
        }
    }

    private function diagnosa($gejala)
    {
        $kodeGejala = array_keys($gejala);

        $depresi = TingkatDepresi::all();
        $arrGejala = [];

        foreach ($depresi as $depresiItem) {
            $cfArr = [
                "cf" => [],
                "kode_depresi" => $depresiItem->kode_depresi
            ];
            $ruleSetiapDepresi = Keputusan::whereIn("kode_gejala", $kodeGejala)
                ->where("kode_depresi", $depresiItem->kode_depresi)
                ->get();

            if ($ruleSetiapDepresi->isNotEmpty()) {
                foreach ($ruleSetiapDepresi as $ruleKey) {
                    $cf = $ruleKey->mb - $ruleKey->md;
                    array_push($cfArr["cf"], $cf);
                }
                $res = $this->getGabunganCf($cfArr);
                $arrGejala[] = $res;
            }
        }

        $diagnosa_dipilih = [];
        $int = 0.0;
        foreach ($arrGejala as $val) {
            if (floatval($val["value"]) > $int) {
                $diagnosa_dipilih["value"] = floatval($val["value"]);
                $diagnosa_dipilih["kode_depresi"] = $val["kode_depresi"];
                $int = floatval($val["value"]);
            }
        }

        return $diagnosa_dipilih;
    }

    private function getGabunganCf($cfArr)
    {
        if (empty($cfArr["cf"])) {
            return [
                "value" => 0,
                "kode_depresi" => $cfArr["kode_depresi"]
            ];
        }

        $cfoldGabungan = $cfArr["cf"][0];
        for ($i = 1; $i < count($cfArr["cf"]); $i++) {
            $cfoldGabungan = $cfoldGabungan + ($cfArr["cf"][$i] * (1 - $cfoldGabungan));
        }

        return [
            "value" => "$cfoldGabungan",
            "kode_depresi" => $cfArr["kode_depresi"]
        ];
    }

    private function seedDatabase()
    {
        // Pastikan bahwa data diisi sesuai kebutuhan
        $keputusanData = [
            ['kode_gejala' => 'G001', 'kode_depresi' => 'P001', 'mb' => 0.7, 'md' => 0.1],
            ['kode_gejala' => 'G002', 'kode_depresi' => 'P001', 'mb' => 0.8, 'md' => 0.2],
            ['kode_gejala' => 'G001', 'kode_depresi' => 'P002', 'mb' => 0.6, 'md' => 0.1],
            ['kode_gejala' => 'G006', 'kode_depresi' => 'P002', 'mb' => 0.9, 'md' => 0.3],
            // Tambahkan lebih banyak data keputusan sesuai kebutuhan
        ];

        $tingkatDepresiData = [
            ['kode_depresi' => 'P001', 'depresi' => 'Depresi Ringan'],
            ['kode_depresi' => 'P002', 'depresi' => 'Depresi Sedang'],
            // Tambahkan lebih banyak data tingkat depresi sesuai kebutuhan
        ];

        Keputusan::insert($keputusanData);
        TingkatDepresi::insert($tingkatDepresiData);
    }
}
