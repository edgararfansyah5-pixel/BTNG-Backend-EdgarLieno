<?php

namespace Database\Seeders;

use App\Models\Mobil;
use Illuminate\Database\Seeder;

class MobilSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['Avanza 1.5 G CVT', 'Toyota', 275000000, 2024, 'CVT', 'Bensin', 'tersedia'],
            ['Civic RS Turbo', 'Honda', 615000000, 2023, 'CVT', 'Bensin', 'tersedia'],
            ['Xpander Ultimate', 'Mitsubishi', 280000000, 2023, 'CVT', 'Bensin', 'tersedia'],
            ['Fortuner 2.4 VRZ', 'Toyota', 560000000, 2022, 'Matic', 'Diesel', 'sold'],
            ['Brio RS', 'Honda', 220000000, 2024, 'CVT', 'Bensin', 'tersedia'],
            ['Agya 1.2 G', 'Toyota', 175000000, 2023, 'Manual', 'Bensin', 'tersedia'],
            ['Ioniq 5 Signature', 'Hyundai', 780000000, 2024, 'Matic', 'Listrik', 'tersedia'],
            ['Innova Zenix Hybrid', 'Toyota', 520000000, 2024, 'CVT', 'Hybrid', 'tersedia'],
            ['Pajero Sport Dakar', 'Mitsubishi', 600000000, 2022, 'Matic', 'Diesel', 'sold'],
            ['Terios R Deluxe', 'Daihatsu', 255000000, 2023, 'Manual', 'Bensin', 'tersedia'],
        ];

        foreach ($data as [$nama, $brand, $harga, $tahun, $transmisi, $bahanBakar, $status]) {
            // firstOrCreate => aman dijalankan berulang kali (tidak menggandakan data)
            Mobil::firstOrCreate(
                ['nama' => $nama, 'brand' => $brand],
                [
                    'harga' => $harga,
                    'tahun' => $tahun,
                    'transmisi' => $transmisi,
                    'bahan_bakar' => $bahanBakar,
                    'status' => $status,
                    'foto' => 'https://placehold.co/600x400?text='.urlencode("{$brand} {$nama}"),
                ]
            );
        }
    }
}
