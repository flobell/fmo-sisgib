<?php
/**
 * Clase DbTable para administrar los archivos en la Base de Datos
 *
 * @author simón garcia
 */
class Application_Model_DbTable_DetalleInterfaz extends Application_Model_DbTable_Abstract
{
    //protected $_schema = 'sch_sisgib';
    protected $_name = 'detalle_interfaz';
    protected $_primary = 'id_detalle';
    protected $_sequence = true;
    
    protected $_referenceMap = array (
        'interfaz' => array(
            self::COLUMNS => 'fk_id_interfaz',
            self::REF_TABLE_CLASS => 'Application_Model_DbTable_Interfaz',
            self::REF_COLUMNS => 'codigo_interfaz'
        ),
        'relacion' => array(
            self::COLUMNS => 'fk_id_relacion',
            self::REF_TABLE_CLASS => 'Application_Model_DbTable_Relacion',
            self::REF_COLUMNS => 'id_relacion'
        ),
        'tipo_registro' => array(
            self::COLUMNS => 'fk_id_registro',
            self::REF_TABLE_CLASS => 'Application_Model_DbTable_TipoRegistro',
            self::REF_COLUMNS => 'id_tiporegistro'
        ),
    );
}