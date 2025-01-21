<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

class SecretariasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        self::generar_secretarias();
        self::generar_universidades();
        self::dependencias_programas();
    }

    public static function dependencias_programas()
    {

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'MASTER',
            'siglas' => 'MASTER',
            'type' => 'ADMIN'
        ]);
        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Sistema DIF Tamaulipas',
            'siglas' => 'DIF',
            'type' => 'DEPENDENCIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Centros de Conciliación Laboral de Tamaulipas',
            'siglas' => 'CCLT',
            'type' => 'DEPENDENCIA'

        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Comisión Estatal de Mejora Regulatoria',
            'siglas' => 'CEMER',
            'type' => 'DEPENDENCIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Comisión Estatal para la Protección contra Riesgos Sanitarios',
            'siglas' => 'COEPRIS',
            'type' => 'DEPENDENCIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Comisión de Caza y Pesca Deportiva de Tamaulipas',
            'siglas' => 'CCPDT',
            'type' => 'DEPENDENCIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Comisión de Parques y Biodiversidad de Tamaulipas',
            'siglas' => 'CPBT',
            'type' => 'DEPENDENCIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Comapa Zona Conurbada',
            'siglas' => 'CZC',
            'type' => 'DEPENDENCIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Consejo Tamaulipeco de Ciencia y la Tecnología',
            'siglas' => 'COTACYT',
            'type' => 'DEPENDENCIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Centro Regional de Formación Docente e Investigación Educativa',
            'siglas' => 'CEFIRE',
            'type' => 'DEPENDENCIA'
        ]);


        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Fondo Tamaulipas',
            'siglas' => 'FT',
            'type' => 'DEPENDENCIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Fiscalía Especializada en Combate a la Corrupción',
            'siglas' => 'FECC',
            'type' => 'DEPENDENCIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Información Pública del Estado de Tamaulipas',
            'siglas' => 'IPET',
            'type' => 'DEPENDENCIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Instituto de Defensoría Pública del Estado de Tamaulipas',
            'siglas' => 'IDPET',
            'type' => 'DEPENDENCIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Instituto de Previsión y Seguridad Social del Estado de Tamaulipas',
            'siglas' => 'IPSS',
            'type' => 'DEPENDENCIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Instituto del Deporte',
            'siglas' => 'ID',
            'type' => 'DEPENDENCIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Instituto Metropolitano de Planeación del Sur de Tamaulipas',
            'siglas' => 'IMPST',
            'type' => 'DEPENDENCIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Instituto Tamaulipeco de Becas, Estímulos y Créditos Educativos',
            'siglas' => 'ITBECE',
            'type' => 'DEPENDENCIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Instituto Tamaulipeco de Infraestructura Física Educativa',
            'siglas' => 'ITIFE',
            'type' => 'DEPENDENCIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Instituto Tamaulipeco de Vivienda y Urbanismo',
            'siglas' => 'ITVU',
            'type' => 'DEPENDENCIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Instituto Tamaulipeco para la Cultura y las Artes',
            'siglas' => 'ITCA',
            'type' => 'DEPENDENCIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Instituto Tamaulipeco para los Migrantes',
            'siglas' => 'ITM',
            'type' => 'DEPENDENCIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Jóvenes Tamaulipas',
            'siglas' => 'JT',
            'type' => 'DEPENDENCIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Mujeres Tamaulipas',
            'siglas' => 'MT',
            'type' => 'DEPENDENCIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Oficina del Gobierno de Tamaulipas en CDMX',
            'siglas' => 'OGTCDMX',
            'type' => 'DEPENDENCIA'

        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Oficina del Gobierno de Tamaulipas en Nuevo León',
            'siglas' => 'OGTNL',
            'type' => 'DEPENDENCIA'

        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Periódico Oficial',
            'siglas' => 'PO',
            'type' => 'DEPENDENCIA'

        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Portal de información sobre el Coronavirus',
            'siglas' => 'PIC',
            'type' => 'DEPENDENCIA'

        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Portal de Datos Abiertos',
            'siglas' => 'PDA',
            'type' => 'DEPENDENCIA'

        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Protección Civil',
            'siglas' => 'PC',
            'type' => 'DEPENDENCIA'

        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Radio Tamaulipas',
            'siglas' => 'RT',
            'type' => 'DEPENDENCIA'

        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Red para la Evaluación e Investigación del Sector Público',
            'siglas' => 'REISP',
            'type' => 'DEPENDENCIA'

        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Red Temática de Salud Pública',
            'siglas' => 'RTSP',
            'type' => 'DEPENDENCIA'

        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Secretariado Ejecutivo del Sistema Estatal de Seguridad Pública',
            'siglas' => 'SESESP',
            'type' => 'DEPENDENCIA'

        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Sistema Estatal Anticorrupción',
            'siglas' => 'SEA',
            'type' => 'DEPENDENCIA'

        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Sistema Estatal de Protección Integral de los Derechos de Niñas, Niños y Adolescentes',
            'siglas' => 'SEPINNA',
            'type' => 'DEPENDENCIA'

        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Sistema Intersectorial de Protección y Gestión Integral de Derechos de las Personas con Discapacidad',
            'siglas' => 'SIPGIDPD',
            'type' => 'DEPENDENCIA'

        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Unidad de Inteligencia Financiera y Económica',
            'siglas' => 'UIFE',
            'type' => 'DEPENDENCIA'
        ]);
    }
    public static function generar_secretarias()
    {

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Jefe de la Oficina del Gobernador',
            'siglas' => 'JOG',
            'type' => 'SECRETARIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Secretaría General de Gobierno',
            'siglas' => 'SGG',
            'type' => 'SECRETARIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Contraloría Gubernamental',
            'siglas' => 'CG',
            'type' => 'SECRETARIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Secretaría de Administración',
            'siglas' => 'SA',
            'type' => 'SECRETARIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Secretaría de Bienestar Social',
            'siglas' => 'SBS',
            'type' => 'SECRETARIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Secretaría de Desarrollo Energético',
            'siglas' => 'SDE',
            'type' => 'SECRETARIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Secretaría de Desarrollo Rural, Pesca y Acuacultura',
            'siglas' => 'SDRPA',
            'type' => 'SECRETARIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Secretaría de Desarrollo Urbano y Medio Ambiente',
            'siglas' => 'SDUMA',
            'type' => 'SECRETARIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Secretaría de Economía',
            'siglas' => 'SECO',
            'type' => 'SECRETARIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Secretaría de Educación',
            'siglas' => 'SE',
            'type' => 'SECRETARIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Secretaría de Finanzas',
            'siglas' => 'SF',
            'type' => 'SECRETARIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Secretaría de Obras Públicas',
            'siglas' => 'SOP',
            'type' => 'SECRETARIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Secretaria de Recursos Hidraulicos para el Desarrollo Social',
            'siglas' => 'SRHDS',
            'type' => 'SECRETARIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Secretaría de Salud',
            'siglas' => 'SS',
            'type' => 'SECRETARIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Secretaría de Seguridad Pública',
            'siglas' => 'SSP',
            'type' => 'SECRETARIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Secretaría del Trabajo',
            'siglas' => 'ST',
            'type' => 'SECRETARIA'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Secretaría de Turismo',
            'siglas' => 'STUR',
            'type' => 'SECRETARIA'
        ]);
    }
    public static function generar_universidades()
    {

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Colegio de Bachilleres de Tamaulipas',
            'siglas' => 'COBAT',
            'type' => 'UNIVERSIDAD'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Colegio de Tamaulipas',
            'siglas' => 'COT',
            'type' => 'UNIVERSIDAD'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'CONALEP Tamaulipas',
            'siglas' => 'CONALEP',
            'type' => 'UNIVERSIDAD'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Instituto Tamaulipeco de Educación para Adultos',
            'siglas' => 'ITEA',
            'type' => 'UNIVERSIDAD'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Instituto Tamaulipeco de Capacitación para el Empleo',
            'siglas' => 'ITACE',
            'type' => 'UNIVERSIDAD'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Instituto Tecnológico Superior de El Mante',
            'siglas' => 'ITSMANTE',
            'type' => 'UNIVERSIDAD'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Universidad Politécnica de Altamira',
            'siglas' => 'UPA',
            'type' => 'UNIVERSIDAD'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Universidad Politécnica de la Región Ribereña',
            'siglas' => 'UPRR',
            'type' => 'UNIVERSIDAD'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Universidad Politécnica de Victoria',
            'siglas' => 'UPV',
            'type' => 'UNIVERSIDAD'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Universidad Tecnológica de Altamira',
            'siglas' => 'UTA',
            'type' => 'UNIVERSIDAD'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Universidad Tecnológica de Matamoros',
            'siglas' => 'UTM',
            'type' => 'UNIVERSIDAD'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Universidad Tecnológica de Nuevo Laredo',
            'siglas' => 'UTNL',
            'type' => 'UNIVERSIDAD'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Universidad Tecnológica de Tamaulipas Norte',
            'siglas' => 'UTTN',
            'type' => 'UNIVERSIDAD'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Universidad Tecnológica del Mar',
            'siglas' => 'UTMAR',
            'type' => 'UNIVERSIDAD'
        ]);

        \App\Models\Secretaria::factory()->create([
            'nombre' => 'Universidad de Seguridad y Justicia de Tamaulipas',
            'siglas' => 'USJT',
            'type' => 'UNIVERSIDAD'
        ]);
    }
}
