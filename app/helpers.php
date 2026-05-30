<?php

if (! function_exists('get_countries')) {
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
