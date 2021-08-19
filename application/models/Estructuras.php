<?php

class Application_Model_Estructuras
{
    
    const CODIGO_ESTRUCTURA ='codigo_estructura';
    const TITULO ='titulo';
    const NUM_TIPO_REGISTROS ='num_tipo_registros';
    const ESTADO_ESTRUCTURA = 'estado_estructura';
    const CODIGO_BANCO = 'codigo_banco';
    const FECHA = 'fecha';

 
    
    public static function getAllEstructuras($codigo_estructura = '')
    {        
        $tMisEstructuras = new Application_Model_DbTable_Estructuras();
        $tFormato = new Application_Model_DbTable_Formato();
        $sel = $tMisEstructuras->select()
                ->setIntegrityCheck(false)
                ->from(array('e'=>$tMisEstructuras->info(Zend_Db_Table::NAME)),array(
                            self:: CODIGO_ESTRUCTURA =>'e.codigo_estructura',

                    Application_Model_Formato::ID_FORMATO         => 'de.id_formato',
                    Application_Model_Formato::CAMPO_RELACION     => 'de.campo_relacion',
                    Application_Model_Formato::TIPO_REGISTRO      => 'de.tipo_registro',
                    Application_Model_Formato::LONGITUD           => 'de.longitud',
                    Application_Model_Formato::TIPO_DATO          => 'de.tipo_dato',
                    Application_Model_Formato::POSICION_INICIAL   => 'de.posicion_inicial', 
                    Application_Model_Formato::POSICION_FINAL     => 'de.posicion_final',
                    Application_Model_Formato::ALINEACION         => 'de.alineacion',
                    Application_Model_Formato::OPERACION          => 'de.operacion',
                    Application_Model_Formato::FK_CODIGO_ESTRUCTURA  => 'de.fk_codigo_estructura',   
                          ))
                 
                        ->joinleft(array('de'=>$tFormato->info(Zend_Db_Table::NAME)),'e.codigo_estructura = de.fk_codigo_estructura', array(),$tFormato->info(Zend_Db_Table::SCHEMA));  
            if ($codigo_estructura != '') {
                $sel->where('codigo_estructura = ? ',$codigo_estructura);
            }
        //exit($sel->__toString());
        //Fmo_Logger::debug($sel->__tostring());
        return $sel;
    }
    
  
    
    public function addFilterByID($codigo_estructura, $igual = true)
    {
        return $this->_addFilterBy(self::CODIGO_ESTRUCTURA, $igual, $codigo_estructura);
    }
    
    public static function findByID($codigo_estructura)
    {
        return Application_Model_DbTable_Estructuras::findOneByColumn('codigo_estructura', $codigo_estructura);
    }
    
    public static function getEstructuras($estructura)
    {   
        $tblEstructuras = new Application_Model_DbTable_Estructuras();
        
        $sql = $tblEstructuras->select()
            ->from(array('e' => $tblEstructuras->info(Zend_Db_Table::NAME)), array(                
               
              'e.codigo_estructura',
              'e.titulo',
                
                ), $tblEstructuras->info(Zend_Db_Table::SCHEMA));
           if ($estructura != '') {
                $sql->where('codigo_banco = ? ',$estructura);
            }
        
    
          //exit($sql->__toString());
        return $tblEstructuras->getAdapter()->fetchPairs($sql);
    }
    
    public static function getFormatoTxt($codigo = '')
    {

        $tEstructura = new Application_Model_DbTable_Estructuras();
        $tFormato = new Application_Model_DbTable_Formato();

        $sel = $tEstructura->select()
                ->setIntegrityCheck(false)
                ->from(array('e' => $tEstructura->info(Zend_Db_Table::NAME)), array(
                    self:: CODIGO_BANCO,

                    Application_Model_Formato::CAMPO_RELACION     => 'f.campo_relacion',
                    Application_Model_Formato::TIPO_REGISTRO      => 'f.tipo_registro',
                    Application_Model_Formato::LONGITUD           => 'f.longitud',
                    Application_Model_Formato::TIPO_DATO          => 'f.tipo_dato',
                    Application_Model_Formato::POSICION_INICIAL   => 'f.posicion_inicial', 
                    Application_Model_Formato::POSICION_FINAL     => 'f.posicion_final',
                    Application_Model_Formato::ALINEACION         => 'f.alineacion',
                    Application_Model_Formato::OPERACION          => 'f.operacion',
                ))
                
                ->joinleft(array('f' => $tFormato->info(Zend_Db_Table::NAME)), 'e.codigo_estructura=f.fk_codigo_estructura', array(), $tFormato->info(Zend_Db_Table::SCHEMA));

        if ($codigo != '') {
            $sel->where('codigo_banco = ? ',$codigo);
        }
        //exit($sel->__toString());
        //return $tProveedores->getDefaultAdapter()->fetchAll($sel);
        return $sel;
    }
}
