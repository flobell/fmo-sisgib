<?php


class Application_Form_EntidadesBancarias extends Fmo_Form_Abstract
{

    //CONSTANTES PARA DATOS DE ENTIDADES BANCARIAS 
    const E_COD = 'cod';
    const E_BANCO = 'banco';
    const E_BCV = 'bcv';
    const E_SWIFT = 'swift';
    const E_ACTIVO = 'activo';
    const E_MONTO = 'monto';

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

        $tEntidades = new Application_Model_DbTable_Bancos();
 


        
     
////////////// DATOS ENTIDADES BANCARIAS ///////////////////////////////
        
        
        $eCodigo= new Zend_Form_Element_Text(self::E_COD); 
        $eCodigo->setLabel('Codigo:')
                ->setAttrib('size', '25')
                ->setAttrib('maxlength', '5')
                ->setAttrib('placeholder', "Ingrese Código del Banco")
                ->addValidator('float',true)
                ->addValidator('StringLength', false, array('min' => '0', 'max' => '3', 'encoding' => $this->getView()->getEncoding()))
                ->setRequired(true);
        $this->addElement($eCodigo); 

        $eBanco = new Zend_Form_Element_Text(self::E_BANCO);
        $eBanco->setLabel('Banco:')
                ->setAttrib('size', '25')
                ->setAttrib('maxlength', '80')
                ->setAttrib('placeholder', "Ingrese Nombre del Banco")
                ->setRequired(true);
        $this->addElement($eBanco); 
        
        $eBcv = new Zend_Form_Element_Text(self::E_BCV);
        $eBcv->setLabel('Codigo BCV:')
                ->setAttrib('size', '25')
                ->setAttrib('maxlength', '5')
                ->addValidator('float',true)
                ->addValidator('StringLength', false, array('min' => '0', 'max' => '4', 'encoding' => $this->getView()->getEncoding()))
                ->setAttrib('placeholder', "Ingrese Código BCV del Banco")
                ->setRequired(true);
        $this->addElement($eBcv); 
        
        $eSwift = new Zend_Form_Element_Text(self::E_SWIFT);
        $eSwift->setLabel('Codigo Swift:')
                ->setAttrib('size', '25')
                ->setAttrib('maxlength', '15')
                ->setAttrib('placeholder', "Ingrese Código Swift del Banco")
                ->setRequired(true);
        $this->addElement($eSwift); 
        
        $eMonto = new Zend_Form_Element_Text(self::E_MONTO);
        $eMonto->setLabel('Monto Maximo')
                ->setAttrib('style', 'text-align: right;')
                ->setAttrib('size', '20')
                ->setAttrib('maxlength', '30')
                ->setDescription('Ejemplo: 3000000.00')
                ->setRequired();
        $this->addElement($eMonto);
        
        $eActivo = new Zend_Form_Element_Checkbox(self::E_ACTIVO);
        $eActivo->setLabel('Habilitar:');
        $this->addElement($eActivo); 
        
        
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
    public function valoresPorDefecto($codigo)
    {
        $entidades = Application_Model_DbTable_Bancos::findOneById($codigo);

        $this->setDefault(self::E_COD, $entidades->codigo)
             ->setDefault(self::E_BANCO, $entidades->nombre_banco)
             ->setDefault(self::E_BCV, $entidades->bcv)
             ->setDefault(self::E_SWIFT, $entidades->swift)
             ->setDefault(self::E_ACTIVO, $entidades->activo)
             ->setDefault(self::E_MONTO, $entidades->monto_maximo);

             
    }

    /**
     * Método para guardar los datos en la Base de Datos
     */
    public function guardarNuevo($params)
    {
        $tEntidades = new Application_Model_DbTable_Bancos();
        try {
             //Transacciones
            Zend_Db_Table::getDefaultAdapter()->beginTransaction();
            //Guarda en tabla bancos
            $registro = $tEntidades->createRow();
            $registro->codigo = $params[self::E_COD];
            $registro->nombre_banco = strtoupper($params[self::E_BANCO]);
            $registro->bcv = $params[self::E_BCV];
            $registro->swift = strtoupper($params[self::E_SWIFT]);
            $registro->monto_maximo = $params[self::E_MONTO];
       
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
    public function guardarModificacion($codigo, $params)
    {
        $entidades = Application_Model_DbTable_Bancos::findOneById($codigo);
        try{
            //Transacciones
            Zend_Db_Table::getDefaultAdapter()->beginTransaction();
             //Guarda modificacion en tabla bancos  
            $entidades->codigo = $params[self::E_COD];
            $entidades->nombre_banco= strtoupper($params[self::E_BANCO]);
            $entidades->bcv = $params[self::E_BCV];
            $entidades->swift = strtoupper($params[self::E_SWIFT]);
            $entidades->activo = $params[self::E_ACTIVO];
            $entidades->monto_maximo = $params[self::E_MONTO];
            
            Zend_Db_Table::getDefaultAdapter()->commit();
            return $entidades->save();
        }catch(Exception $e){
            Zend_Db_Table::getDefaultAdapter()->rollBack();
            throw $e;
        }        
        

    }


}

