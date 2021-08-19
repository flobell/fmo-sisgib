<?php

/**
 * Description of Solicitud
 *
 * @author simon
 */

class Application_Model_Proveedores
{
    //Campos de la tabla proveedores
    const RIF  = 'rif';
    const NACIONALIDAD = 'nacionalidad';
    const RAZON   = 'razon';
    const REPRESENTANTE = 'representante';
    const TELEFONO = 'telefono';
    const DIRECCION = 'direccion';
    const CORREO = 'correo';
    const FECHA = 'fecha';
    
   
    public static function getAllCuentas($rif = '')
    {

        $tProveedores = new Application_Model_DbTable_Proveedores();
        $tCuentasProveedores = new Application_Model_DbTable_CuentasProveedores();
        $sel = $tProveedores->select()
                ->setIntegrityCheck(false)
                ->from(array('p' => $tProveedores->info(Zend_Db_Table::NAME)), array(
                    self:: RIF => 'p.rif',
                    
                    Application_Model_CuentasProveedores::CODIGO_BANCO  => 'cp.codigo_banco',
                    Application_Model_CuentasProveedores::BANCO         => 'cp.banco',
                    Application_Model_CuentasProveedores::NUMCUENTA     => 'cp.numcuenta',
                    Application_Model_CuentasProveedores::TIPOCUENTA    => 'cp.tipocuenta',
                    Application_Model_CuentasProveedores::ESTADO        => 'cp.estado',
                    Application_Model_CuentasProveedores::NACIONALIDAD  => 'cp.nacionalidad',
                ))
                
                ->joinleft(array('cp' => $tCuentasProveedores->info(Zend_Db_Table::NAME)), 'p.rif=cp.fk_rif', array(), $tCuentasProveedores->info(Zend_Db_Table::SCHEMA));
        if ($rif != '') {
            $sel->where('rif = ? ',$rif);
        }

        //exit($sel->__toString());
        //return $tProveedores->getDefaultAdapter()->fetchAll($sel);
        return $sel;
    }
    
    public function addFilterByRIF($rif, $igual = true)
    {
        return $this->_addFilterBy(self::RIF, $igual, $rif);
    }
    
    public static function findByRIF($rif)
    {
        return Application_Model_DbTable_Proveedores::findOneByColumn('rif', $rif);
    }
    
    public function findOne()
    {
        $this->_limit = 1;
        $datos = $this->_execute();
        return isset($datos[0]) ? $datos[0] : null;
    }

    public static function getCuentaByRif($rif)
    {
        $tProveedores = new Application_Model_DbTable_Proveedores();
        $tCuentasProveedores = new Application_Model_DbTable_CuentasProveedores();
        $sel = $tProveedores->select()
                ->setIntegrityCheck(false)
                ->from(array('p' => $tProveedores->info(Zend_Db_Table::NAME)), array(
                    self:: RAZON => 'p.razon',
                    self:: REPRESENTANTE => 'p.representante',
                    self:: NACIONALIDAD => 'p.nacionalidad',
                    Application_Model_CuentasProveedores::CODIGO_BANCO  => 'cp.codigo_banco',
                    Application_Model_CuentasProveedores::NUMCUENTA     => 'cp.numcuenta',
                    Application_Model_CuentasProveedores::TIPOCUENTA    => 'cp.tipocuenta',
                ))
                ->joinleft(array('cp' => $tCuentasProveedores->info(Zend_Db_Table::NAME)), 'p.rif=cp.fk_rif', array(), $tCuentasProveedores->info(Zend_Db_Table::SCHEMA));
        $sel->where('rif = ? ',$rif);
        return $tProveedores->getDefaultAdapter()->fetchRow($sel);
    }
}