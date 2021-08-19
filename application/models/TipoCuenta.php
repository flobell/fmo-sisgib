<?php

/**
 * Description of Solicitud
 *
 * @author simon
 */

class Application_Model_TipoCuenta
{
    //Campos de la tabla tipocuenta
    const ID_TIPO  = 'id_tipo';
    const TIPO = 'tipo';

    
    
public function addFilterByCodigo($id_tipo, $igual = true)
    {
        return $this->_addFilterBy(self::ID_TIPO, $igual, $id_tipo);
    }    

 public static function findByCodigo($id_tipo)
    {
        return Application_Model_DbTable_TipoCuenta::findOneByColumn('id_tipo', $id_tipo);
    }
    
}