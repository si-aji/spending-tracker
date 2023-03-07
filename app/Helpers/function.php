<?php

/**
 * Generate Avatar
 *
 * @param $name = String
 * @param $type = ['male', 'female', 'human', 'identicon', 'initials', 'bottts', 'avataaars', 'jdenticon', 'gridy', 'micah']
 */
function getAvatar($name, $type = 'initials')
{
    if ($type == 'custom') {
        $avatar = asset($name);
    } else {
        $avatar = 'https://avatars.dicebear.com/api/'.$type.'/'.$name.'.svg';
    }

    return $avatar;
}

/**
 * Format Rupiah
 *
 * Print number in Indonesian Rupiah
 */
function formatRupiah($number = 0, $prefix = true, $short = false)
{
    if($short){
        $number = shortNumber($number);
    } else {
        $number = round($number, 2);
        $decimal = null;
        $checkDecimal = explode('.', $number);
        if (count($checkDecimal) > 1) {
            $decimal = $checkDecimal[1];
        }
    
        if($number < 0){
            $number = '('.number_format((int) $number, 0, ',', '.').(! empty($decimal) ? ','.$decimal : '').')';
        } else {
            $number = number_format((int) $number, 0, ',', '.').(! empty($decimal) ? ','.$decimal : '');
        }
    }
    
    return ($prefix ? 'Rp ' : '').$number;
}

/**
 * Convert thousand
 */
function shortNumber($number)
{
    $negative = false;
    if($number < 0){
        $negative = true;
        $number *= -1;
    }

    $units = ['', 'K', 'M', 'B', 'T'];
    $i = 0;
    for($i = 0;$number >= 1000; $i++){
      $number /= 1000;
    }

    if($negative){
        $number *= -1;
    }
    
    return round($number, 2).$units[$i];
}