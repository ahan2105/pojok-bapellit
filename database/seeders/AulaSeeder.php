<?php

namespace Database\Seeders;

use App\Models\Aula;
use Illuminate\Database\Seeder;

class AulaSeeder extends Seeder
{
    public function run(): void
    {
        Aula::create([
            'nama' => 'Aula Wiradadaha',
            'kapasitas' => 100,
            'deskripsi' => 'Aula utama Bappetibangda, cocok untuk rapat koordinasi besar, sosialisasi program, dan acara seremonial.',
            'fasilitas' => ['Proyektor', 'AC', 'Sound System', 'Microphone Wireless'],
            'status_aktif' => true,
        ]);

        Aula::create([
            'nama' => 'Aula Wiratanuningrat',
            'kapasitas' => 80,
            'deskripsi' => 'Ruang serbaguna untuk rapat lintas bidang, FGD, dan pelatihan internal.',
            'fasilitas' => ['Proyektor', 'AC', 'Whiteboard'],
            'status_aktif' => true,
        ]);

        Aula::create([
            'nama' => 'Aula Wirahadinigrat',
            'kapasitas' => 60,
            'deskripsi' => 'Ruang lebih kecil dan intim, ideal untuk rapat internal bidang atau diskusi terbatas.',
            'fasilitas' => ['AC', 'TV', 'Meja Konferensi'],
            'status_aktif' => true,
        ]);
    }
}