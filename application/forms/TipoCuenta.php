<?php


class Application_Form_TipoCuenta extends Fmo_Form_Abstract
{

    //CONSTANTES PARA DATOS DE ENTIDADES BANCARIAS 
    const E_ID_TIPO = 'id_tipo';
    const E_TIPO = 'tipo';
    const E_NOMBRE = 'nombre';

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
    
    public function init()
    {
        $this->setAction($this->getView()->url());

        $tTipoCuenta = new Application_Model_DbTable_TipoCuenta();
 


        
     
////////////// DATOS TIPO DE CUENTA ///////////////////////////////

         
        $eID= new Zend_Form_Element_Text(self::E_TIPO); 
        $eID->setLabel('ID:')
                ->setAttrib('size', '35')
                ->setAttrib('maxlength', '2')
                ->setAttrib('placeholder', "No. Tipo de Cuenta")
                ->addValidator('float',true)
                ->addValidator('StringLength', false, array('min' => '0', 'max' => '2', 'encoding' => $this->getView()->getEncoding()))
                ->setRequired(true);
        $this->addElement($eID); 

        $eNombre = new Zend_Form_Element_Text(self::E_NOMBRE);
        $eNombre->setLabel('Nuevo Tipo de Cuenta:')
                ->setAttrib('size', '35')
                ->setAttrib('maxlength', '80')
                ->setAttrib('placeholder', "Nombre del Tipo de Cuenta")
                ->setRequired(true);
        $this->addElement($eNombre); 
        
  
        
        
    ////////////// BOTONES DE GUARDADO Y CANCELADO ///////////////////////////////
        $eCancelar = new Zend_Form_Element_Submit(self::E_CANCELAR);
        $eCancelar->setLabel('Volver')
                  ->setIgnore(true);
        $this->addElement($eCancelar);
        
        $eGuardar = new Zend_Form_Element_Submit(self::E_GUARDAR);
        $eGuardar->setLabel('Guardar')
                 ->setIgnore(true);
        $this->addElement($eGuardar);

       

        $this->setCustomDecorators();
    }

    /**
     * Método para inicializar los valores por defecto en el formulario
     *

     * @throws InvalidArgumentException
     */
    public function valoresPorDefecto($id_tipo)
    {
        $tipocuenta = Application_Model_DbTable_TipoCuenta::findOneById($id_tipo);

        $this->setDefault(self::E_ID_TIPO, $tipocuenta->id_tipo);
        $this->setDefault(self::E_TIPO, $tipocuenta->tipo);
        $this->setDefault(self::E_NOMBRE, $tipocuenta->nombre);
 
    }

    /**
     * Método para guardar los datos en la Base de Datos
     */
    public function guardarNuevo($params)
    {
        $tTipoCuenta = new Application_Model_DbTable_TipoCuenta();
        try {
            //Transacciones
            Zend_Db_Table::getDefaultAdapter()->beginTransaction();
            
            //Guarda en tabla bancos
            $registro = $tTipoCuenta->createRow();
            $registro->tipo = $params[self::E_TIPO];
            $registro->nombre = strtoupper($params[self::E_NOMBRE]);
            
            $registro->save();
            Zend_Db_Table::getDefaultAdapter()->commit();
        } catch (Exception $e) {
            Zend_Db_Table::getDefaultAdapter()->rollBack();
            throw $e;
        }
    }

    /**
     * Método para guardar los datos en la Base de Datos
     */
    public function guardarModificacion($id_tipo, $params)
    {
        $tipocuenta = Application_Model_DbTable_TipoCuenta::findOneById($id_tipo);
        try {
            //Transacciones
            Zend_Db_Table::getDefaultAdapter()->beginTransaction();
            //Guarda modificacion en tabla 
            $tipocuenta->tipo = $params[self::E_TIPO];
            $tipocuenta->nombre = strtoupper($params[self::E_NOMBRE]);
            Zend_Db_Table::getDefaultAdapter()->commit();
            return $tipocuenta->save();
            
        } catch (Exception $ex) {
            Zend_Db_Table::getDefaultAdapter()->rollBack();
            throw $ex;
        }       
    }
}

