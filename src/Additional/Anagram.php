<?php

declare(strict_types=1);

namespace App\Additional;

class Anagram
{
    static public function check(string $first, string $second): bool
    {
        $normalizedFirst = self::normalize($first);
        $normalizedSecond = self::normalize($second);
        
        return empty(array_diff_assoc($normalizedFirst, $normalizedSecond));
    }

    static private function normalize(string $characters): array
    {
        $lowerCaseCharacters = strtolower($characters);
        $splitCharacters = str_split($lowerCaseCharacters);
        $resultCharacters = array_filter(
            $splitCharacters,
            fn($character) => preg_match('/[a-z]/', $character)
        );

        return array_count_values($resultCharacters);
    }
}