<?php
/**
 * Clase DbTable para administrar los archivos en la Base de Datos
 *
 * @author simón garcia
 */
class Application_Model_DbTable_CuentasProveedores extends Application_Model_DbTable_Abstract
{
    //protected $_schema = 'sch_sisgib';
    protected $_name = 'cuentas_proveedores';
    protected $_primary = 'numcuenta';
    protected $_sequence = true;
    
    protected $_referenceMap = array (
        'proveedores' => array(
            self::COLUMNS => 'fk_rif',
            self::REF_TABLE_CLASS => 'Application_Model_DbTable_Estructuras',
            self::REF_COLUMNS => 'rif'
        ),
    );
}