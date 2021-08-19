<?php


class Application_Form_Reporte extends Fmo_Form_Abstract
{
    const E_TIPO_CLIENTE = 'tipocliente';
    //CONSTANTES PARA DATOS BANCARIOS PROVEEDORES
    const E_COD = 'cod';
    //CONSTANTES PARA LOS BOTONES DE GUARDADO Y CANCELAR
    const E_CARGAR = 'cargarTip';
    const E_CANCELAR = 'cancelarTip';
    

    /**
     * Inicialización del formulario
     */
    public function __construct()
    { 
        parent::__construct(null);
    }
    
 

    public function init()
    {
        $this->setAction($this->getView()->url());

        //$tCargar = new Application_Model_DbTable_Cargar();
 
///////////////////// DATOS DE INTERFAZ  ///////////////////////////////  

        $eTipoCliente = new Zend_Form_Element_Radio(self::E_TIPO_CLIENTE);
        $eTipoCliente->setLabel('Tipo de Cliente ')
                ->setDescription('  ')
                ->addMultiOption('false', 'Proveedores')
                ->addMultiOption('true', 'Trabajadores')
                ->setSeparator('')
                ->setValue('false');
        $this->addElement($eTipoCliente);
        


////////////// DATOS BANCARIOS ///////////////////////////////
        
        $eCodigo = new Zend_Form_Element_Select(self::E_COD);
        $eCodigo->setLabel('Código.')
               ->addMultiOption("",'Seleccionar Banco.')
               ->addMultiOptions(Application_Model_DbTable_Bancos::getPairsWithOrder('codigo', new Zend_Db_Expr("bcv ||' - ' || nombre_banco"), 'activo=true',  'codigo'))
               ->setAttrib('onchange', "this.form.submit();")
               ->setRequired();
        $this->addElement($eCodigo);
        
  
           
        
    ////////////// BOTONES DE GUARDADO Y CANCELADO ///////////////////////////////
        
        $eCargar = new Zend_Form_Element_Submit(self::E_CARGAR);
        $eCargar->setLabel('Generar')
                 ->setIgnore(true);
        $this->addElement($eCargar);

        $eCancelar = new Zend_Form_Element_Submit(self::E_CANCELAR);
        $eCancelar->setLabel('Cancelar')
                  ->setIgnore(true);
        $this->addElement($eCancelar);

        $this->setCustomDecorators();
    }

    /**
     * Método para inicializar los valores por defecto en el formulario
     *

     * @throws InvalidArgumentException
     */
    public function valoresPorDefecto($idCargar)
    {
        //$cargar= Application_Model_DbTable_Cargar::findOneById($idCargar);
/*
        $this->setDefault(self::E_ID, $proveedores->id)
             ->setDefault(self::E_RAZON, $proveedores->razon)
             ->setDefault(self::E_REPRESENTANTE, $proveedores->representante);
  */           
    }

    /**
     * Método para guardar los datos en la Base de Datos
     */
    public function guardarNuevo($params)
    {
        
        $tCargar = new Application_Model_DbTable_Cargar();
        try {
            
            //$tCentroAcopio->getDefaultAdapter()->beginTransaction();

            $registro = $tCargar->createRow();
            $registro->titulo = $params[self::E_TITULO];
            $registro->id = $params[self::E_ID];
            $registro->nacionalidad = $params[self::E_NACIONALIDAD];
            $registro->razon = $params[self::E_RAZON];
            $registro->representante = $params[self::E_REPRESENTANTE];
            $registro->numerocuenta = $params[self::E_NUMERO_CUENTA];
            $registro->tipocuenta = $params[self::E_TIPO_CUENTA];
            $registro->banco = $params[self::E_BANCO];
            $registro->monto = $params[self::E_MONTO];
            $registro->bancotxt = $params[self::E_BANCOTXT];


            
            $registro->save();
            //$tCentroAcopio->getDefaultAdapter()->commit();
        } catch (Exception $e) {
            //$tCentroAcopio->getDefaultAdapter()->rollBack();
            throw $e;
        }
    }

    /**
     * Método para guardar los datos en la Base de Datos
     */
    public function guardarModificacion($idCentroAcopio, $params)
    {
       /* $centroacopio = Application_Model_DbTable_CentroAcopio::findOneById($idCentroAcopio);

        $centroacopio->acopio = $params[self::E_ACOPIO];
        $centroacopio->patio= $params[self::E_PATIO];
        $centroacopio->lote= $params[self::E_LOTE];
     
        return $centroacopio->save();*/
    }


}