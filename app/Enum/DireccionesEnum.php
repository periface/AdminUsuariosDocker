<?php

namespace App\Enum;

enum DireccionesEnum: int
{
    //
    case SELECCIONE = 0;
    case DIRECCION_GRAL_COMPRAS_Y_OPERACIONES = 1;
    case DIRECCION_CONTRATOS = 2;
    case DIRECCION_PATRIMONIO = 3;
    case CONTRALORIA_GUBERNAMENTAL = 4;
    case DIRECCION_PLANEACION_CONTROL_ESTRATEGICO = 5;


    public function direccion(): string {
        return match($this){
            self::SELECCIONE => 'Seleccione',
            self::DIRECCION_GRAL_COMPRAS_Y_OPERACIONES => 'Dirección Gral de Compras y Operaciones',
            self::DIRECCION_CONTRATOS => 'Dirección de Contratos',
            self::DIRECCION_PATRIMONIO => 'Dirección de Patrimonio',
            self::CONTRALORIA_GUBERNAMENTAL => 'Contraloría Gubernamental',
            self::DIRECCION_PLANEACION_CONTROL_ESTRATEGICO => 'Dirección de Planeación y Control Estratégico'
        };
    }

}
