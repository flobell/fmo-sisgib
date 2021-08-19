<?php
use Zend\Form\Element;
use Zend\Form\Form;

class Application_Form_NuevaCuentaBancaria extends Fmo_Form_Abstract
{
    const E_RIF = 'rif';
    const E_LETRA = 'letra';
    
    //CONSTANTES PARA DATOS BANCARIOS 
    const E_COD = 'cod';
    const E_BANCO = 'banco';
    const E_TIPOCUENTA = 'tipocuenta';
    const E_CODTXT = 'codtxt';
    const E_BCV = 'bcv';
    const E_NUMCUENTA = 'numcuenta';
    const E_ACTIVA = 'activa';

    
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
        $this->setAction($this->getView()->url())
             ->setLegend('Nueva Cuenta Bancaria');
             
        //$tProveedores = new Application_Model_DbTable_Proveedores();
 

        
        $eTipo = array('J'=>'J', 'G'=>'G', 'V'=>'V', 'E'=>'E');
        $eLetra= new Zend_Form_Element_Select(self::E_LETRA);
            $eLetra->setLabel('R.I.F/C.I :')
                ->addMultiOptions($eTipo)
                ->setRequired();
        $this->addElement($eLetra);
                       
        $eRif = new Zend_Form_Element_Text(self::E_RIF);
        $eRif->setLabel(' ')
                //->setRequired()              
                ->setAttrib("readonly","")
                ->setAttrib('size', '20')
                ->setAttrib('maxlength', '10')
                ->addValidator('float',true)
                ->addValidator('StringLength', false, array('min' => '8', 'max' => '10', 'encoding' => $this->getView()->getEncoding()));
        $this->addElement($eRif);
     
////////////// DATOS BANCARIOS PROVEEDOR ///////////////////////////////
        
        
        $eCodigo = new Zend_Form_Element_Select(self::E_COD);
        $eCodigo->setLabel('Banco.')
               ->addMultiOption("",'Seleccionar Banco.')
               ->addMultiOptions(Application_Model_DbTable_Bancos::getPairsWithOrder('codigo', new Zend_Db_Expr("bcv ||' - ' || nombre_banco"), 'activo=true',  'codigo'))
               ->setRequired()
               ->setAttrib("oninput", "getDatosBanco()")
                        ->setAttrib("url", $this->getView()->url(array("controller" => "ajax", "action" => "getbanco")));     
        $this->addElement($eCodigo);


        $eTipoCuenta = new Zend_Form_Element_Select(self::E_TIPOCUENTA);
        $eTipoCuenta->setLabel('Tipo de Cuenta')
               ->addMultiOption("",'Seleccione el tipo de cuenta.')
               ->addMultiOptions(Application_Model_DbTable_TipoCuenta::getPairsWithOrder('tipo', new Zend_Db_Expr("nombre"), NULL,  'tipo'))
               ->setRequired();
        $this->addElement($eTipoCuenta);
       
    ////////////// DATOS BANCARIOS PROVEEDOR EN TABLA (TEXTO) /////////////////////////////// 
        
        $eCodigotxt = new Zend_Form_Element_text(self::E_CODTXT);
        $eCodigotxt->setLabel('Cod.')
               ->setAttrib('size', '5')
               ->setAttrib('maxlength', '3')
                ->setRequired()
               ->setAttrib("readonly","");
        $this->addElement($eCodigotxt);
 
        $eBanco = new Zend_Form_Element_Text(self::E_BANCO);
        $eBanco->setLabel('Banco')
               ->setAttrib('size', '22')
               ->setAttrib('maxlength', '40')
                ->setRequired()
               ->setAttrib("readonly","");
        $this->addElement($eBanco);

        $eBcv = new Zend_Form_Element_Text(self::E_BCV);
        $eBcv->setLabel('BCV')
               ->setAttrib('size', '5')
               ->setAttrib('maxlength', '4')
               ->setAttrib("readonly","");
        $this->addElement($eBcv);
        
        $eNumCuenta = new Zend_Form_Element_Text(self::E_NUMCUENTA);
        $eNumCuenta->setLabel('Numero de Cuenta')
               ->setAttrib('size', '18')
               ->setAttrib('maxlength', '20')
               ->addValidator('float',true)
               ->addValidator('StringLength', false, array('min' => '20', 'max' => '20', 'encoding' => $this->getView()->getEncoding()))
              //  ->addValidator('alpha',true)
               ->setRequired();
        $this->addElement($eNumCuenta); 
        
        $eActiva = new Zend_Form_Element_Checkbox(self::E_ACTIVA);
        $eActiva->setLabel('Cuenta Activa')
                ->setCheckedValue("1")
                ->setUncheckedValue("0");
        $this->addElement($eActiva); 
        

    ////////////// BOTONES DE GUARDADO Y CANCELADO ///////////////////////////////
        
        $eGuardar = new Zend_Form_Element_Submit(self::E_GUARDAR);
        $eGuardar->setLabel('Guardar')
                 ->setIgnore(true);
        $this->addElement($eGuardar);

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
    public function valoresPorDefecto($numcuenta)
    {
        
        $cuentasproveedores = Application_Model_DbTable_CuentasProveedores::findOneById($numcuenta);
       
        $this->setDefault(self::E_NUMCUENTA, $cuentasproveedores->numcuenta)
             ->setDefault(self::E_COD, $cuentasproveedores->codigo_banco)
             ->setDefault(self::E_TIPOCUENTA, $cuentasproveedores->tipocuenta)
             ->setDefault(self::E_BANCO, $cuentasproveedores->banco)
             ->setDefault(self::E_RIF, $cuentasproveedores->fk_rif)
             ->setDefault(self::E_CODTXT, $cuentasproveedores->codigo_banco)
             ->setDefault(self::E_LETRA, $cuentasproveedores->nacionalidad);
    }

    /**
     * Método para guardar los datos en la Base de Datos
     */
    public function guardarNuevo($params)
    {
        $tCuentasProveedores = new Application_Model_DbTable_CuentasProveedores();
        try {
            //Transacciones
            Zend_Db_Table::getDefaultAdapter()->beginTransaction();
            
            //Guarda en tabla proveedores
            $registro = $tCuentasProveedores->createRow();
            $registro->codigo_banco = $params[self::E_COD];
            $registro->banco = $params[self::E_BANCO];
            $registro->numcuenta = $params[self::E_NUMCUENTA];
            $registro->tipocuenta = $params[self::E_TIPOCUENTA];
            $registro->fk_rif = $params[self::E_RIF];
            $registro->estado = $params[self::E_ACTIVA];
            $registro->nacionalidad = $params[self::E_LETRA];
            
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
    public function guardarModificacion($numcuenta, $params)
    {
        $cuentasproveedores = Application_Model_DbTable_CuentasProveedores::findOneById($numcuenta);
        try {
            //Transacciones
            Zend_Db_Table::getDefaultAdapter()->beginTransaction();
            
            //Guarda modificacion en tabla cuentas proveedores 
            $cuentasproveedores->fk_rif = $params[self::E_RIF];
            $cuentasproveedores->codigo_banco = $this->getValue(self::E_COD);
            $cuentasproveedores->banco = $params[self::E_BANCO];
            $cuentasproveedores->tipocuenta = $this->getValue(self::E_TIPOCUENTA);
            $cuentasproveedores->numcuenta = $params[self::E_NUMCUENTA];
            $cuentasproveedores->estado = $params[self::E_ACTIVA];
            $cuentasproveedores->nacionalidad = $params [self::E_LETRA];
        
            Zend_Db_Table::getDefaultAdapter()->commit();
            return $cuentasproveedores->save();
            
        } catch (Exception $ex) {
            Zend_Db_Table::getDefaultAdapter()->rollBack();
            throw $ex;
        }
    }    
}

