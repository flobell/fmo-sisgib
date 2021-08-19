<?php
use Zend\Form\Element;
use Zend\Form\Form;

class Application_Form_Proveedores extends Fmo_Form_Abstract
{
    //CONSTANTES PARA DATOS INTERFAZ - PROVEEDORES
    const E_RIF = 'RIF';
    const E_NACIONALIDAD = 'nacionalidad';
    const E_LETRA = 'letra';
    const E_RAZON = 'razon';
    const E_REPRESENTANTE = 'representante';
    const E_TELEFONO ='telefono';
    const E_DIRECCION = 'direccion';
    const E_CORREO = 'correo';
    const E_FECHA = 'fecha';
    
    //CONSTANTES PARA DATOS BANCARIOS PROVEEDORES
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
             ->setLegend('Registro de Proveedores');
             
        $tProveedores = new Application_Model_DbTable_Proveedores();
 
////////////// DATOS DE INTERFAZ DE PROVEEDOR ///////////////////////////////
        
        $eTipo = array('J'=>'J', 'G'=>'G', 'V'=>'V', 'E'=>'E');
        $eLetra= new Zend_Form_Element_Select(self::E_LETRA);
            $eLetra->setLabel('R.I.F/C.I :')
                ->addMultiOptions($eTipo)
                ->setRequired();
        $this->addElement($eLetra);
                       
        $eRif = new Zend_Form_Element_Text(self::E_RIF);
        $eRif->setLabel(' ')
                ->setRequired()
                ->setAttrib('size', '20')
                ->setAttrib('maxlength', '10')
                ->addValidator('float',true)
                ->addValidator('StringLength', false, array('min' => '8', 'max' => '10', 'encoding' => $this->getView()->getEncoding()));
        $this->addElement($eRif);

        $eRazon = new Zend_Form_Element_Text(self::E_RAZON);
        $eRazon->setLabel('Razón Social:')
                ->setAttrib('size', '40 ')
                ->setAttrib('maxlength', '60')
                ->setRequired();
        $this->addElement($eRazon);

        $eRepresentante = new Zend_Form_Element_Text(self::E_REPRESENTANTE);
        $eRepresentante->setLabel('Representante Legal:')
                ->setAttrib('size', '40')
                ->setAttrib('maxlength', '80')
                ->setRequired();
        $this->addElement($eRepresentante);
        
        $eTelefono = new Zend_Form_Element_Text(self::E_TELEFONO);
        $eTelefono->setLabel('Telefono:')
                ->setAttrib('size', '25')
                ->setAttrib('maxlength', '11')
                ->addValidator('float',true)
                ->addValidator('StringLength', false, array('min' => '11', 'max' => '11', 'encoding' => $this->getView()->getEncoding()))
                ->setRequired();
        $this->addElement($eTelefono);
        
        $eDireccion = new Zend_Form_Element_Text(self::E_DIRECCION);
        $eDireccion->setLabel('Dirección:')
                ->setAttrib('size', '40')
                ->setAttrib('maxlength', '80')
                ->setRequired();
        $this->addElement($eDireccion);
     
        $eCorreo = new Zend_Form_Element_Text(self::E_CORREO);
        $eCorreo->setLabel('Correo:')
                ->setAttrib('size', '25')
                ->setAttrib('maxlength', '80')
                ->setRequired();
        $this->addElement($eCorreo);
     
////////////// DATOS BANCARIOS PROVEEDOR ///////////////////////////////
        
        
        $eCodigo = new Zend_Form_Element_Select(self::E_COD);
        $eCodigo->setLabel('Código.')
               ->addMultiOption("",'Seleccione Entidad Bancaria')
               ->addMultiOptions(Application_Model_DbTable_Bancos::getPairsWithOrder('codigo', new Zend_Db_Expr("codigo ||' - ' || nombre_banco"), 'activo=true',  'codigo'))
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
               ->setAttrib('size', '4')
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
        
        
    ////////////// DATOS DEL SISTEMA  ///////////////////////////////

        $eFecha= new Zend_Form_Element_Hidden(self::E_FECHA); //acomodar
        $eFecha->setLabel('Fecha')
            ->setAttrib('size', '5')    
            ->setRequired(true)
            ->setValue(date('d/m/y'))
            ->setAttrib("readonly",""); 
        $this->addElement($eFecha);

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
    public function valoresPorDefecto($numcuenta, $rif)
    {
        
        $cuentasproveedores = Application_Model_DbTable_CuentasProveedores::findOneById($numcuenta);
        $proveedores = Application_Model_DbTable_Proveedores::findOneById($rif);
        
        $this->setDefault(self::E_NUMCUENTA, $cuentasproveedores->numcuenta)
             ->setDefault(self::E_COD, $cuentasproveedores->codigo_banco)
             ->setDefault(self::E_TIPOCUENTA, $cuentasproveedores->tipocuenta)
                
             ->setDefault(self::E_RIF, $proveedores->rif)
             ->setDefault(self::E_RAZON, $proveedores->razon)
             ->setDefault(self::E_REPRESENTANTE, $proveedores->representante)
             ->setDefault(self::E_LETRA, $proveedores->nacionalidad);    

             
    }

    /**
     * Método para guardar los datos en la Base de Datos
     */
    public function guardarNuevo($params)
    {
        $tProveedores = new Application_Model_DbTable_Proveedores();
        $tCuentasProveedores = new Application_Model_DbTable_CuentasProveedores();
        try {
            //Transacciones
            Zend_Db_Table::getDefaultAdapter()->beginTransaction();
            
            //Guarda en tabla proveedores
            $registroP = $tProveedores->createRow();
            $registroCP = $tCuentasProveedores->createRow();
            
            $registroP->rif = $params[self::E_RIF];
            $registroP->razon = strtoupper($params[self::E_RAZON]);
            $registroP->representante = strtoupper($params[self::E_REPRESENTANTE]);
            $registroP->nacionalidad = $params[self::E_LETRA];
            $registroP->fecha = $params[self::E_FECHA];
            $registroP->direccion = strtoupper($params[self::E_DIRECCION]);
            $registroP->telefono = $params[self::E_TELEFONO];
            $registroP->correo = $params[self::E_CORREO];
            
            $registroCP->codigo_banco = $params[self::E_COD];
            $registroCP->banco = $params[self::E_BANCO];
            $registroCP->numcuenta = $params[self::E_NUMCUENTA];
            $registroCP->tipocuenta = $params[self::E_TIPOCUENTA];
            $registroCP->fk_rif = $params[self::E_RIF];
            $registroCP->estado = $params[self::E_ACTIVA];
            $registroCP->nacionalidad = $params[self::E_LETRA];
       
            $registroP->save();
            $registroCP->save();
            
            Zend_Db_Table::getDefaultAdapter()->commit();
        } catch (Exception $e) {
             Zend_Db_Table::getDefaultAdapter()->rollBack();
            throw $e;
        }
    }

    /**
     * Método para guardar los datos en la Base de Datos
     */
    public function guardarModificacion($rif, $numcuenta, $params)
    {/*
        $proveedores = Application_Model_DbTable_Proveedores::findOneById($rif);
        $cuentasproveedores = Application_Model_DbTable_CuentasProveedores::findOneById($numcuenta);
        
        //Guarda modificacion en tabla proveedores  
        $proveedores->rif = $params[self::E_RIF];
        $proveedores->razon= strtoupper($params[self::E_RAZON]);
        $proveedores->representante= strtoupper($params[self::E_REPRESENTANTE]);
        $proveedores->nacionalidad = $params[self::E_LETRA];
        $proveedores->fecha = $params[self::E_FECHA];
        
        $cuentasproveedores->codigo_banco = $this->getValue(self::E_COD);
        //$proveedores->banco = $params[self::E_BANCO];
        $cuentasproveedores->tipocuenta = $this->getValue(self::E_TIPOCUENTA);
        $cuentasproveedores->numcuenta = $params[self::E_NUMCUENTA];
        $cuentasproveedores->estado = $params[self::E_ACTIVA];
        
        

        
        return $proveedores->save();
        $cuentasproveedores->save();
*/
    }

    
}

