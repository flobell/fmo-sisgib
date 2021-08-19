<?php

/**
 * Description of Solicitud
 *
 * @author simon
 */

class Application_Model_Bancos
{
    //Campos de la tabla bancos
    const CODIGO  = 'codigo';
    const NOMBRE_BANCO = 'nombre_banco';
    const BCV = 'bcv';
    const SWIFT = 'swift';

    
    
public function addFilterByCodigo($codigo, $igual = true)
    {
        return $this->_addFilterBy(self::CODIGO, $igual, $codigo);
    }    

 public static function findByCodigo($codigo)
    {
        return Application_Model_DbTable_Bancos::findOneByColumn('codigo', $codigo);
    }
    
    public static function findByBcv($bcv)
    {
        return Application_Model_DbTable_Bancos::findOneByColumn('bcv', $bcv);
    }
    
}