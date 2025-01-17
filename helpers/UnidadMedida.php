<?php

namespace App;

enum UNIDAD_MEDIDA: string
{
    case PORCENTAJE = 'porcentaje';
    case MONEDA = 'moneda';
}

class UnidadMedida
{
    public static function get_value(int $value, UNIDAD_MEDIDA $unidad_medida)
    {
        $prefix = self::get_prefix($unidad_medida);
        $suffix = self::get_suffix($unidad_medida);
        return $prefix . $value . $suffix;
    }
    public static function get_prefix($unidad_medida)
    {
        switch ($unidad_medida) {
            case UNIDAD_MEDIDA::PORCENTAJE:
                return '';
            case UNIDAD_MEDIDA::MONEDA:
                return '$';
            default:
                return '';
        }
    }
    public static function get_suffix($unidad_medida)
    {
        switch ($unidad_medida) {
            case UNIDAD_MEDIDA::PORCENTAJE:
                return '%';
            case UNIDAD_MEDIDA::MONEDA:
                return ' pesos';
            default:
                return '';
        }
    }
}
