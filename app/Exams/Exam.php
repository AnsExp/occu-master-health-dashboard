<?php

namespace App\Http\Exams;

abstract class Exam
{
    public string $name = '';
    public string $slug = '';
    public string $version = '1.0';
    public array $schema = [];
    public bool $isActive = true;
}
