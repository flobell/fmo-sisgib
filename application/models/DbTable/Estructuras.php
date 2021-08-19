<?php
/**
 * Clase DbTable para administrar los archivos en la Base de Datos
 *
 * @author simón garcia
 */
class Application_Model_DbTable_Estructuras extends Application_Model_DbTable_Abstract
{ 
    protected $_schema = 'sch_sisgib';
    protected $_name = 'sch_sisgib.estructura';
    protected $_primary = 'codigo_estructura';
    protected $_sequence = true;
  
    protected $_dependentTables = array(
    'Application_Model_DbTable_Formato'
    );
}