<?php


class Application_Form_Relacion extends Fmo_Form_Abstract
{

    //CONSTANTES PARA DATOS DE LOS CAMPOS DE RELACION
    const E_ID_RELACION= 'id_relacion';
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

        $tRelacion = new Application_Model_DbTable_Relacion();
 


        
     
////////////// DATOS CAMPOS DE RELACION ///////////////////////////////


        $eRelacion = new Zend_Form_Element_Text(self::E_NOMBRE);
        $eRelacion->setLabel('Nuevo Campo de Relación:')
                ->setAttrib('size', '30')
                ->setAttrib('maxlength', '80')
                ->setAttrib('placeholder', "Ingrese Nombre Campo de Relación")
                ->setRequired(true);
        $this->addElement($eRelacion); 
        
  
        
        
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
    public function valoresPorDefecto($id_relacion)
    {
        $relacion = Application_Model_DbTable_Relacion::findOneById($id_relacion);

        $this->setDefault(self::E_ID_RELACION, $relacion->id_relacion);
        $this->setDefault(self::E_NOMBRE, $relacion->nombre);
 
    }

    /**
     * Método para guardar los datos en la Base de Datos
     */
    public function guardarNuevo($params)
    {
        $tRelacion = new Application_Model_DbTable_Relacion();
        try {
            
            //Guarda en tabla bancos
            $registro = $tRelacion->createRow();
            $registro->nombre = $params[self::E_NOMBRE];

            $registro->save();
     
        } catch (Exception $e) {
            
            throw $e;
        }
    }

    /**
     * Método para guardar los datos en la Base de Datos
     */
    public function guardarModificacion($id_relacion, $params)
    {
        $relacion = Application_Model_DbTable_Relacion::findOneById($id_relacion);
                
        //Guarda modificacion en tabla 
        $relacion->relacion = $params[self::E_NOMBRE];

        return $relacion->save();

    }


}

