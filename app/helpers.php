<?php

use Hamcrest\StringDescription;

if (!function_exists('generateSequentialNumber')){
    function generateSequentialNumber(string $model, ?string $initials = null, string $columnd = 'order_numer'): string
    {
        $rekamanTerakhir = $model::latest('id')->first();
        $angkaTerakhir = $rekamanTerakhir ? intval(substr($rekamanTerakhir->column, strlen($initials))) : 0;
        $angkaBaru = $angkaTerakhir + 1;
        return $initials . str_pad($angkaBaru, 8, '0', STR_PAD_LEFT );
    }
}
?>