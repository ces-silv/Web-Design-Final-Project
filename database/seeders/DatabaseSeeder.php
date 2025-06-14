<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        DB::table('faculties')->insert([
            ['name' => 'Facultad de Ciencias Administrativas y Económicas'],
            ['name' => 'Ciencias Jurídicas, Humanidades y Relaciones Internacionales'],
            ['name' => 'Ciencias Médicas'],
            ['name' => 'Ingeniería y Arquitectura'],
            ['name' => 'Marketing, Diseño y Ciencias de la Comunicación'],
            ['name' => 'Odontología'],
        ]);

    }
}
