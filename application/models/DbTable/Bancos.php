<?php
/**
 * Clase DbTable para administrar los archivos en la Base de Datos
 *
 * @author simón garcia
 */
class Application_Model_DbTable_Bancos extends Application_Model_DbTable_Abstract
{
    protected $_schema = 'sch_sisgib';
    protected $_name = 'sch_sisgib.bancos';
    protected $_primary = 'codigo';
    //protected $_primary = array('id_centro_acopio');
    protected $_sequence = true;
    protected $_referenceMat = array (
        'Bancos' => array(
            self::COLUMNS => 'codigo',
            self::REF_TABLE_CLASS => 'Application_Model_DbTable_Bancos',
            self::REF_COLUMNS => 'codigo'
        ),
    );
}