<?php
/**
 * Clase DbTable para administrar los archivos en la Base de Datos
 *
 * @author simón garcia
 */
class Application_Model_DbTable_CuentasFMO extends Application_Model_DbTable_Abstract
{
    //protected $_schema = 'sch_sisgib';
    protected $_name = 'cuentas';
    protected $_primary = 'numcuenta';
    protected $_sequence = true;
    
}