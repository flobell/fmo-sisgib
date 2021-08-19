<?php


class Application_Model_DbTable_Constituir extends Application_Model_DbTable_Abstract
{
    
    protected $_name = 'constituir';
    protected $_primary  = array('fk_codigo_detalle' , 'fk_codigo_estructura');
    protected $_sequence = false;
    protected $_referenceMap = array (
        'estructura' => array(
            self::COLUMNS => 'fk_codigo_estructura',
            self::REF_TABLE_CLASS => 'Application_Model_DbTable_Estructura',
            self::REF_COLUMNS => 'codigo_estructura'
        ),
        'detalle_estructura' => array(
            self::COLUMNS => 'fk_codigo_detalle',
            self::REF_TABLE_CLASS => 'Application_Model_DbTable_DetalleEstructura',
            self::REF_COLUMNS => 'codigo_detalle'
        ),
    );
}