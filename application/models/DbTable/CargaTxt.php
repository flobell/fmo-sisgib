<?php
/**
 * Clase DbTable para administrar los archivos en la Base de Datos
 *
 */
class Application_Model_DbTable_CargaTxt extends Application_Model_DbTable_Abstract
{
    protected $_schema = 'sch_sisgib';
    protected $_name = 'carga_txt';
    protected $_primary = 'id';
    protected $_sequence = true;
}