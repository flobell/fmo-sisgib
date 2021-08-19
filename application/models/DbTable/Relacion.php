<?php
/**
 * Clase DbTable para administrar los archivos en la Base de Datos
 *
 * @author simón garcia
 */
class Application_Model_DbTable_Relacion extends Application_Model_DbTable_Abstract
{ 
    protected $_name = 'relacion';
    protected $_schema = 'sch_sisgib';
    protected $_primary = 'id_relacion';
    protected $_sequence = true;
    
}