<?php

/**
 * Description of Solicitud
 *
 * @author simon
 */

class Application_Model_CuentasProveedores
{
    //Campos de la tabla proveedores
    const FK_RIF  = 'fk_rif';
    const BANCO= 'banco';
    const TIPOCUENTA = 'tipocuenta';
    const NUMCUENTA = 'numcuenta';
    const CODIGO_BANCO = 'codigo_banco';
    const ESTADO = 'estado';
    const NACIONALIDAD = "nacionalidad";
    
    public function addFilterByRif($rif, $igual = true)
    {
        return $this->_addFilterBy(self::FK_RIF, $igual, $rif);
    }
    
    public static function findByRif($rif)
    {
        return Application_Model_DbTable_CuentasProveedores::findOneByColumn('fk_rif', $rif);
    }
    
    
   
}