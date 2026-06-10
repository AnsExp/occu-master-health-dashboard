<?php

namespace App\Http\Exams;

class AudiologyExam extends Exam
{
    public string $name = 'Examen de audiología';
    public string $slug = 'audiology-exam';
    public array $schema = [
        [
            'section' => 'Audición',
            'fields' => [
                [
                    'question' => 'Oído derecho',
                    'slug' => 'right_ear',
                    'type' => 'text',
                    'answer' => null,
                    'required' => true,
                ],
                [
                    'question' => 'Oído izquierdo',
                    'slug' => 'left_ear',
                    'type' => 'text',
                    'answer' => null,
                    'required' => true,
                ],
            ],
        ],
        [
            'section' => 'Prueba del habla y del susurro (3 metros)',
            'type' => 'radio',
            'fields' => [
                [
                    'question' => 'Oído derecho',
                    'slug' => 'right_ear',
                    'type' => 'radio',
                    'options' => ['Susurro', 'Normal'],
                    'answer' => null,
                    'required' => true,
                ],
                [
                    'question' => 'Oído izquierdo',
                    'slug' => 'left_ear',
                    'type' => 'radio',
                    'options' => ['Susurro', 'Normal'],
                    'answer' => null,
                    'required' => true,
                ],
            ],
        ],
        [
            'section' => 'Test de Ishihara',
            'type' => 'radio',
            'fields' => [
                [
                    'question' => 'Amarillo',
                    'slug' => 'yellow',
                    'type' => 'radio',
                    'options' => ['Correcto', 'Incorrecto'],
                    'answer' => null,
                    'required' => true,
                ],
                [
                    'question' => 'Rojo',
                    'slug' => 'red',
                    'type' => 'radio',
                    'options' => ['Correcto', 'Incorrecto'],
                    'answer' => null,
                    'required' => true,
                ],
            ],
        ],
    ];
}
