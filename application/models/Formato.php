<?php

class Application_Model_Formato
{
    //cambiar a columnas de detalle estructura
    const ID_FORMATO ='id_formato';
    const TIPO_REGISTRO ='tipo_registro';
    const LONGITUD ='longitud';
    const TIPO_DATO = 'tipo_dato';
    const POSICION_INICIAL = 'posicion_inicial';  
    const POSICION_FINAL = 'posicion_final';
    const ALINEACION = 'alineacion';
    const CAMPO_RELACION = 'campo_relacion';
    const OPERACION = 'operacion';
    const FK_CODIGO_ESTRUCTURA = 'fk_codigo_estructura';
    
    public function addFilterByID($codigo_estructura, $igual = true)
    {
        return $this->_addFilterBy(self::CODIGO_ESTRUCTURA, $igual, $codigo_estructura);
    }
    
    public static function findByID($codigo_estructura)
    {
        return Application_Model_DbTable_DetalleEstructuras::findOneByColumn('codigo_estructura', $codigo_estructura);
    }
}
