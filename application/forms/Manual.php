<?php


class Application_Form_Manual extends Fmo_Form_Abstract
{
    //CONSTANTES PARA DATOS DE TABLA REPORTE
    const E_DESCARGAR = 'descargar';

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
    
    /*
        $cboImplemento= new Zend_Form_Element_Select(self::E_IMPLEMENTO);
        $cboImplemento->setLabel('Implemento')
                ->addMultiOption(' ', 'Seleccione Implemento')
                ->addMultiOptions(Application_Model_Implemento::getLista());
        $this->addElement($cboImplemento);
    */
    public function init()
    {
        $this->setAction($this->getView()->url());

        //$tCargar = new Application_Model_DbTable_Cargar();
 
///////////////////// BOTON DE DESCARGA DE MANUAL  ///////////////////////////////  
        
        $eDescargar = new Zend_Form_Element_Button(self::E_DESCARGAR);
        $eDescargar->setLabel('Descargar')
                ->setIgnore(false);
        $this->addElement($eDescargar);
        
        
        
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