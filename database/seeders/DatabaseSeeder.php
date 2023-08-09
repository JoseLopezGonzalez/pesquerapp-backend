<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        //PARA CREAR CON DATOS REALES

        // Almacenes
        DB::table('almacenes')->insert([
            'id' => 1,
            'nombre' => 'Cámara de congelados',
            'temperatura' => -18.50,
            'capacidad' => 80000.50,
        ]);

        // Articulos
        $articulosData = [
        ];
        // ... Inserta aquí todos los datos de la tabla 'articulos'

        DB::table('articulos')->insert($articulosData);

        // Articulos Materia Prima
        $articulosMateriaPrimaData = [
            // ... Inserta aquí todos los datos de la tabla 'articulos_materia_prima'
        ];
        DB::table('articulos_materia_prima')->insert($articulosMateriaPrimaData);

        // Cajas
        $cajasData = [
            // ... Inserta aquí todos los datos de la tabla 'cajas'
        ];
        DB::table('cajas')->insert($cajasData);

        // Categorias Articulos
        $categoriasArticulosData = [
            // ... Inserta aquí todos los datos de la tabla 'categorias_articulos'
        ];
        DB::table('categorias_articulos')->insert($categoriasArticulosData);

        // Especies
        $especiesData = [
            // ... Inserta aquí todos los datos de la tabla 'especies'
        ];
        DB::table('especies')->insert($especiesData);

        // Estados Palets
        $estadosPaletsData = [
            // ... Inserta aquí todos los datos de la tabla 'estados_palets'
        ];
        DB::table('estados_palets')->insert($estadosPaletsData);

        // Palets
        $paletsData = [
            // ... Inserta aquí todos los datos de la tabla 'palets'
        ];
        DB::table('palets')->insert($paletsData);

        // Zonas Captura
        $zonasCapturaData = [
            // ... Inserta aquí todos los datos de la tabla 'zonas_captura'
        ];
        DB::table('zonas_captura')->insert($zonasCapturaData);

        $this->command->info('Datos iniciales insertados exitosamente.');
    }
}
