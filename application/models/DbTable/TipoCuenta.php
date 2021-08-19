<?php
/**
 * Clase DbTable para administrar los archivos en la Base de Datos
 *
 * @author simón garcia
 */
class Application_Model_DbTable_TipoCuenta extends Application_Model_DbTable_Abstract
{
    protected $_schema = 'sch_sisgib';
    protected $_name = 'tipo_cuenta';
    protected $_primary = 'id_tipo';
    protected $_sequence = true;
    
}