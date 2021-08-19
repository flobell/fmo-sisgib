<?php


class EstructurasController extends Fmo_Controller_Action_Abstract
{
  
    public function listarAction()
    {/*
        $urlVolver = "default/estructuras/listar/page/{$this->getParam('page')}";
        try {
            
            //$tCliente = new Application_Model_DbTable_Cliente();
            //$select = $tCliente->select()->order('nombre_empresa');
            $estructuras = Application_Model_Estructuras::getAllEstructuras();
            //Zend_Debug::dd($cliente);
            $datos = $this->paginator($estructuras, 10);
        } catch (Exception $ex) {
            $datos = null;
            $this->addMessageException($ex);
        }
        $this->view->estructuras = $datos;*/
        
        
        try {
            
            $tEstructuras = new Application_Model_DbTable_Estructuras();
            $select = $tEstructuras->select()->order('estructura');
            //Zend_Debug::dd($tEstructuras);
            $datos = $this->paginator($select, 20);
        } catch (Exception $ex) {
            $datos = null;
            $this->addMessageException($ex);
        }
        $this->view->estructura = $datos;
    }
    
    
   
    public function nuevoAction()
    {
         //Se habilitan las librerias de JQuery
        $this->view->JQueryX()->enable();
        $this->view->select2()->enable();

        $urlAvanzar = "default/estructuras/listar";
        $urlVolver = "default/estructuras/nuevo";
        
        $formulario = new Application_Form_Estructuras();
        $this->view->formulario = $formulario;
        $this->view->listar = null;
        $request = $this->getRequest();
        if ($this->getParam(Application_Form_Estructuras::E_CANCELAR)) {
            $this->addMessageInformation('Ha cancelado la operación');
            $this->redirect("/");
        }
       
        
        try{
            //Se verifica si se ha recibido una petición via POST
            if ($request->isPost()){
                $post = $request->getPost();
                //Zend_Debug::dd($post);
            if (isset($post[Application_Form_Estructuras::E_GUARDAR])) {
                if ($formulario->isValid($this->getRequest()->getPost()) ) {
                    $formulario->guardarNuevo($post);
                    $this->addMessageSuccessful("La estructura ha sido generada exitosamente " );
                    $this->redirect($urlAvanzar);
                }

            }
            }
        } catch (Exception $ex){
           $this->addMessageError('Error accediendo a la base de datos: ' . $ex->getMessage());
                $this->redirect("$urlVolver");
        }
       
    }
    
   
    public function editarAction()
    {
        $urlVolver = "default/estructuras/listar/page/{$this->getParam('page')}";

        if ($this->getParam(Application_Form_Estructuras::E_CANCELAR)) {
            $this->redirect($urlVolver);
        }

        $pCodigo = $this->getParam('codigo');
        try {
            $form = new Application_Form_Estructuras();
            $this->view->form = $form;
            $form->valoresPorDefecto($pCodigo);
            if ($this->getRequest()->isPost()) {
                    $post = $this->getRequest()->getPost();
                    $idEstructurasModificado = $form->guardarModificacion($pCodigo, $post);
                    $this->addMessageSuccessful("Se guardo exitosamente el registro de " .$idEstructurasModificado );
                    $this->redirect($urlVolver);
                
            } 
        } catch (Exception $ex) {
            $form = null;
            $this->addMessageException($ex);
            $this->redirect($urlVolver);
            }
    }

    
    public function eliminarAction()
    {
        $pPage = $this->getParam('page');
        $urlVolver = "default/estructuras/listar/page/$pPage";
        $pCodigo = $this->getParam('codigo_estructura');
        $pConfirmar = $this->getParam('confirmar', 'N');

        try {
            $estructuras = Application_Model_DbTable_Estructuras::findOneById($pCodigo);
            if ($pConfirmar == 'S') {
                $mensaje = "Se eliminó exitosamente el registro '{$estructuras->titulo}'";
                $estructuras->delete();
                $this->addMessageSuccessful($mensaje);
                $this->redirect($urlVolver);
            }
        } catch (Exception $ex) {
            if ($ex->getCode() == 23503) {
                $this->addMessageWarning('No puede eliminar porque existen registros asociados');
            } else {
                $this->addMessageException($ex);
                $this->redirect($urlVolver);
            }
        }
        $this->view->estructuras = $estructuras;
        $this->view->page = $pPage;
    }
    
    public function formatoAction()
    {
       $pCodigo= $this->getParam('codigo_estructura');
        try {

            $pFormato = Application_Model_Estructuras::getAllEstructuras($pCodigo);
            //Zend_Debug::dd($cliente);
            $datos = $this->paginator($pFormato, 10);
        } catch (Exception $ex) {
            $datos = null;
            $this->addMessageException($ex);
        }
        $this->view->formato = $datos;
        /*
        try {
            
            //$tCliente = new Application_Model_DbTable_Cliente();
            //$select = $tCliente->select()->order('nombre_empresa');
            $detalle = Application_Model_Estructuras::getAllEstructuras();
            //Zend_Debug::dd($cliente);
            $datos = $this->paginator($detalle, 10);
        } catch (Exception $ex) {
            $datos = null;
            $this->addMessageException($ex);
        }
        $this->view->detalle_estructura = $datos*/
    }
    
    
    public function nuevoformatoAction()
    {
         //Se habilitan las librerias de JQuery
        $this->view->JQueryX()->enable();
        $this->view->select2()->enable();

        $urlVolver = "default/estructuras/listar";
        $pCodigo = $this->getParam('codigo_estructura');
        
        $formulario = new Application_Form_Formato();
        $formulario->getElement(Application_Form_Formato::E_CODIGO)->setValue($pCodigo);
        $this->view->formulario = $formulario;
        $this->view->listar = null;
        $request = $this->getRequest();
        if ($this->getParam(Application_Form_Formato::E_CANCELAR)) {
            $this->addMessageInformation('Ha cancelado la operación');
            $this->redirect("/");
        }
        try{
            //Se verifica si se ha recibido una petición via POST
            if ($request->isPost()){
                $post = $request->getPost();
                //var_dump($this->getAllParams());
                //exit;
             
            if (isset($post[Application_Form_Estructuras::E_GUARDAR])) {
       
                if ($formulario->isValid($this->getRequest()->getPost()) ) {
                    $formulario->guardarNuevo($post);
                    //Zend_Debug::dd($post);
                    $this->addMessageSuccessful("El registro ha sido agregado exitosamente " );
                    
                    $this->redirect($urlVolver);
                }

            }
            }
        } catch (Exception $ex){
           $this->addMessageError('Error accediendo a la base de datos: ' . $ex->getMessage());
                $this->redirect("$urlVolver");
        }
   
    }
    
    public function eliminarformatoAction()
    {
        $pPage = $this->getParam('page');
        
        //Zend_Debug::dd($pPage);
        $urlVolver = "default/estructuras/listar/page/$pPage";
        $pCodigo = $this->getParam('id_formato');
        $pConfirmar = $this->getParam('confirmar', 'N');
        try {
            $formato = Application_Model_DbTable_Formato::findOneById($pCodigo);
            if ($pConfirmar == 'S') {
                $mensaje = "Se eliminó exitosamente el registro '{$formato->campo_relacion}'";
                $formato->delete();
                $this->addMessageSuccessful($mensaje);
                $this->redirect($urlVolver);
            }
        } catch (Exception $ex) {
            if ($ex->getCode() == 23503) {
                $this->addMessageWarning('No puede eliminar este proveedor porque existen registros asociados');
            } else {
                $this->addMessageException($ex);
                $this->redirect($urlVolver);
            }
        }
        $this->view->formato = $formato;
        $this->view->page = $pPage;
        

    }

}