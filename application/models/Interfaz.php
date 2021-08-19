<?php

/**
 * Description of Solicitud
 *
 * @author simon
 */

class Application_Model_Interfaz
{
    //Campos de la tabla interfaces
    const ID_INTERFAZ  = 'id_interfaz';
    const TITULO = 'titulo';
    const CLIENTE   = 'cliente';
    const ESTADO = 'estado';
    const CODIGO_BANCO = 'codigo_banco';
    const FECHA = 'fecha';
    const USUARIO = 'usuario';
    const NUMPAGOS = 'numpagos';
    const MONTO_TOTAL = 'monto_total';
    const CUENTAFMO = 'cuentafmo'; 
    const PARTES = 'partes'; 

    public static function getAllByCodigoEstado($codigo_banco = '')
    {

        $tInterfaz = new Application_Model_DbTable_Interfaz();
        
        $sel = $tInterfaz->select()
                ->setIntegrityCheck(false)
                ->from(array('i' => $tInterfaz->info(Zend_Db_Table::NAME)), array(
                    self:: ID_INTERFAZ => 'i.id_interfaz',
                    self:: TITULO => 'i.titulo',
                    self:: CLIENTE => 'i.cliente',
                    self:: USUARIO => 'i.usuario',
                    self:: FECHA => 'i.fecha',
                    self:: NUMPAGOS => 'i.numpagos',
                    self:: MONTO_TOTAL => 'i.monto_total',
                ));    
        $sel->order('i.id_interfaz desc');
        if ($codigo_banco != '') {
            $sel->where('codigo_banco = ? ',$codigo_banco)
                ->where('estado = 0');
                 
        }

        //exit($sel->__toString());
        //return $tInterfaz->getDefaultAdapter()->fetchAll($sel);
        return $sel;
    }
    

    public static function getAllByBanco($codigo_banco = '')
    {
        $tInterfaz = new Application_Model_DbTable_Interfaz();
        
        $sel = $tInterfaz->select()
                ->setIntegrityCheck(false)
                ->from(
                    array('i' => $tInterfaz->info(Zend_Db_Table::NAME)), 
                    array(
                        self::ID_INTERFAZ => 'i.id_interfaz',
                        self::TITULO => 'i.titulo',
                        self::CLIENTE => 'i.cliente',
                        self::ESTADO => 'i.estado',
                        self::USUARIO => 'i.usuario',
                        self::FECHA => new Zend_Db_Expr("to_char(i.fecha, 'DD-MM-YYYY')"),
                        self::NUMPAGOS => 'i.numpagos',
                        self::MONTO_TOTAL => 'i.monto_total',
                        self::PARTES => 'i.partes'
                    )
                )
                ->where('codigo_banco = ?',$codigo_banco);

        return $tInterfaz->fetchAll($sel);
    }
    
    public function addFilterByCodigoBanco($codigo_banco, $igual = true)
    {
        return $this->_addFilterBy(self::RIF, $igual, $codigo_banco);
    }
    
    public static function findByCodigoBanco($codigo_banco)
    {
        return Application_Model_DbTable_Interfaz::findOneByColumn('codigo_banco', $codigo_banco);
    }
    
    public function findOne()
    {
        $this->_limit = 1;

        $datos = $this->_execute();

        return isset($datos[0]) ? $datos[0] : null;
    }

    public static function getAllDetalles($codigo = '')
    {

        $tInterfaz = new Application_Model_DbTable_Interfaz();
        $tDetalle = new Application_Model_DbTable_DetalleInterfaz();
        $tBanco = new Application_Model_DbTable_Bancos();
        $sel = $tInterfaz->select()
                ->setIntegrityCheck(false)
                ->from(array('i' => $tInterfaz->info(Zend_Db_Table::NAME)), array(
                    self:: ID_INTERFAZ => 'i.id_interfaz',
                    self:: ESTADO => 'i.estado',
                    self:: CODIGO_BANCO => 'i.codigo_banco',
                    
                    Application_Model_DetalleInterfaz::RIF  => 'd.rif',
                    Application_Model_DetalleInterfaz::NACIONALIDAD => 'd.nacionalidad',
                    Application_Model_DetalleInterfaz::NOMBRE         => 'd.nombre',
                    Application_Model_DetalleInterfaz::NUMCUENTA     => 'd.numcuenta',
                    Application_Model_DetalleInterfaz::TIPO_CUENTA    => 'd.tipo_cuenta',
                    Application_Model_DetalleInterfaz::MONTO        => 'd.monto',
                    
                    Application_Model_Bancos:: NOMBRE_BANCO => 'b.nombre_banco',
                    
                ))
                ->joinleft(array('b' => $tBanco->info(Zend_Db_Table::NAME)), 'i.codigo_banco=b.codigo', array(), $tBanco->info(Zend_Db_Table::SCHEMA))
                ->joinleft(array('d' => $tDetalle->info(Zend_Db_Table::NAME)), 'i.id_interfaz=d.fk_id_interfaz', array(), $tDetalle->info(Zend_Db_Table::SCHEMA));
        if ($codigo != '') {
            $sel->where('id_interfaz = ? ',$codigo);
        }

        //exit($sel->__toString());
        //return $tInterfaz->getDefaultAdapter()->fetchAll($sel);
        return $sel;
    }
   
    
    public static function getDatosTxt($codigo = '', $grupo = 1)
    {   
        $tInterfaz = new Application_Model_DbTable_Interfaz();
        $tDetalle= new Application_Model_DbTable_DetalleInterfaz();
        $tProveedores = new Application_Model_DbTable_Proveedores();
        $tTrabajadores = new Fmo_DbTable_Rpsdatos_Elegible();   
        $cuentaFMO = new Application_Model_DbTable_CuentasFMO();
        //$tBanco = new Application_Model_DbTable_Bancos();
       

        $sel =  $tInterfaz->select()
        ->setIntegrityCheck(false)
        ->from(
            array('i' => $tInterfaz->info(Zend_Db_Table::NAME)), 
            array(
                self:: FECHA  => 'i.fecha',
                self:: MONTO_TOTAL => 'i.monto_total',
                self:: NUMPAGOS => 'i.numpagos',
                self:: CUENTAFMO => 'i.cuentafmo',
                self:: TITULO => 'i.titulo',
                self:: CLIENTE => 'i.cliente',
                self:: ID_INTERFAZ => 'i.id_interfaz',

                Application_Model_DetalleInterfaz::ID_DETALLE       => 'd.id_detalle',
                Application_Model_DetalleInterfaz::RIF              => 'd.rif',
                Application_Model_DetalleInterfaz::NOMBRE           => 'd.nombre',
                Application_Model_DetalleInterfaz::NUMCUENTA        => 'd.numcuenta',
                Application_Model_DetalleInterfaz::MONTO            => 'd.monto',
                Application_Model_DetalleInterfaz::TIPO_CUENTA      => 'd.tipo_cuenta',
                Application_Model_DetalleInterfaz::NACIONALIDAD     => 'd.nacionalidad',
                Application_Model_DetalleInterfaz::GRUPO            => 'd.grupo',

                Application_Model_Proveedores::CORREO               => 'p.correo',
                Application_Model_Proveedores::TELEFONO             => 'p.telefono',

                Application_Model_Trabajadores::TELEFONO_CELULAR  => 't.eleg_nrotelc',
                Application_Model_Trabajadores::CORREO_ELECTRONICO    => 't.eleg_email',

                Application_Model_CuentasFMO::RIF_EMPRESA           => 'e.rif',
                Application_Model_CuentasFMO::RAZON                 => 'e.razon',
                Application_Model_CuentasFMO::NRO_CONVENIO          => 'e.nro_convenio',
                Application_Model_CuentasFMO::NRO_EMPRESA           => 'e.nro_empresa',
                Application_Model_CuentasFMO::SERIAL                => 'e.serial',                    
            )
        )             
        ->joinleft(array('d' => $tDetalle->info(Zend_Db_Table::NAME)), 'i.id_interfaz=d.fk_id_interfaz', array(), $tDetalle->info(Zend_Db_Table::SCHEMA))
        ->joinleft(array('p' => $tProveedores->info(Zend_Db_Table::NAME)), 'd.rif=p.rif', array(), $tProveedores->info(Zend_Db_Table::SCHEMA))
        ->joinleft(array('t' => $tTrabajadores->info(Zend_Db_Table::NAME)), 'd.rif=t.eleg_cedula', array(), $tTrabajadores->info(Zend_Db_Table::SCHEMA))
        ->joinleft(array('e' => $cuentaFMO->info(Zend_Db_Table::NAME)), 'i.cuentafmo = e.numcuenta', array(), $tDetalle->info(Zend_Db_Table::SCHEMA));

        if($codigo != ''){ 
            $sel->where('id_interfaz = ? ',$codigo); 
            $sel->where('d.grupo = ? ',$grupo); 
        }
        $sel->order('d.id_detalle');
        //$sel->limit($limite, $offset);
        
        $query = $tInterfaz->select()
        ->setIntegrityCheck(false)
        ->from(
            array('foo' =>  new Zend_Db_Expr('(' . $sel . ')')),
            array(
                'foo.*',
                'monto_parte'       => new Zend_Db_Expr("SUM(foo.monto) OVER (PARTITION BY foo.id_interfaz)"),
                'numpagos_parte'    => new Zend_Db_Expr("COUNT(*) OVER (PARTITION BY foo.id_interfaz)")
            )
        );
        //Zend_Debug::dd("GRUPO: ".$grupo);
        //exit($sel->__toString());
        //Zend_Debug::dd($query->assemble());
        //Zend_Debug::dd(json_encode($tInterfaz->fetchAll($sel)->toArray()));
        return $tInterfaz->fetchAll($query);
    }
    
    /* public static function getNumPagos($codigo = '')
    {
        $tInterfaz = new Application_Model_DbTable_Interfaz();
        
        $sel = $tInterfaz->select()
                ->setIntegrityCheck(false)
                ->from(array('i' => $tInterfaz->info(Zend_Db_Table::NAME)), array(
                    
                    self:: NUMPAGOS => 'i.numpagos',

                ));

        if ($codigo != '') {
            $sel->where('id_interfaz = ? ',$codigo);
        }
        
        //exit($sel->__toString());
        return $tInterfaz->getDefaultAdapter()->fetchRow($sel)->{self::NUMPAGOS};
        //return $sel;
    }*/
    
    
    
    
}