<?php

namespace App\Models;

class Hierarchy
{
    public static $STACK = [
        [
            'section' => 'Vigía',
            'classifications' => [
                [
                    'name' => 'Gente de pesca',
                    'roles' => [
                        'Capitán de B/P',
                        'Patrón de altura B/P',
                        'Patrón costanero B/P',
                        'Marinero de Primera de Puente B/P',
                    ],
                ],
                [
                    'name' => 'Gente de mar',
                    'roles' => [
                        'Capitán de Altura',
                        'Primer Oficial de Cubierta',
                        'Segundo Oficial de Cubierta',
                        'Tercer Oficial de Cubierta',
                        'Patrón de Altura',
                        'Patrón costanero',
                        'Contramaestre',
                        'Marinero de Primera de Puente',
                        'Marinero de Cubierta',
                        'Marinero Electrotécnico',
                    ],
                ],
            ],
        ],
        [
            'section' => 'Maquinas',
            'classifications' => [
                [
                    'name' => 'Gente de pesca',
                    'roles' => [
                        'Jefe de Máquinas B/P',
                        'Primer Oficial de Máquinas B/P',
                        'Marinero de Primera de Máquinas B/P',
                        'Marinero de Máquinas B/P',
                    ],
                ],
                [
                    'name' => 'Gente de mar',
                    'roles' => [
                        'Jefe de Máquinas',
                        'Primer Oficial de Máquinas',
                        'Segundo Oficial de Máquinas',
                        'Tercer Oficial de Máquinas',
                        'Marinero de Primera de Máquinas',
                        'Marinero de Máquinas',
                    ],
                ],
            ],
        ],
        [
            'section' => 'Electrotécnicos',
            'classifications' => [
                [
                    'name' => 'Gente de pesca',
                    'roles' => [
                        'Mecánico de helicóptero',
                        'Piloto de helicóptero',
                        'Observado de pesca',
                        'Capitán de pesca',
                    ],
                ],
                [
                    'name' => 'Gente de mar',
                    'roles' => [
                        'Técnico de mantenimiento de equipos hoteleros',
                        'Informático',
                        'Oficial Electrotécnico',
                        'Marinero Electrotécnico',
                        'Director de crucero',
                        'Gerente hotelero',
                        'Asistente de gerente hotelero',
                        'Administrador',
                        'Guía de turismo',
                        'Guía naturalista',
                        'Gasfitero',
                        'Ama de llaves',
                        'Encargado de boutique',
                        'Mayordomo',
                        'Lavandera/o',
                        'Bodeguero/guardalmacén',
                        'Carpintero/ebanista',
                        'Profesor de idiomas',
                        'Masajista',
                        'Fotógrafo',
                        'Músico',
                        'Médico',
                        'Enfermero',
                        'Soldador',
                        'Tornero',
                        'Operador de bomba',
                        'Electricista',
                        'Refrigerante',
                        'Operador de grúa',
                        'Representante de armador',
                        'Contramaestre',
                        'y otros que considere la autoridad marítima',
                    ],
                ],
            ],
        ],
        [
            'section' => 'Subacuáticas',
            'classifications' => [
                [
                    'name' => 'Gente de mar',
                    'roles' => [
                        'Buzos científicos y recreativos',
                        'Buzos comerciales',
                    ],
                ],
            ],
        ],
    ];
}
