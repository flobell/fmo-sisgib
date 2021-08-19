<?php
/**
 * Clase DbTable para administrar los archivos en la Base de Datos
 *
 * @author simón garcia
 */
class Application_Model_DbTable_Proveedores extends Application_Model_DbTable_Abstract
{
    //protected $_schema = 'sch_sisgib';
    protected $_name = 'proveedores';
    protected $_primary = 'rif';
    protected $_sequence = true;
    
    protected $_dependentTables = array(
    'Application_Model_DbTable_CuentasProveedores'
    );
    
}