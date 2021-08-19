<?php

/**
 * Description of Solicitud
 *
 * @author simon
 */

class Application_Model_CuentasFMO
{
    //Campos de la tabla cuentas
    const RIF  = 'rif';
    const RAZON   = 'razon';
    const BANCO= 'banco';
    const TIPOCUENTA = 'tipocuenta';
    const NUMCUENTA = 'numcuenta';
    const COD = 'cod';
    const FECHA = 'fecha';
    const NRO_CONVENIO = 'nro_convenio';
    const NRO_EMPRESA = 'nro_empresa';
    const SERIAL = 'serial';
    const RIF_EMPRESA = 'rif_empresa';


    public static function findCuentasByCodigo($codigo)
    {
        return Application_Model_DbTable_CuentasFMO::findOneByColumn('codigo_banco', $codigo);
    }
   
    public static function findByCuenta($numcuenta)
    {
        return Application_Model_DbTable_Proveedores::findOneByColumn('numcuenta', $numcuenta);
    }
    
    
}
