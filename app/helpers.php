<?php

if (!function_exists('get_countries')) {
    function get_countries(string $language = 'spa', bool $only_common = true, bool $sort = true)
    {
        $url = "https://restcountries.com/v3.1/all?fields=translations";
        $response = file_get_contents($url);

        if ($response === false) {
            return [];
        }

        $data = json_decode($response, true);

        $countries = [];
        foreach ($data as $country) {
            if ($only_common) {
                if (isset($country['translations'][$language]['common'])) {
                    $countries[] = $country['translations'][$language]['common'];
                }
            } else {
                if (isset($country['translations'][$language])) {
                    $countries[] = $country['translations'][$language];
                }
            }
        }

        if ($sort) {
            if (class_exists(Collator::class)) {
                $collator = new Collator($language === 'spa' ? 'es_ES' : 'en_US');
                $collator->sort($countries);
            } else {
                sort($countries, SORT_NATURAL | SORT_FLAG_CASE);
            }
        }

        return $countries;
    }
}

if (!function_exists('format_datetime')) {
    function format_datetime(DateTimeInterface $date, bool $short = false, bool $includeTime = false): string
    {
        $timezone = new DateTimeZone('America/Guayaquil');
        $localDate = DateTimeImmutable::createFromInterface($date)->setTimezone($timezone);

        $day = (int) $localDate->format('j');
        $month = (int) $localDate->format('n');
        $year = $localDate->format('Y');
        $time = $localDate->format('H\\hi');
        if ($short) {
            $shortMonths = [
                1 => 'ene',
                2 => 'feb',
                3 => 'mar',
                4 => 'abr',
                5 => 'may',
                6 => 'jun',
                7 => 'jul',
                8 => 'ago',
                9 => 'sep',
                10 => 'oct',
                11 => 'nov',
                12 => 'dic',
            ];
            $monthLabel = $shortMonths[$month] ?? $localDate->format('M');
            $result = sprintf('%d %s, %s', $day, $monthLabel, $year);

            return $includeTime ? sprintf('%s %s', $result, $time) : $result;
        }
        $months = [
            1 => 'enero',
            2 => 'febrero',
            3 => 'marzo',
            4 => 'abril',
            5 => 'mayo',
            6 => 'junio',
            7 => 'julio',
            8 => 'agosto',
            9 => 'septiembre',
            10 => 'octubre',
            11 => 'noviembre',
            12 => 'diciembre',
        ];
        $monthLabel = $months[$month] ?? $localDate->format('F');
        $result = sprintf('%d de %s, %s', $day, $monthLabel, $year);

        return $includeTime ? sprintf('%s %s', $result, $time) : $result;
    }
}
