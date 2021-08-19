<?php

/**
 * Description of Solicitud
 *
 * @author simon
 */

class Application_Model_Relacion
{
    //Campos de la tabla proveedores
    const ID_RELACION  = 'id_relacion';
    const NOMBRE = 'NOMBRE';

    public function addFilterByRIF($id_relacion, $igual = true)
    {
        return $this->_addFilterBy(self::ID_RELACION, $igual, $id_relacion);
    }
    
    public static function findByRIF($id_relacion)
    {
        return Application_Model_DbTable_Relacion::findOneByColumn('id_relacion', $id_relacion);
    }
    
 
}