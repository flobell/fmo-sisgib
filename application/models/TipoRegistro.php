<?php

/**
 * Description of Solicitud
 *
 * @author simon
 */

class Application_Model_TipoRegistro
{
    //Campos de la tabla proveedores
    const ID_REGISTRO  = 'id_registro';
    const NOMBRE = 'nombre';

    
    
public function addFilterByCodigo($id_registro, $igual = true)
    {
        return $this->_addFilterBy(self::ID_REGISTRO, $igual, $id_registro);
    }    

 public static function findByCodigo($id_registro)
    {
        return Application_Model_DbTable_TipoRegistro::findOneByColumn('id_registro', $id_registro);
    }
    
}