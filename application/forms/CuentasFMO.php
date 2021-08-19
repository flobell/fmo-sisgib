    <?php


class Application_Form_CuentasFMO extends Fmo_Form_Abstract
{
    //CONSTANTES PARA DATOS INTERFAZ - PROVEEDORES
    const E_RIF= 'rif';
    const E_LETRA = 'letra';
    const E_RAZON = 'razon';
    const E_CONVE = 'conve';
    const E_CODEMPRESA = 'codempresa';

    //CONSTANTES PARA DATOS BANCARIOS PROVEEDORES
    const E_COD = 'cod';
    const E_BANCO = 'banco';
    const E_TIPOCUENTA = 'tipocuenta';
    
    const E_CODTXT = 'codtxt';
    const E_BANCOTXT = 'bancotxt';
    const E_TIPOCUENTATXT = 'tipocuentatxt';
    const E_BCV = 'bcv';
    const E_NUMCUENTA = 'numcuenta';
    const E_ACTIVA = 'activa';
    const E_FECHA = 'fecha';

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

       $tCuentas = new Application_Model_DbTable_CuentasFMO();
 
////////////// DATOS DE INTERFAZ DE PROVEEDOR ///////////////////////////////
        
                       
        $eRif = new Zend_Form_Element_Text(self::E_RIF);
        $eRif->setLabel(' ')
                //->setValue("J-00100542-0")
                //->setAttrib('readonly',"")
                ->setAttrib('size', '10')
                ->setAttrib('maxlength', '10')
                ->setRequired()
                ->setDescription('El formato del RIF. debe ser: J001005420');
                
        $this->addElement($eRif);

        $eRazon = new Zend_Form_Element_Text(self::E_RAZON);
        $eRazon->setLabel('Razón Social:')
                //->setValue("FERROMINERA ORINOCO")
                //->setAttrib('readonly',"")
                ->setRequired()
                ->setAttrib('size', '20 ')
                ->setAttrib('maxlength', '60');
               

        $this->addElement($eRazon);

        $eConve = new Zend_Form_Element_Text(self::E_CONVE);
        $eConve->setLabel('Número de Convenio:')
               //->setAttrib('size', '10')
               ->setAttrib('maxlength', '10')
                ->addValidator('float',true)
               ->addValidator('StringLength', false, array('min' => '0', 'max' => '10', 'encoding' => $this->getView()->getEncoding()))
               //->setRequired();
               ->setDescription('Requerido solo BANCO DELSUR');
        $this->addElement($eConve);
        
        $eCodempresa = new Zend_Form_Element_Text(self::E_CODEMPRESA);
        $eCodempresa->setLabel('Código Empresa:')
               //->setAttrib('size', '10')
               ->setAttrib('maxlength', '10')
               ->addValidator('float',true)
               ->setDescription('Requerido solo BANCO DELSUR')
               ->addValidator('StringLength', false, array('min' => '0', 'max' => '10', 'encoding' => $this->getView()->getEncoding()));
               //->setRequired();
        $this->addElement($eCodempresa);
        
////////////// DATOS BANCARIOS FMO ///////////////////////////////

        $eCodigo = new Zend_Form_Element_Select(self::E_COD);
        $eCodigo->setLabel('Código.')
               ->addMultiOption("",'Entidad Bancaria')
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
       
    ////////////// DATOS BANCARIOS FMO EN TABLA (TEXTO) /////////////////////////////// 
        
        $eCodigotxt = new Zend_Form_Element_text(self::E_CODTXT);
        $eCodigotxt->setLabel('Cod.')
               ->setAttrib('size', '4')
               ->setAttrib('maxlength', '3')
                ->setRequired()
               ->setAttrib("readonly",""); 
              // ->setRequired();
        $this->addElement($eCodigotxt);
        
        $eBanco = new Zend_Form_Element_Text(self::E_BANCO);
        $eBanco->setLabel('Banco')
               ->setAttrib('size', '22')
               ->setAttrib('maxlength', '40')
                ->setRequired()
               ->setAttrib("readonly",""); 
              // ->setRequired();
        $this->addElement($eBanco);

        $eBcv = new Zend_Form_Element_Text(self::E_BCV);
        $eBcv->setLabel('BCV')
               ->setAttrib('size', '5')
               ->setAttrib('maxlength', '4')
               ->setAttrib("readonly",""); 
              // ->setRequired();
        $this->addElement($eBcv);
        
        $eNumCuenta = new Zend_Form_Element_Text(self::E_NUMCUENTA);
        $eNumCuenta->setLabel('Numero de Cuenta')
               ->setAttrib('size', '18')
               ->setAttrib('maxlength', '20')
                ->addValidator('float',true)
               ->addValidator('StringLength', false, array('min' => '20', 'max' => '20', 'encoding' => $this->getView()->getEncoding()))
               ->setRequired();
        $this->addElement($eNumCuenta); 
        
        $eActiva = new Zend_Form_Element_Checkbox(self::E_ACTIVA);
        $eActiva->setLabel('Numero de Cuenta')
                ->setCheckedValue("1")
                ->setUncheckedValue("0");
        $this->addElement($eActiva); 
        
        $eFecha = new Zend_Form_Element_Hidden(self::E_FECHA);
        $eFecha->setLabel('Fecha')
                ->setAttrib('size', '5')    
                ->setRequired(true)
                ->setValue(date('d/m/y'))
                ->setAttrib("readonly", "");
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
    public function valoresPorDefecto($numcuenta)
    {
       $cuentasFMO = Application_Model_DbTable_CuentasFMO::findOneById($numcuenta);
       
        $this->setDefault(self::E_RIF, $cuentasFMO->rif)
             ->setDefault(self::E_RAZON, $cuentasFMO->razon)
             ->setDefault(self::E_NUMCUENTA, $cuentasFMO->numcuenta)
             ->setDefault(self::E_COD, $cuentasFMO->codigo_banco)
             ->setDefault(self::E_TIPOCUENTA, $cuentasFMO->tipocuenta)
             ->setDefault(self::E_BANCO, $cuentasFMO->banco)
             ->setDefault(self::E_CODTXT, $cuentasFMO->codigo_banco)
             ->setDefault(self::E_ACTIVA, $cuentasFMO->estado)     
            ->setDefault(self::E_CONVE, $cuentasFMO->nro_convenio)
            ->setDefault(self::E_CODEMPRESA, $cuentasFMO->nro_empresa); 
    }

    /**
     * Método para guardar los datos en la Base de Datos
     */
    public function guardarNuevo($params)
    {
        $tCuentas = new Application_Model_DbTable_CuentasFMO();
        try {
            //Transacciones
            Zend_Db_Table::getDefaultAdapter()->beginTransaction();
            
            //Guarda en tabla proveedores
            $registro = $tCuentas->createRow();
            $registro->codigo_banco = $params[self::E_COD];
            $registro->banco = $params[self::E_BANCO];
            $registro->numcuenta = $params[self::E_NUMCUENTA];
            $registro->tipocuenta = $params[self::E_TIPOCUENTA];
            $registro->estado = $params[self::E_ACTIVA];
            $registro->fecha = $params[self::E_FECHA];
            $registro->rif = $params[self::E_RIF];
            $registro->razon = strtoupper($params[self::E_RAZON]);
            if ($params[self::E_CONVE] != NULL) $registro->nro_convenio = $params[self::E_CONVE];
            if ($params[self::E_CODEMPRESA] != NULL) $registro->nro_empresa = $params[self::E_CODEMPRESA];
       
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
        $cuentas = Application_Model_DbTable_CuentasFMO::findOneById($numcuenta);
        try{
            //Transacciones
            Zend_Db_Table::getDefaultAdapter()->beginTransaction();        
            //Guarda modificacion en tabla cuentas fmo
            $cuentas->codigo_banco = $params[self::E_COD];
            $cuentas->banco = $params[self::E_BANCO];
            $cuentas->tipocuenta = $params[self::E_TIPOCUENTA];
            $cuentas->numcuenta = $params[self::E_NUMCUENTA];
            $cuentas->estado = $params[self::E_ACTIVA];
            $cuentas->fecha = $params[self::E_FECHA];
            $cuentas->rif = $params[self::E_RIF];
            $cuentas->razon = strtoupper($params[self::E_RAZON]);
            if ($params[self::E_CONVE] != NULL) $cuentas->nro_convenio = $params[self::E_CONVE];
            if ($params[self::E_CODEMPRESA] != NULL) $cuentas->nro_empresa = $params[self::E_CODEMPRESA];


            Zend_Db_Table::getDefaultAdapter()->commit();
            return $cuentas->save();
        }  catch (Exception $e){
            Zend_Db_Table::getDefaultAdapter()->rollBack();
            throw $e;
        }
    }
}