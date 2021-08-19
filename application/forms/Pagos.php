<?php


class Application_Form_Pagos extends Fmo_Form_Abstract
{
    //CONSTANTES PARA DATOS INTERFAZ A GENERAR - 
    const E_TIPO_CLIENTE = 'tipocliente';
    const E_ESTRUCTURA = 'estructura';
    const E_SELECCION = 'seleccion';
    const E_FECHA = 'fecha';
    const E_GENERAR = 'generar';

    
    //CONSTANTES PARA DATOS BANCARIOS PROVEEDORES
    const E_COD = 'cod';
    const E_BANCO = 'banco'; //USAR PARA LA TABLA TAMBIEN
    

    //CONSTANTES PARA LOS BOTONES DE GUARDADO Y CANCELAR
    const E_GUARDAR = 'guardarTip';
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
 
///////////////////// DATOS DE INTERFAZ  ///////////////////////////////  
      /*  
        $eTipoCliente = new Zend_Form_Element_Radio(self::E_TIPO_CLIENTE);
        $eTipoCliente->setLabel('Tipo de Cliente ')
                ->setDescription('  ')
                ->addMultiOption('false', 'Proveedores')
                ->addMultiOption('true', 'Trabajadores')
                ->setSeparator('')
                ->setValue('true');
        $this->addElement($eTipoCliente);
        */
        $eEstructura = new Zend_Form_Element_Select(self::E_ESTRUCTURA);
        $eEstructura->setLabel('Estructura Activa.')
               ->addMultiOption("",'SELECCIONE ESTRUCTURA')
               //->addMultiOptions(Application_Model_DbTable_Estructuras::getPairsWithOrder('codigo_estructura', new Zend_Db_Expr("titulo"), NULL,  'codigo_estructura'))
               //->setAttrib('onchange', "this.form.submit();")
               ->setRequired();
        $this->addElement($eEstructura);
    
       $eSeleccion = new Zend_Form_Element_Radio(self::E_SELECCION);
       $eSeleccion->clearValidators();
       $eSeleccion->setLabel('Selección')            
                ->setDescription('')
                ->setSeparator('')
                ->setValue('true');
       $this->addElement($eSeleccion);
       
       $eGenerar = new Zend_Form_Element_Radio(self::E_GENERAR);
       $eGenerar->setLabel('Generar')            
                ->setDescription('')
                ->setSeparator('')
                ->setValue('true');
       $this->addElement($eGenerar);
       
       $eFecha= new Zend_Form_Element_Hidden(self::E_FECHA); //acomodar
        $eFecha->setLabel('Fecha')
            ->setAttrib('size', '5')    
            ->setRequired(true)
            ->setValue(date('d/m/y'))
            ->setAttrib("readonly",""); 
        $this->addElement($eFecha);
           
       
////////////// DATOS BANCARIOS ///////////////////////////////
        
        $eCodigo = new Zend_Form_Element_Select(self::E_COD);
        $eCodigo->setLabel('Código.')
               ->addMultiOption("",'Seleccionar Banco.')
               ->addMultiOptions(Application_Model_DbTable_Bancos::getPairsWithOrder('codigo', new Zend_Db_Expr("bcv ||' - ' || nombre_banco"), "activo=true AND bcv NOT IN ('0001')",  'codigo'))
               ->setAttrib('onchange', "this.form.submit();")
               ->setRequired();
        $this->addElement($eCodigo);
     
  
    ////////////// BOTONES DE GUARDADO Y CANCELADO ///////////////////////////////
        
        $eGuardar = new Zend_Form_Element_Submit(self::E_GUARDAR);
        $eGuardar->setLabel('Generar Archivo')
                 ->setIgnore(true);
        $this->addElement($eGuardar);

        $eCancelar = new Zend_Form_Element_Submit(self::E_CANCELAR);
        $eCancelar->setLabel('Cancelar')
                  ->setIgnore(true);
        $this->addElement($eCancelar);
        $this->setCustomDecorators();
    }

    public function cargarEstructura($estructura)
    {
        //funcion que carga los plantas que dependen de una empresa
        $cboEstructura = $this->getElement(self::E_ESTRUCTURA);
        $cboEstructura->clearMultiOptions();
        $cboEstructura->addMultiOption("",'SELECCIONE ESTRUCTURA');
        $cboEstructura->addMultiOptions(Application_Model_Estructuras::getEstructuras($estructura));

    }
   
    
    private function _validarDatos($params)
    {  
        if (empty($params[self::E_COD])) {
            throw new Exception('Debe indicar un banco.');
        }
        if (empty($params[self::E_SELECCION])) {
            throw new Exception('Debe indicar una cuenta de FMO.');
        }
        if (empty($params[self::E_ESTRUCTURA])) {
            throw new Exception('Debe indicar un estructura.');
        }
        if (empty($params[self::E_GENERAR])) {
            throw new Exception('Debe indicar una interfaz.');
        }
        
        
    }
    
    /**
     * Método para inicializar los valores por defecto en el formulario
     *

     * @throws InvalidArgumentException
     */
    public function valoresPorDefecto($idPagos)
    {/*
        $valor= Application_Model_DbTable_Estructuras::findOneById($idCargar);

        $this->setDefault(self::E_COD, $valor->codigo_banco)
             //->setDefault(self::E_RAZON, $proveedores->razon)
             //->setDefault(self::E_REPRESENTANTE, $proveedores->representante);
        */ 
    }

    /**
     * Método para guardar los datos en la Base de Datos
     */
    public function generarNuevo($params, $id_interfaz)
    {   
        $this->_validarDatos($params);
        $interfaz = Application_Model_DbTable_Interfaz::findOneById($id_interfaz);
       
            
        try {
            //Transacciones
            Zend_Db_Table::getDefaultAdapter()->beginTransaction();
            
            $interfaz->cuentafmo = $params[self::E_SELECCION];
            $interfaz->fk_codigo_estructura = $params[self::E_ESTRUCTURA];
            $interfaz->estado = 1;
            $interfaz->fecha = $params[self::E_FECHA];
            
            $interfaz->save();
            
            Zend_Db_Table::getDefaultAdapter()->commit();
        } catch (Exception $e) {
            Zend_Db_Table::getDefaultAdapter()->rollBack();
            throw $e;
        }
    }

    /**
     * Método para guardar los datos en la Base de Datos
     */
    public function guardarModificacion($id_interfaz)
    {/*
        $interfaz = Application_Model_DbTable_Interfaz::findOneById($id_interfaz);

        $interfaz->estado = 1;
     
        return $interfaz->save();*/
    }


}