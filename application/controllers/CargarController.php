<?php


class CargarController extends Fmo_Controller_Action_Abstract
{
  
    
    public function nuevoAction()
    {/*
         //Se habilitan las librerias de JQuery
        $this->view->jQuery()->enable();
        $this->view->jQuery()->uienable();
        
        $this->view->headScript()
                ->appendFile($this->view->baseUrl('public/js/interfaces/nuevo.js'));
        
        //Librerias para el select2
        //$this->view->JQueryX()->enable();
        //$this->view->select2()->enable();
        //$this->view->headScript()
                //->appendFile($this->view->baseUrl('public/js/estructuras/nuevo.js'));
        
        $urlVolver = "default/cargar/nuevo";
        $formulario = new Application_Form_Cargar();
        //$formulario->activarTablas();//Para activar las tablas dependiendo del tipo cliente
        $this->view->formulario = $formulario;
        
        
        $request = $this->getRequest();
        if ($this->getParam(Application_Form_Cargar::E_CANCELAR)) {
            $this->addMessageInformation('Ha cancelado la operación');
            $this->redirect("/");
        }
        try{
            //Se verifica si se ha recibido una petición via POST
            if ($request->isPost()){
                $post = $request->getPost();
                
                //Zend_Debug::dd($post);
            if (isset($post[Application_Form_Cargar::E_CARGAR])) {
               
                if (!$formulario->isValid($this->getRequest()->getPost()) ) {
                    $formulario->guardarNuevo($post);
                    $this->addMessageSuccessful("Ha cargado la interfaz satisfactoriamente. " );
                    $this->redirect($urlVolver);
                }

            }
            }
        } catch (Exception $ex){
            $this->addMessageError('Error accediendo a la base de datos: ' . $ex->getMessage());
                $this->redirect("$urlVolver");
        }
        
       */
    }
    
    public function proveedoresAction()
    {
         //Se habilitan las librerias de JQuery
        $this->view->jQuery()->enable();
        $this->view->jQuery()->uienable();
        
        //$this->view->headScript()
        //->appendFile($this->view->baseUrl('public/js/interfaces/nuevo.js'));
        
        //Librerias para el select2
        //$this->view->JQueryX()->enable();
        //$this->view->select2()->enable();
        //$this->view->headScript()
                //->appendFile($this->view->baseUrl('public/js/estructuras/nuevo.js'));
        
        $urlVolver = "default/cargar/proveedores";
        $formulario = new Application_Form_CargarProveedores();
        //$formulario->activarTablas();//Para activar las tablas dependiendo del tipo cliente
        
        $request = $this->getRequest();
        if ($this->getParam(Application_Form_CargarProveedores::E_CANCELAR)) {
            $this->addMessageInformation('Ha cancelado la operación');
            $this->redirect("/");
        }
        try{
            //Se verifica si se ha recibido una petición via POST
            if ($request->isPost()){
                $post = $request->getPost();
                $formulario->setDefaults($post);
                //$formulario->setDefault('titulo', $post['titulo']);
            if (isset($post[Application_Form_CargarProveedores::E_CARGAR])) {
               
                //if ($formulario->isValid($this->getRequest()->getPost()) ) {
                    $formulario->guardarNuevo($post);
                    $this->addMessageSuccessful("Ha cargado la interfaz satisfactoriamente. " );
                    $this->redirect($urlVolver);
                //}

            }
            }
        } catch (Exception $ex){
            $this->addMessageError('Error accediendo a la base de datos: ' . $ex->getMessage());
            $this->redirect("$urlVolver");
        }
        
        $this->view->formulario = $formulario;
        
       
    }
    
    public function trabajadoresAction()
    {
         //Se habilitan las librerias de JQuery
        $this->view->jQuery()->enable();
        $this->view->jQuery()->uienable();
        
        //$this->view->headScript()
        //->appendFile($this->view->baseUrl('public/js/interfaces/nuevo.js'));

        
        $urlVolver = "default/cargar/trabajadores";
        $formulario = new Application_Form_CargarTrabajadores();
        //$formulario->activarTablas();//Para activar las tablas dependiendo del tipo cliente
        $this->view->formulario = $formulario;
        
        
        $request = $this->getRequest();
        if ($this->getParam(Application_Form_CargarTrabajadores::E_CANCELAR)) {
            $this->addMessageInformation('Ha cancelado la operación');
            $this->redirect("/");
        }
        try{
            //Se verifica si se ha recibido una petición via POST
            if ($request->isPost()){
                $post = $request->getPost();
                
                //Zend_Debug::dd($post);
            if (isset($post[Application_Form_CargarTrabajadores::E_CARGAR])) {
               
                //if ($formulario->isValid($this->getRequest()->getPost()) ) {
                    $formulario->guardarNuevo($post);
                    $this->addMessageSuccessful("Ha cargado la interfaz satisfactoriamente. " );
                    $this->redirect($urlVolver);
                //}

            }
            }
        } catch (Exception $ex){
            $this->addMessageError('Error accediendo a la base de datos: ' . $ex->getMessage());
                $this->redirect("$urlVolver");
        }
        
       
    }
    
    public function excelAction()
    {
        //Se habilitan las librerias de JQuery
        $this->view->jQuery()->enable();
        $this->view->jQuery()->uienable();
        
        $urlVolver = "default/cargar/excel";
        $formulario = new Application_Form_Excel();
        //$formulario->activarTablas();//Para activar las tablas dependiendo del tipo cliente
        $this->view->formulario = $formulario;
        
        
        $request = $this->getRequest();
        if ($this->getParam(Application_Form_Excel::E_CANCELAR)) {
            $this->addMessageInformation('Ha cancelado la operación');
            $this->redirect("/");
        }
        try{
            //Se verifica si se ha recibido una petición via POST
            if ($request->isPost()){
                $post = $request->getPost();
                
                //Zend_Debug::dd($post);
            if (isset($post[Application_Form_Excel::E_CARGAR])) {
               
                if ($formulario->isValid($this->getRequest()->getPost()) ) {
                    $formulario->guardarNuevo($post);
                    $this->addMessageSuccessful("Ha cargado la interfaz satisfactoriamente. " );
                    $this->redirect($urlVolver);
                }

            }
            }
        } catch (Exception $ex){
            $this->addMessageError($ex->getMessage());
                $this->redirect("$urlVolver");
        }
        
       
    }

    public function patriaAction(){
        //Se habilitan las librerias de JQuery
        $this->view->jQuery()->enable();
        $this->view->jQuery()->uienable();
        
        $urlVolver = "default/cargar/patria";
        $formulario = new Application_Form_Patria();
        //$formulario->activarTablas();//Para activar las tablas dependiendo del tipo cliente
        $this->view->formulario = $formulario;
        
        
        $request = $this->getRequest();
        try{
            //Se verifica si se ha recibido una petición via POST
            if ($request->isPost()){
                $post = $request->getPost();
                    //Zend_Debug::dd($post);
                if (isset($post[Application_Form_Patria::E_CARGAR])) {
                    $formulario->guardarNuevo($post);
                    $this->addMessageSuccessful("Ha cargado la interfaz satisfactoriamente. " );
                    $this->redirect($urlVolver);
                }
            }
        } catch (Exception $ex){
            $this->addMessageError($ex->getMessage());
                $this->redirect("$urlVolver");
        }
                
    }

    public function patriatxtAction(){
        //Se habilitan las librerias de JQuery
        $this->view->jQuery()->enable();
        $this->view->jQuery()->uienable();
        
        $urlVolver = "default/cargar/patriatxt";
        $formulario = new Application_Form_PatriaTxt();
        //$formulario->activarTablas();//Para activar las tablas dependiendo del tipo cliente
        $this->view->formulario = $formulario;
        
        
        $request = $this->getRequest();
        try{
            //Se verifica si se ha recibido una petición via POST
            if ($request->isPost()){
                $post = $request->getPost();
                    //Zend_Debug::dd($post);
                if (isset($post[Application_Form_PatriaTxt::E_CARGAR])) {
                    $formulario->guardarNuevo($post);
                    $this->addMessageSuccessful("Ha cargado la interfaz satisfactoriamente. " );
                    $this->redirect($urlVolver);
                }
            }
        } catch (Exception $ex){
            $this->addMessageError($ex->getMessage());
                $this->redirect("$urlVolver");
        }
                
    }


    public function editarAction()
    {/*
        $urlVolver = "default/cargar/listar/page/{$this->getParam('page')}";

        if ($this->getParam(Application_Form_Cargar::E_CANCELAR)) {
            $this->redirect($urlVolver);
        }

        $pCodigo = $this->getParam('codigo');
        try {
            $form = new Application_Form_Cargar;
            $this->view->form = $form;
            $form->valoresPorDefecto($pCodigo);
            if ($this->getRequest()->isPost()) {
                    $post = $this->getRequest()->getPost();
                    $idCargarModificado = $form->guardarModificacion($pCodigo, $post);
                    $this->addMessageSuccessful("Se guardo exitosamente el registro de " .$idCargarModificado );
                    $this->redirect($urlVolver);
                
            } 
        } catch (Exception $ex) {
            $form = null;
            $this->addMessageException($ex);
            $this->redirect($urlVolver);
            }*/
    }

    
    public function eliminarAction()
    {/*
        $pPage = $this->getParam('page');
        $urlVolver = "default/cargar/listar/page/$pPage";
        $pCodigo = $this->getParam('codigo');
        $pConfirmar = $this->getParam('confirmar', 'N');

        try {
            $cargar = Application_Model_DbTable_Cargar::findOneById($pCodigo);
            if ($pConfirmar == 'S') {
                $mensaje = "Se eliminó exitosamente el registro '{$Cargar->id_cargar}'";
                $cargar->delete();
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
        $this->view->cargar = $cargar;
        $this->view->page = $pPage;*/
    }
    
    
    
}