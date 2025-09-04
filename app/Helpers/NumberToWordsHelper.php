<?php

namespace App\Helpers;

class NumberToWordsHelper
{
    public static function numberToWords($number)
    {
        $ones = [
            0 => '', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
            5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen',
            14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen',
            18 => 'Eighteen', 19 => 'Nineteen'
        ];
        
        $tens = [
            2 => 'Twenty', 3 => 'Thirty', 4 => 'Forty', 5 => 'Fifty',
            6 => 'Sixty', 7 => 'Seventy', 8 => 'Eighty', 9 => 'Ninety'
        ];
        
        $scales = ['', 'Thousand', 'Million', 'Billion', 'Trillion'];
        
        if ($number == 0) {
            return 'Zero';
        }
        
        $number = (int)$number;
        $words = '';
        
        if ($number < 0) {
            $words = 'Negative ';
            $number = abs($number);
        }
        
        $scale = 0;
        
        while ($number > 0) {
            $hundreds = $number % 1000;
            if ($hundreds != 0) {
                $hundredWords = self::convertHundreds($hundreds, $ones, $tens);
                if ($scale > 0) {
                    $hundredWords .= ' ' . $scales[$scale];
                }
                if ($words != '' && $words != 'Negative ') {
                    $words = $hundredWords . ' ' . $words;
                } else {
                    $words = $hundredWords;
                }
            }
            $number = (int)($number / 1000);
            $scale++;
        }
        
        return trim($words);
    }
    
    private static function convertHundreds($number, $ones, $tens)
    {
        $words = '';
        
        if ($number >= 100) {
            $hundreds = (int)($number / 100);
            $words .= $ones[$hundreds] . ' Hundred';
            $number = $number % 100;
            if ($number > 0) {
                $words .= ' ';
            }
        }
        
        if ($number >= 20) {
            $ten = (int)($number / 10);
            $words .= $tens[$ten];
            $number = $number % 10;
            if ($number > 0) {
                $words .= ' ' . $ones[$number];
            }
        } elseif ($number > 0) {
            $words .= $ones[$number];
        }
        
        return $words;
    }
}
