<?php

class AjaxController extends Zend_Controller_Action
{
    
    /**
     * Inicialización del controlador
     */
    public function init() {
        $context = $this->_helper->ajaxContext();
        foreach (get_class_methods(__CLASS__) as $method) {
            if (strpos($method, 'Action') !== false) {
                $context->addActionContext($method, 'json');
            }
        }
        $context->initContext();
    }
    
    public function getdatosAction() {
        //esta accion no usara layout.phtml
        $this->_helper->layout->disableLayout();
        //esta accion no renderizara su contenido en getdatosusuario.phtml
        $this->_helper->viewRenderer->setNoRender();

        $codigo = $this->getParam('proveedores');

        if ($codigo) {
            $bancos = Application_Model_Proveedores::findByCedula($codigo)->toArray();
        echo (count($bancos) > 0) ? json_encode($bancos) : '';
        } else {
            echo '';
        }
        
    }
    
    
     public function getbancoAction() {
        //esta accion no usara layout.phtml
        $this->_helper->layout->disableLayout();
        //esta accion no renderizara su contenido en getdatosusuario.phtml
        $this->_helper->viewRenderer->setNoRender();

        $codigo = $this->getParam('bancos');

     if ($codigo) {
            $bancos = Application_Model_Bancos::findByCodigo($codigo)->toArray();
            echo (count($bancos) > 0) ? json_encode($bancos) : '';
        } else {
            echo '';
        }
        
    }
    
    
    
    public function getrifAction() {
        //esta accion no usara layout.phtml
        $this->_helper->layout->disableLayout();
        //esta accion no renderizara su contenido en getdatosusuario.phtml
        $this->_helper->viewRenderer->setNoRender();

        $rif = $this->getParam('proveedores');

        if ($rif) {
            $proveedores = Application_Model_Proveedores::getCuentaByRif($rif);
            echo (count($proveedores) > 0) ? json_encode($proveedores) : '';
        } else {
            echo '';
        }
        
     }
     
    
     
     
    public function getfichaAction() {
        //esta accion no usara layout.phtml
        $this->_helper->layout->disableLayout();
        //esta accion no renderizara su contenido en getdatosusuario.phtml
        $this->_helper->viewRenderer->setNoRender();

        $ficha = $this->getParam("ficha");
        if ($ficha) {
            $mdlPersona = new Application_Model_Trabajadores();
            $mdlPersona->addFilterByActividadActivo()
                       ->addFilterByFicha($ficha);
            $persona = $mdlPersona->findOne();

            echo $persona ? json_encode($persona) : '';
        } else {
            echo '';
        }
    } 

}
