<?php


class Application_Form_TipoRegistro extends Fmo_Form_Abstract
{

    //CONSTANTES PARA DATOS DE ENTIDADES BANCARIAS 
    const E_ID_TIPOREGISTRO = 'id_tiporegistro';
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

        $tTipoRegistro = new Application_Model_DbTable_TipoRegistro();
 


        
     
////////////// DATOS TIPO DE REGISTRO ///////////////////////////////

    
        $eTipoRegistro= new Zend_Form_Element_Text(self::E_NOMBRE);
        $eTipoRegistro->setLabel('Nuevo Tipo de Registro:')
                ->setAttrib('size', '30')
                ->setAttrib('maxlength', '80')
                ->setAttrib('placeholder', "Ingrese Nombre del Tipo de Registro")
                ->setRequired(true);
        $this->addElement($eTipoRegistro); 
        
  
        
        
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
    public function valoresPorDefecto($id_registro)
    {
        $registro = Application_Model_DbTable_TipoRegistro::findOneById($id_registro);

        $this->setDefault(self::E_ID_TIPOREGISTRO, $registro->id_registro);
        $this->setDefault(self::E_NOMBRE, $registro->nombre);
 
    }

    /**
     * Método para guardar los datos en la Base de Datos
     */
    public function guardarNuevo($params)
    {
        $tRegistro = new Application_Model_DbTable_TipoRegistro();
        try {
            //Transacciones
            Zend_Db_Table::getDefaultAdapter()->beginTransaction();
            //Guarda en tabla bancos
            $registro = $tRegistro->createRow();
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
    public function guardarModificacion($id_registro, $params)
    {
        $registro = Application_Model_DbTable_TipoRegistro::findOneById($id_registro);
        
        try {
            //Transacciones
            Zend_Db_Table::getDefaultAdapter()->beginTransaction();
            //Guarda modificacion en tabla 
            $registro->nombre = strtoupper($params[self::E_NOMBRE]);
            Zend_Db_Table::getDefaultAdapter()->commit();
            return $registro->save();
            
        } catch (Exception $ex) {
            Zend_Db_Table::getDefaultAdapter()->rollBack();
            throw $ex;
        }
        

    }


}

