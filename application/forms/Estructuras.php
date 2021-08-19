<?php


class Application_Form_Estructuras extends Fmo_Form_Abstract
{
    //CONSTANTES PARA DATOS ESTRUCTURA
    const E_TITULO = 'titulo';
    const E_FECHA = 'fecha';
    const E_TIPO_REGISTROS = 'tiporegistros';
  
    //CONSTANTES PARA SELECCIONAR BANCO 
    const E_COD = 'cod';


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

        $tEstructuras = new Application_Model_DbTable_Estructuras();
 
////////////// SELECCION DE BANCO ///////////////////////////////
        
                       
       $eCodigo = new Zend_Form_Element_Select(self::E_COD);
        $eCodigo->setLabel('Código.')
               ->addMultiOption("",'Código.')
               ->addMultiOptions(Application_Model_DbTable_Bancos::getPairsWithOrder('codigo', new Zend_Db_Expr("bcv ||' - ' || nombre_banco"), NULL,  'codigo'))
               ->setRequired()
               ->setAttrib("oninput", "getDatosBanco()")
                        ->setAttrib("url", $this->getView()->url(array("controller" => "ajax", "action" => "getbanco")));
               //->addValidator('StringLength', false, array('min' => 1, 'max' => 100));           
        $this->addElement($eCodigo);
        
     
////////////// ESTRUCTURAS NUEVAS ///////////////////////////////
       
        
        $eFecha= new Zend_Form_Element_Text(self::E_FECHA); //acomodar
        $eFecha->setLabel('Fecha')
            ->setAttrib('size', '5')    
            ->setRequired(true)
            ->setValue(date('d/m/y'))
            ->setAttrib("readonly",""); 
        $this->addElement($eFecha);

  
        $eTituloEstructura = new Zend_Form_Element_text(self::E_TITULO);
        $eTituloEstructura->setLabel('Titulo de Estructura')
               ->setAttrib('size', '20')
               ->setAttrib('maxlength', '80')
               ->setRequired();
        $this->addElement($eTituloEstructura);

        $eTipoRegistro = new Zend_Form_Element_Select(self::E_TIPO_REGISTROS);
        $eTipoRegistro->setLabel('Tipo de Registro')
               ->addMultiOption("",'Tipo de Registro')
               ->addMultiOptions(Application_Model_DbTable_TipoRegistro::getPairsWithOrder('nombre', new Zend_Db_Expr("nombre"), NULL,  'nombre'))
               ->setIsArray(true)
               ->setRequired();
        $this->addElement($eTipoRegistro);
        
    ////////////// BOTONES DE GUARDADO Y CANCELADO ///////////////////////////////
        
        $eGuardar = new Zend_Form_Element_Submit(self::E_GUARDAR);
        $eGuardar->setLabel('Generar Estructura')
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

    public function valoresPorDefecto($codigo_estructura)
    {

        $estructuras = Application_Model_DbTable_Estructuras::findOneById($codigo_estructura);

        $this->setDefault(self::E_COD, $estructuras->codigo_banco)
             ->setDefault(self::E_TITULO, $estructuras->titulo);
           
    }

    /**
     * Método para guardar los datos en la Base de Datos
     */
    public function guardarNuevo($params)
    {
        $tEstructuras = new Application_Model_DbTable_Estructuras();
        try {
            //Transacciones
            Zend_Db_Table::getDefaultAdapter()->beginTransaction();

            $realizadoPor = Fmo_Model_Seguridad::getUsuarioSesion()->{Fmo_Model_Personal::SIGLADO};
            $registro = $tEstructuras->createRow();
                    
            $registro->titulo = strtoupper($params[self::E_TITULO]);
            $registro->fecha = $params[self::E_FECHA];
            $registro->codigo_banco = $params[self::E_COD];
            $registro->usuario = $realizadoPor;
            $registro->save();
            
            for($i=0;$i < count($params[self::E_TIPO_REGISTROS]) ;$i++){
                $data = array(
                    'fk_codigo_estructura'=>$registro->codigo_estructura,
                    'tipo_registro' => $params[self::E_TIPO_REGISTROS][$i]
                    
                );
                
                $tFormato = new Application_Model_DbTable_Formato();
                $tFormato->createRow($data)->save(); 
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
    public function guardarModificacion($codigo_estructura, $params)
    {
        $estructuras = Application_Model_DbTable_Estructuras::findOneById($codigo_estructura);
        try {
            //Transacciones
            Zend_Db_Table::getDefaultAdapter()->beginTransaction();
            
            $estructuras->codigo_banco = $params[self::E_COD];
            $estructuras->titulo = strtoupper($params[self::E_TITULO]);
            $estructuras->fecha = $params[self::E_FECHA];
            
            Zend_Db_Table::getDefaultAdapter()->commit();
            return $estructuras->save();
            
        } catch (Exception $ex) {
            Zend_Db_Table::getDefaultAdapter()->rollBack();
            throw $ex;
        }
       

        
    }


}