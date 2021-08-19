<?php


class Application_Form_CargarProveedores extends Fmo_Form_Abstract
{
    //CONSTANTES PARA DATOS INTERFAZ - PROVEEDORES
    const E_RIF = 'rif';
    const E_RAZON = 'razon';
    const E_REPRESENTANTE = 'representante';
    const E_NACIONALIDAD = 'nacionalidad';
    const E_NUMERO_CUENTA = 'numcuenta';    
    const E_TIPO_CUENTA = 'tipocuenta';
    const E_MONTO= 'monto';
    const E_BANCOTXT = 'bancotxt';
    
    
    const E_TITULO = 'titulo';
    const E_COD = 'cod';
    const E_ESTADO = 'estado';
    const E_FECHA = 'fecha';


    

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
 
///////////////////// DATOS DE INTERFAZ PROVEEDORES ///////////////////////////////  
        

        
        $eTitulo = new Zend_Form_Element_Text(self::E_TITULO);
        $eTitulo->setLabel('titulo de la Interfaz')
                ->setAttrib('size', '30')
                ->setAttrib('maxlength', '100')
                ->setRequired();
        $this->addElement($eTitulo);
           
        $eFecha= new Zend_Form_Element_Hidden(self::E_FECHA); 
        $eFecha->setLabel('Fecha')
            ->setAttrib('size', '5')    
            ->setRequired(true)
            ->setValue(date('d/m/y'))
            ->setAttrib("readonly",""); 
        $this->addElement($eFecha);
        
        
 
        
///////////// DATOS DE INTERDAZ PROVEEDORES ///////////////////////////////////
        
        $eRif = new Zend_Form_Element_Text(self::E_RIF);
        $eRif->setLabel('C.I/R.I.F')
                ->setAttrib('size', '6')
                ->setAttrib('maxlength', '10')
                ->setAttrib ("float","true")    
                ->setIsArray(true)
                ->setAttrib("oninput", "getDatosRif(this); validarFila(this);")
                        ->setAttrib("url", $this->getView()->url(array("controller" => "ajax", "action" => "getrif")))
                ->addValidator('StringLength', false, array('min' => 8, 'max' => 10))
                ->setRequired();
        $this->addElement($eRif);

        $eRazon = new Zend_Form_Element_Text(self::E_RAZON);
        $eRazon->setLabel('Razón Social')
                ->setAttrib('size', '10 ')
                ->setAttrib('maxlength', '60')
                ->setIsArray(true)
                ->setAttrib("readonly","");  
        $this->addElement($eRazon);

        $eRepresentante = new Zend_Form_Element_Text(self::E_REPRESENTANTE);
        $eRepresentante->setLabel('Representante Legal')
                ->setAttrib('size', '10')
                ->setAttrib('maxlength', '80')
                ->setIsArray(true)
                ->setAttrib("readonly","");  
        $this->addElement($eRepresentante);
        
        $eNacionalidad = new Zend_Form_Element_Text(self::E_NACIONALIDAD);
        $eNacionalidad->setLabel('Nac.')
                ->setAttrib('size', '2  ')
                ->setAttrib('maxlength', '2')
                ->setIsArray(true)
                ->setAttrib("readonly",""); 
        $this->addElement($eNacionalidad);
        
        $eNumeroCuenta = new Zend_Form_Element_Text(self::E_NUMERO_CUENTA);
        $eNumeroCuenta->setLabel('Numero de Cuenta')
                ->setAttrib('size', '20 ')
                ->setAttrib('maxlength', '20')
                ->setIsArray(true)
                ->setAttrib("readonly","");
        $this->addElement($eNumeroCuenta);
        
        $eTipoCuenta = new Zend_Form_Element_Text(self::E_TIPO_CUENTA);
        $eTipoCuenta->setLabel('Tipo de Cuenta')
                ->setAttrib('size', '10')
                ->setAttrib('maxlength', '10')
                ->setIsArray(true)
                ->setAttrib("readonly","");    
        $this->addElement($eTipoCuenta);
        
        $eBancoTxt = new Zend_Form_Element_Text(self::E_BANCOTXT);
        $eBancoTxt->setLabel('Banco')
                ->setAttrib('size', '2')
                ->setAttrib('maxlength', '40')
                ->setIsArray(true)
                ->setAttrib("readonly","");
        $this->addElement($eBancoTxt);
        
        $eMonto = new Zend_Form_Element_Text(self::E_MONTO);
        $eMonto->setLabel('Monto a Pagar')
                ->setAttrib('size', '10 ')
                ->setAttrib('style', 'text-align: right;')
                ->setIsArray(true)
                ->setAttrib('maxlength', '20')
                ->setRequired();
        $this->addElement($eMonto);
        
////////////// DATOS BANCARIOS ///////////////////////////////
        
        $eCodigo = new Zend_Form_Element_Select(self::E_COD);
        $eCodigo->setLabel('Banco.')
               ->addMultiOption("",'Seleccionar banco.')
               ->addMultiOptions(Application_Model_DbTable_Bancos::getPairsWithOrder('codigo', new Zend_Db_Expr("bcv ||' - ' || nombre_banco"), 'activo=true',  'codigo'))
               ->setRequired();
        $this->addElement($eCodigo);

    ////////////// BOTONES DE GUARDADO Y CANCELADO ///////////////////////////////
        
        $eCargar = new Zend_Form_Element_Submit(self::E_CARGAR);
        $eCargar->setLabel('Guardar')
                ->setIgnore(true);
        $this->addElement($eCargar);

        $eCancelar = new Zend_Form_Element_Submit(self::E_CANCELAR);
        $eCancelar->setLabel('Volver')
                  ->setIgnore(true);
        $this->addElement($eCancelar);
        
        
        $this->setCustomDecorators();
    }

    
    function limpiar($string)
    {

        $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $string
            );

        $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $string
            );

        $string = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $string
            );

        $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $string
            );

        $string = str_replace(
                array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
                array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
                $string
                );

        $string = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C',),
            $string
            );

        


        return $string;
    }
    
    // Validaciones
    private function _validarDatos($params)
    {  /* 
        for($i = 0; $i < count($params[self::E_RIF]); $i++){
            $j= 0;
            $j=$i+1; 
            //Zend_Debug::dd($i);
            if ($params[self::E_COD] != $params[self::E_BANCOTXT][$i]) {
                throw new Exception('El banco del proveedor de la FILA ' . $j . ' no coincide con la entidad bancaria seleccionada.');
            }
        }
        */

        if (empty($params[self::E_COD])) {
            throw new Exception('Debe indicar un banco para cargar la interfaz');
        }
        if (empty($params[self::E_TITULO])) {
            throw new Exception('Debe indicar un titulo para la interfaz');
        }
        
        if (count($params[self::E_RIF]) > 0) {
            foreach ($params[self::E_RIF] as $rif) {
                if (empty($rif)) {
                    throw new Exception('Debe indicar el RIF del proveedor.');
                }
            }
        } else {
            throw new Exception('Debe indicar el RIF del proveedor.');
        }
        if (count($params[self::E_RIF]) > 0) {
            foreach ($params[self::E_RIF] as $rif) {
                if (!is_numeric($rif)) {
                    throw new Exception('Formato de RIF inválido, debe ser númerico.');
                }
            }
        } else {
            throw new Exception('Formato de RIF inválido, debe ser númerico.');
        }
        
        if (count($params[self::E_BANCOTXT]) > 0) {
            
            for($i=0; $i<sizeof($params[self::E_BANCOTXT]); $i++){
                $rif = $params[self::E_RIF][$i];
                $banco = $params[self::E_BANCOTXT][$i];  
                
                if ($banco != $params[self::E_COD]) {
                    throw new Exception('Rif {'.$rif.'} posee cuenta no perteneciente a entidad bancaria seleccionada');
                }
            }
        } 
        
        
        if (count($params[self::E_MONTO]) > 0) {
            foreach ($params[self::E_MONTO] as $monto) {
                if (empty($monto)) {
                    throw new Exception('Debe indicar monto a pagar.');
                }
            }
        } else {
            throw new Exception('Debe indicar monto a pagar.');
        }
        
        if (count($params[self::E_MONTO]) > 0) {
            foreach ($params[self::E_MONTO] as $monto) {
                if (!is_numeric($monto)) {
                    throw new Exception('Formato númerico inválido: 1. El monto ingresado no es númerico. 2. Debe colocar "." como separador de decimales.');
                }
            }
        } else {
            throw new Exception('Formato númerico inválido: 1. El monto ingresado no es númerico. 2. Debe colocar "." como separador de decimales.');
        }
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
        $tInterfaz= new Application_Model_DbTable_Interfaz();
        //Transacciones
        Zend_Db_Table::getDefaultAdapter()->beginTransaction();
        try {
            $this->_validarDatos($params);
            
            $realizadoPor = Fmo_Model_Seguridad::getUsuarioSesion()->{Fmo_Model_Personal::SIGLADO};
            $registro = $tInterfaz->createRow();
                     
            $registro->titulo = strtoupper($params[self::E_TITULO]);
            $registro->codigo_banco = $params[self::E_COD];
            $registro->fecha = $params[self::E_FECHA];
            $registro->usuario = $realizadoPor;
            $registro->numpagos = count($params[self::E_RIF]);
            $registro->cliente = "PROVEEDORES";
            $registro->estado = 0;
            $registro->partes = 1;
            
            $registro->save();
            //Zend_Debug::dd($registro);
            
            $total=0;
            
            
            for($i=0;$i < count($params[self::E_RIF]) ;$i++){
                $data = array(
                    'fk_id_interfaz'=>$registro->id_interfaz,
                    'rif' => $params[self::E_RIF][$i],
                    'nombre' => $this->limpiar($params[self::E_REPRESENTANTE][$i]),
                    'numcuenta' =>$params[self::E_NUMERO_CUENTA][$i],
                    'tipo_cuenta' => $params[self::E_TIPO_CUENTA][$i],
                    'monto' => $params[self::E_MONTO][$i],
                    'codigo_banco' => $params[self::E_BANCOTXT][$i],
                    'nacionalidad' => $params[self::E_NACIONALIDAD][$i],
                    'grupo' => 1,
                    $total += $params[self::E_MONTO][$i]

                );
                $tDetalle = new Application_Model_DbTable_DetalleInterfaz();
                $tDetalle->createRow($data)->save(); 
                         
            }
            $registro->monto_total = $total;
            $registro->save();
                  
            //exit;
            Zend_Db_Table::getDefaultAdapter()->commit();
        } catch (Exception $ex) {
            Zend_Db_Table::getDefaultAdapter()->rollBack();
            throw $ex;
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