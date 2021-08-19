<?php
/**
 * Clase DbTable para administrar los archivos en la Base de Datos
 *
 * @author simón garcia
 */
class Application_Model_DbTable_TipoRegistro extends Application_Model_DbTable_Abstract
{
    //protected $_schema = 'sch_sisgib';
    protected $_name = 'registro';
    protected $_primary = 'id_registro';
    protected $_sequence = true;
    
    protected $_dependentTables = array(
    'Application_Model_DbTable_DetalleInterfaz'
    );
}