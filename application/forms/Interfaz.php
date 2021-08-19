<?php


class Application_Form_Interfaz extends Fmo_Form_Abstract
{
    
    //CONSTANTES PARA DATOS BANCARIOS 
    const E_COD = 'cod';

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

        //$tCargar = new Application_Model_DbTable_Cargar();
 

     
////////////// DATOS BANCARIOS ///////////////////////////////
        
        $eCodigo = new Zend_Form_Element_Select(self::E_COD);
        $eCodigo->setLabel('Código.')
               ->addMultiOption("",'Seleccionar Banco.')
               ->addMultiOptions(Application_Model_DbTable_Bancos::getPairsWithOrder('codigo', new Zend_Db_Expr("bcv ||' - ' || nombre_banco"), "activo=true AND bcv NOT IN ('0001')",  'codigo'))
               ->setAttrib('onchange', "this.form.submit();")
               ->setRequired();
        $this->addElement($eCodigo);
     

   

    }

}