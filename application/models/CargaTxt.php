<?php

class Application_Model_CargaTxt
{
    const ID        = 'carga_id'; 
    const LETRA     = 'carga_letra';
    const CEDULA    = 'carga_cedula';
    const CUENTA    = 'carga_cuenta';
    const MONTO     = 'carga_monto';
    const NOMBRE    = 'carga_nombre';

    public static function getCarga()
    {        
        $tCargaTxt = new Application_Model_DbTable_CargaTxt();

        $select = $tCargaTxt->select()
        ->setIntegrityCheck(false)
        ->from(
            array('t1'=>$tCargaTxt->info(Zend_Db_Table::NAME)),
            array(
                self::ID        =>new Zend_Db_Expr("t1.id"),
                self::LETRA     =>new Zend_Db_Expr("'V'"),
                self::CEDULA    =>new Zend_Db_Expr("SUBSTRING(t1.registro,5,10)::INT"),
                self::CUENTA    =>new Zend_Db_Expr("t3.forp_ccc::TEXT"),
                self::MONTO     =>new Zend_Db_Expr("(CAST(SUBSTRING(t1.registro,25,10) AS float8)/100)::NUMERIC"),
                self::NOMBRE    =>new Zend_Db_Expr("rpad(t2.datb_apellid||' '||t2.datb_nombre,40)"),
            ),
            $tCargaTxt->info(Zend_Db_Table::SCHEMA)   
        )
        ->joinleft(array('t2'=>'sn_tdatbas'),'t2.datb_cedula = SUBSTRING(t1.registro,5,10)::INT', array(),'sch_rpsdatos')
        ->joinleft(array('t3'=>'sn_tforpago'),'t3.forp_cedula = t2.datb_cedula', array(),'sch_rpsdatos')
        ->where("t3.forp_prcs = 99")
        ->where("SUBSTRING(t1.registro,1,8) <> '00011107'");

        return $tCargaTxt->fetchAll($select);
    }

    public static function delete($id){
        $tCargaTxt = new Application_Model_DbTable_CargaTxt();
        $where = $tCargaTxt->getAdapter()->quoteInto('id = ?', $id);
        return $tCargaTxt->delete($where);
    }

}