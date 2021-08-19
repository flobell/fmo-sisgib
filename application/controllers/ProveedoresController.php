<?php


class ProveedoresController extends Fmo_Controller_Action_Abstract
{
  
    public function listarAction()
    {
        //Application_Model_Proveedores::getCuentaByRif('24889053');
        
        try {
            
            $tProveedores= new Application_Model_DbTable_Proveedores();
            $select = $tProveedores->select()->order('rif');
            //Zend_Debug::dd($select);
            $datos = $this->paginator($select, 20);
        } catch (Exception $ex) {
            $datos = null;
            $this->addMessageException($ex);
        }
        $this->view->proveedores = $datos;
     /*   
        $datos = null;
        try {
            $tProveedores= Application_Model_Proveedores::getAllCuentasProveedores();
            $datos = $this->paginator($tProveedores, 10);
        } catch (Exception $e) {
            $this->addMessageException($e);
        }

        $this->view->proveedores = $datos;*/
    }
    
    
   
    public function nuevoAction()
    { 
        $this->view->jQuery()->enable();
        $this->view->jQuery()->uienable();
        
        //$this->view->headScript()
        //->appendFile($this->view->baseUrl('public/js/proveedores/nuevo.js'));
        
        $urlVolver = "default/proveedores/nuevo";
        $formulario = new Application_Form_Proveedores();
        $this->view->formulario = $formulario;
        $request = $this->getRequest();
        if ($this->getParam(Application_Form_Proveedores::E_CANCELAR)) {
            $this->addMessageInformation('Ha cancelado la operación');
            $this->redirect("/");
        }
        try{
            //Se verifica si se ha recibido una petición via POST
            if ($request->isPost()){
                $post = $request->getPost();
                //Zend_Debug::dd($post);
            if (isset($post[Application_Form_Proveedores::E_GUARDAR])) {
               
                if ($formulario->isValid($this->getRequest()->getPost()) ) {
                    $formulario->guardarNuevo($post);
                    $this->addMessageSuccessful("El proveedor ha sido agregado exitosamente " );
                    $this->redirect($urlVolver);
                }

            }
            
           
            
            }
        } catch (Exception $ex){
           $this->addMessageError('Error accediendo a la base de datos: ' . $ex->getMessage());
                $this->redirect("$urlVolver");
        }
        
        
       /*
        try {
            
            $tProveedores= new Application_Model_DbTable_Proveedores();
            $select = $tProveedores->select()->order('proveedores');
            //Zend_Debug::dd($select);
            $datos = $this->paginator($select, 10);
        } catch (Exception $ex) {
            $datos = null;
            $this->addMessageException($ex);
        }
        $this->view->proveedores = $datos;
        */
        
       
   
        
        
    }
    
   
    public function editarAction()
    {
        $this->view->jQuery()->enable();
        $this->view->jQuery()->uienable();
        
        $this->view->headScript()
                ->appendFile($this->view->baseUrl('public/js/proveedores/nuevo.js'));
        
        $urlVolver = "default/proveedores/listar/page/{$this->getParam('page')}";
       
        //var_dump($cpCodigo, $pCodigo);
        //exit;
        
        if ($this->getParam(Application_Form_EditarProveedores::E_CANCELAR)) {
            $this->redirect($urlVolver);
        }

        $pCodigo = $this->getParam('rif');
        try {
            $formulario = new Application_Form_EditarProveedores();
            $this->view->formulario = $formulario;
                        
            $formulario->valoresPorDefecto($pCodigo);
            if ($this->getRequest()->isPost()) {
                 if ($formulario->isValid($this->getRequest()->getPost())){
                        $post = $this->getRequest()->getPost();
                        $idProveedoresModificado = $formulario->guardarModificacion($pCodigo, $post);
                        $this->addMessageSuccessful("Se guardo exitosamente el registro de " .$idProveedoresModificado );
                        $this->redirect($urlVolver);
                 }
            } 
        } catch (Exception $ex) {
            $formulario = null;
            $this->addMessageException($ex);
            $this->redirect($urlVolver);
            }
    }

    
    public function eliminarAction()
    {
        $pPage = $this->getParam('page');
        $urlVolver = "default/proveedores/listar/page/$pPage";
        $pCodigo = $this->getParam('rif');
        $pConfirmar = $this->getParam('confirmar', 'N');
        try {
            $proveedores = Application_Model_DbTable_Proveedores::findOneById($pCodigo);
            if ($pConfirmar == 'S') {
                $mensaje = "Se eliminó exitosamente el registro '{$proveedores->rif}'";
                $proveedores->delete();
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
        $this->view->proveedores = $proveedores;
        $this->view->page = $pPage;
        

    }
    
    public function detalleAction()
    {
    
        $pCodigo= $this->getParam('rif');
        try {

            $pCuentas = Application_Model_Proveedores::getAllCuentas($pCodigo);
            //Zend_Debug::dd($cliente);
            $datos = $this->paginator($pCuentas, 10);
        } catch (Exception $ex) {
            $datos = null;
            $this->addMessageException($ex);
        }
        $this->view->cuentas_proveedores = $datos;
        
        
    }
    
    public function nuevacuentaAction(){
        $this->view->jQuery()->enable();
        $this->view->jQuery()->uienable();
        
        $this->view->headScript()
                ->appendFile($this->view->baseUrl('public/js/proveedores/nuevo.js'));
        
        $urlVolver = "default/proveedores/listar";
        
        $pRIF = $this->getParam('rif');
        $pNacionalidad = $this->getParam('nacionalidad');
        
        
        $formulario = new Application_Form_NuevaCuentaBancaria();
        $formulario->getElement(Application_Form_NuevaCuentaBancaria::E_RIF)->setValue($pRIF);
        $formulario->getElement(Application_Form_NuevaCuentaBancaria::E_LETRA)->setValue($pNacionalidad);
        $this->view->formulario = $formulario;
        $request = $this->getRequest();
        if ($this->getParam(Application_Form_NuevaCuentaBancaria::E_CANCELAR)) {
            $this->addMessageInformation('Ha cancelado la operación');
            $this->redirect($urlVolver);
        }
        try{
            //Se verifica si se ha recibido una petición via POST
            if ($request->isPost()){
                $post = $request->getPost();
                //Zend_Debug::dd($post);
            if (isset($post[Application_Form_NuevaCuentaBancaria::E_GUARDAR])) {
               
                if ($formulario->isValid($this->getRequest()->getPost()) ) {
                    $formulario->guardarNuevo($post);
                    $this->addMessageSuccessful("La cuenta bancaria ha sido agregada exitosamente " );
                    $this->redirect($urlVolver);
                }

            }
            
           
            
            }
        } catch (Exception $ex){
           $this->addMessageError('Error accediendo a la base de datos: ' . $ex->getMessage());
                $this->redirect("$urlVolver");
        }
    }
    
    
    
    public function eliminarcuentaAction()
    {
        $pPage = $this->getParam('page');
        
        //Zend_Debug::dd($pPage);
        $urlVolver = "default/proveedores/listar/page/$pPage";
        $pCodigo = $this->getParam('numcuenta');
        $pConfirmar = $this->getParam('confirmar', 'N');
        try {
            $cuentaproveedores = Application_Model_DbTable_CuentasProveedores::findOneById($pCodigo);
            if ($pConfirmar == 'S') {
                $mensaje = "Se eliminó exitosamente el registro '{$cuentaproveedores->numcuenta}'";
                $cuentaproveedores->delete();
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
        $this->view->proveedores = $cuentaproveedores;
        $this->view->page = $pPage;
        

    }
    
    public function editarcuentaAction()
    {
        $this->view->jQuery()->enable();
        $this->view->jQuery()->uienable();
        
        $this->view->headScript()
                ->appendFile($this->view->baseUrl('public/js/proveedores/nuevo.js'));
        
        $urlVolver = "default/proveedores/listar/page/{$this->getParam('page')}";
       
        //var_dump($cpCodigo, $pCodigo);
        //exit;
        
        if ($this->getParam(Application_Form_NuevaCuentaBancaria::E_CANCELAR)) {
            $this->redirect($urlVolver);
        }
        $pNacionalidad = $this->getParam('nacionalidad');
        $pCodigo = $this->getParam('numcuenta');
        try {
            $formulario = new Application_Form_NuevaCuentaBancaria();
                    $formulario->getElement(Application_Form_NuevaCuentaBancaria::E_LETRA)->setValue($pNacionalidad);
            $this->view->formulario = $formulario;
                        
            $formulario->valoresPorDefecto($pCodigo);
            if ($this->getRequest()->isPost()) {
                 if ($formulario->isValid($this->getRequest()->getPost())){
                        $post = $this->getRequest()->getPost();
                        $idCuentaModificado = $formulario->guardarModificacion($pCodigo, $post);
                        $this->addMessageSuccessful("Se guardo exitosamente el registro de " .$idCuentaModificado );
                        $this->redirect($urlVolver);
                 }
            } 
        } catch (Exception $ex) {
            $formulario = null;
            $this->addMessageException($ex);
            $this->redirect($urlVolver);
            }
    }

}