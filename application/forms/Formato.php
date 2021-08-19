<?php


class Application_Form_Formato extends Fmo_Form_Abstract
{

    //CONSTANTE DE DATOS DEL REGISTRO
    const E_CODIGO = 'codigo';
    const E_TIPO_REGISTROS = 'tiporegistros';
    const E_CAMPO_RELACIONAR = 'camporelacionar';
    const E_LONGITUD = 'longitud';
    const E_INICIO = 'inicio';
    const E_TIPODATO = 'tipodato';
    const E_FIN = 'fin';
    const E_ALINEACION = 'alineacion';
    const E_OPERACION = 'operacion';

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

        $tFormato = new Application_Model_DbTable_Estructuras();
 
     
   ////////////// DATOS DEL REGISTRO ///////////////////////////////     
         
       
        $eTipoRegistro = new Zend_Form_Element_Select(self::E_TIPO_REGISTROS);
        $eTipoRegistro->setLabel('Tipo de Registro')
               ->addMultiOption("",'Tipo de Registro')
               ->addMultiOptions(Application_Model_DbTable_TipoRegistro::getPairsWithOrder('nombre', new Zend_Db_Expr("nombre"), NULL,  'nombre'))
               ->setRequired();
        $this->addElement($eTipoRegistro);
        
        $eCodigo = new Zend_Form_Element_Hidden(self::E_CODIGO);
        $eCodigo->setLabel('Codigo de la Estructura')
               ->setAttrib('size', '5')
               ->setAttrib('maxlength', '5')
               ->setAttrib("readonly","")
               ->setRequired();
        $this->addElement($eCodigo);
        
        $eCampoRelacionar = new Zend_Form_Element_Select(self::E_CAMPO_RELACIONAR);
        $eCampoRelacionar->setLabel('Campo a relacionar')
               ->addMultiOption("",'Campo a relacionar.')
               ->addMultiOptions(Application_Model_DbTable_Relacion::getPairsWithOrder('nombre', new Zend_Db_Expr("nombre"), NULL,  'nombre'))
               ->setIsArray(true)
               ->setRequired();
        $this->addElement($eCampoRelacionar);
        
        $eLongitud = new Zend_Form_Element_Text(self::E_LONGITUD);
        $eLongitud->setLabel('Longitud')
               ->setAttrib('size', '5')
               ->setAttrib('maxlength', '5')
               ->setIsArray(true)
               ->setRequired();
        $this->addElement($eLongitud);
        
        $eDato = array("NUMERICO"=>"NUMERICO", "ALFABETICO"=>"ALFABETICO", "ALFANUMERICO"=>"ALFANUMERICO");
        $eTipoDato = new Zend_Form_Element_Select(self::E_TIPODATO);
        $eTipoDato->setLabel('Tipo de Dato')
               ->addMultiOption("",'Tipo de Dato')
               ->addMultiOptions($eDato)
               ->setIsArray(true)
               ->setRequired();
        $this->addElement($eTipoDato);  
        
        $eInicio = new Zend_Form_Element_Text(self::E_INICIO);
        $eInicio->setLabel('Inicio')
               ->setAttrib('size', '5')
               ->setAttrib('maxlength', '5')
               ->setIsArray(true)
               ->setRequired();
        $this->addElement($eInicio);
        
        $eFin = new Zend_Form_Element_Text(self::E_FIN);
        $eFin->setLabel('Fin')
               ->setAttrib('size', '5')
               ->setAttrib('maxlength', '5')
               ->setIsArray(true)
               ->setRequired();
        $this->addElement($eFin);
        
        $eAli = array("IZQUIERDA"=>"IZQUIERDA", "DERECHA"=>"DERECHA");
        $eAlineacion = new Zend_Form_Element_Select(self::E_ALINEACION);
        $eAlineacion->setLabel('Alineación')
               ->addMultiOption("",'Alineación')
               ->addMultiOptions($eAli)
               ->setIsArray(true)
               ->setRequired();
        $this->addElement($eAlineacion);
        
        $eOpera = array("PRIMEROS DIGITOS DE LA CUENTA"=>"PRIMEROS DIGITOS DE LA CUENTA",
                        "ULTIMOS DIGITOS DE LA CUENTA"=>"ULTIMOS DIGITOS DE LA CUENTA",
                        "NINGUNO"=>"NINGUNO",
                        "RELLENAR CON CEROS"=>"RELLENAR CON CEROS",
                        "RELLENAR CON ESPACIOS EN BLANCO"=>"RELLENAR CON ESPACIOS EN BLANCO");
        $eOperacion = new Zend_Form_Element_Select(self::E_OPERACION);
        $eOperacion->setLabel('Operación')
               ->addMultiOption("",'Operación')
               ->addMultiOptions($eOpera)
               ->setIsArray(true)
               ->setRequired();
        $this->addElement($eOperacion);
  
        
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

    public function valoresPorDefecto($idEstructuras)
    {
    /*
        $estructuras = Application_Model_DbTable_CentroAcopio::findOneById($idEstructuras);

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
        //$tFormato= new Application_Model_DbTable_Formato();
        try {
            //Transacciones
            Zend_Db_Table::getDefaultAdapter()->beginTransaction();
            
            for($i=0;$i < count($params[self::E_LONGITUD]) ;$i++){
                    $data = array(
                        'fk_codigo_estructura'=>$params[self::E_CODIGO],
                        'tipo_registro' => $params[self::E_TIPO_REGISTROS],
                        'campo_relacion' => $params[self::E_CAMPO_RELACIONAR][$i],
                        'longitud' =>$params[self::E_LONGITUD][$i],
                        'tipo_dato' => $params[self::E_TIPODATO][$i],
                        'posicion_inicial' => $params[self::E_INICIO][$i],
                        'posicion_final' => $params[self::E_FIN][$i],
                        'operacion' => $params[self::E_OPERACION][$i],
                        'alineacion' => $params[self::E_ALINEACION][$i],
                        
                    );
                     $tFormato = new Application_Model_DbTable_Formato();
                     //echo var_dump($data), '<br><br>';
                     $tFormato->createRow($data)->save(); 
                     //Zend_Debug::dd($registro);
            }
            Zend_Db_Table::getDefaultAdapter()->commit();
            
        } catch (Exception $e) {
            Zend_Db_Table::getDefaultAdapter()->rollBack();
            throw $e;
        }
    }

    /**
     * Método para guardar los datos en la Base de Datos
     */
    public function guardarModificacion($idEstructuras, $params)
    {
       /* $centroacopio = Application_Model_DbTable_Estructuras::findOneById($idCentroAcopio);

        $centroacopio->acopio = $params[self::E_ACOPIO];
        $centroacopio->patio= $params[self::E_PATIO];
        $centroacopio->lote= $params[self::E_LOTE];
     
        return $centroacopio->save();*/
    }


}