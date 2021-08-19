<?php
/**
 * Clase DbTable para administrar los archivos en la Base de Datos
 *
 * @author simón garcia
 */
class Application_Model_DbTable_Formato extends Application_Model_DbTable_Abstract
{ 
    protected $_schema = 'sch_sisgib';
    protected $_name = 'formato';
    protected $_primary = 'id_formato';
    protected $_sequence = true;
    
    protected $_referenceMat = array (
        'estructura' => array(
            self::COLUMNS => 'fk_codigo_estructura',
            self::REF_TABLE_CLASS => 'Application_Model_DbTable_Estructuras',
            self::REF_COLUMNS => 'codigo_estructura'
        ),
    );

}