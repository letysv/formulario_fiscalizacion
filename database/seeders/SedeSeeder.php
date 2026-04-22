<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sede;
use App\Models\Ayuntamiento;

class SedeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sedes = [
            'Xalapa' => [
                'Acajete', 'Acatlán', 'Actopan', 'Alto Lucero de Gutiérrez Barrios',
                'Altotonga', 'Apazapan', 'Atzalan', 'Ayahualulco', 'Banderilla',
                'Boca del Río', 'Chiconquiaco', 'Coacoatzintla', 'Coatepec',
                'Colipa', 'Comapa', 'Cosautilán de Carvajal', 'Emiliano Zapata',
                'Ixhuacán de los Reyes', 'Jalacingo', 'Jalcomulco', 'Jilotepec',
                'Juchique de Ferrer', 'La Antigua', 'Landero y Coss', 'Las Minas',
                'Las Vigas de Ramírez', 'Medellín de Bravo', 'Miahuatlán',
                'Misantla', 'Naolinco', 'Paso de Ovejas', 'Perote', 'Puente Nacional',
                'Rafael Lucio', 'Sochiapa', 'Tatatila', 'Tenochtitlán', 'Teocelo',
                'Tepetlán', 'Tlacolulan', 'Tlacotepec de Mejía', 'Tlalnehuayocan',
                'Tlaltetela', 'Tlapacyan', 'Tonayán', 'Totutla', 'Úrsulo Galván',
                'Veracruz', 'Villa Aldama', 'Xalapa', 'Xico', 'Yecuatla'
            ],
            'Papantla' => [
                'Álamo Temapache', 'Benito Juárez', 'Castillo de Teayo',
                'Cazones de Herrera', 'Cerro Azul', 'Chalma', 'Chiconamel',
                'Chicontepec', 'Chinampa de Gorostiza', 'Chontla', 'Chumatlán',
                'Citlaltépetl', 'Coahuitlán', 'Coatzintla', 'Coxquihui',
                'Coyutla', 'El Higo', 'Espinal', 'Filomeno Mata',
                'Gutiérrez Zamora', 'Huayacocotla', 'Ilamatlán', 'Ixcatepec',
                'Ixhuatlán de Madero', 'Martínez de la Torre', 'Mecatlán',
                'Naranjos Amatlán', 'Nautla', 'Ozuluama', 'Pánuco', 'Papantla',
                'Platón Sánchez', 'Poza Rica de Hidalgo', 'Pueblo Viejo',
                'San Rafael', 'Tamalín', 'Tamiahua', 'Tampico Alto', 'Tanocco',
                'Tantima', 'Tantoyuca', 'Tecolutla', 'Tempoal', 'Tepetzintla',
                'Texcatepec', 'Tihuatlán', 'Tlachichilco', 'Tuxpan',
                'Vega de Alatorre', 'Zacualpan', 'Zontecomatlán', 'Zozocolco de Hidalgo'
            ],
            'Orizaba' => [
                'Acultzingo', 'Alpatláhuac', 'Amatlán de los Reyes', 'Aquila',
                'Astacinga', 'Atlahuilco', 'Atoyac', 'Atzacan', 'Calcahualco',
                'Camarón de Tejeda', 'Camerino Z. Mendoza', 'Carrillo Puerto',
                'Chocamán', 'Coetzala', 'Córdoba', 'Coscomatepec', 'Cotaxtla',
                'Cuitláhuac', 'Fortín', 'Huatusco', 'Huiloapan de Cuauhtémoc',
                'Ignacio de la Llave', 'Ixhuatlán del Café', 'Ixhuatlancillo',
                'Ixtaczoquitlán', 'Jamapa', 'La Perla', 'Los Reyes', 'Magdalena',
                'Maltrata', 'Manlio Fabio Altamirano', 'Mariano Escobedo',
                'Mixtla de Altamirano', 'Naranjal', 'Nogales', 'Orizaba',
                'Paso del Macho', 'Rafael Delgado', 'Río Blanco',
                'San Andrés Tenejapan', 'Soledad Atzompa', 'Soledad de Doblado',
                'Tehuipango', 'Tenampa', 'Tepatlaxco', 'Tequila', 'Texhuacán',
                'Tezonapa', 'Tlalixcoyan', 'Tlaquilpa', 'Tlilapan', 'Tomatlán',
                'Xoxocotla', 'Yanga', 'Zentla', 'Zongolica'
            ],
            'Santiago Tuxtla' => [
                'Acayucan', 'Acula', 'Agua Dulce', 'Alvarado', 'Amatitlán',
                'Ángel R. Cabada', 'Carlos A. Carrillo', 'Catemaco',
                'Chacaltianguis', 'Chinameca', 'Coatzacoalcos', 'Cosamaloapan',
                'Cosoleacaque', 'Cuichapa', 'Hidalgotitlán', 'Hueyapan de Ocampo',
                'Isla', 'Ixhuatlán del Sureste', 'Ixmatlahuacan', 'Jáltipan',
                'Jesús Carranza', 'José Azueta', 'Juan Rodríguez Clara',
                'Las Choapas', 'Lerdo de Tejada', 'Mecayapan', 'Minatitlán',
                'Moloacán', 'Nanchital de Lázaro Cárdenas del Río', 'Oluta',
                'Omealca', 'Otatitlán', 'Oteapan', 'Pajapan', 'Playa Vicente',
                'Saltabarranca', 'San Andrés Tuxtla', 'San Juan Evangelista',
                'Santiago Sochiapan', 'Santiago Tuxtla', 'Sayula de Alemán',
                'Soconusco', 'Soteapan', 'Tatahuicapan de Juárez', 'Texistepec',
                'Tierra Blanca', 'Tlacojalpan', 'Tlacotalpan', 'Tres Valles',
                'Tuxtilla', 'Uxpanapa', 'Zaragoza'
            ]
        ];

        foreach ($sedes as $sedeNombre => $ayuntamientos) {
            $sede = Sede::create(['nombre' => $sedeNombre]);
            
            foreach ($ayuntamientos as $ayuntamientoNombre) {
                Ayuntamiento::create([
                    'nombre' => $ayuntamientoNombre,
                    'sede_id' => $sede->id
                ]);
            }
        }
    }
}