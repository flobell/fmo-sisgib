<?php


class CuentasfmoController extends Fmo_Controller_Action_Abstract
{
  
    public function listarAction()
    {
        
        try {
            
            $tCuentas= new Application_Model_DbTable_CuentasFMO();
            $select = $tCuentas->select()->order('cuentas');
            //Zend_Debug::dd($select);
            $datos = $this->paginator($select, 10);
        } catch (Exception $ex) {
            $datos = null;
            $this->addMessageException($ex);
        }
        $this->view->cuentas = $datos;
    }
    
    
   
    public function nuevoAction()
    {
        $this->view->jQuery()->enable();
        $this->view->jQuery()->uienable();
        //$this->view->headScript()
        //->appendFile($this->view->baseUrl('public/js/sisgib.js'));
                                
        $formulario = new Application_Form_CuentasFMO();
        $urlVolver = "default/cuentasfmo/nuevo";
        $this->view->formulario = $formulario;
        $request = $this->getRequest();
        if ($this->getParam(Application_Form_CuentasFMO::E_CANCELAR)) {
            $this->addMessageInformation('Ha cancelado la operación');
            $this->redirect("/");
        }
        try{
            //Se verifica si se ha recibido una petición via POST
            if ($request->isPost()){
                $post = $request->getPost();
                //Zend_Debug::dd($post);
            if (isset($post[Application_Form_CuentasFMO::E_GUARDAR])) {
               
                if ($formulario->isValid($this->getRequest()->getPost()) ) {
                    $formulario->guardarNuevo($post);
                    $this->addMessageSuccessful("La cuenta débito ha sido agregada exitosamente " );
                    $this->redirect($urlVolver);
                    
                }

            }
            }
        } catch (Exception $ex){
            $this->addMessageError('Error accediendo a la base de datos:  ' . $ex->getMessage());
                $this->redirect("$urlVolver");
        }
       
    }
    
   
    public function editarAction()
    {
        $this->view->jQuery()->enable();
        $this->view->jQuery()->uienable();
        //$this->view->headScript()
        //->appendFile($this->view->baseUrl('public/js/sisgib.js'));

        $urlVolver = "default/cuentasfmo/listar/page/{$this->getParam('page')}";


        if ($this->getParam(Application_Form_CuentasFMO::E_CANCELAR)) {
            $this->redirect($urlVolver);
        }

        $pCodigo = $this->getParam('numcuenta');
        try {
            $formulario = new Application_Form_CuentasFMO();
            $this->view->formulario = $formulario;
            $formulario->valoresPorDefecto($pCodigo);
            
            if ($this->getRequest()->isPost()) {
                if ($formulario->isValid($this->getRequest()->getPost())){
                    $post = $this->getRequest()->getPost();
                    $idCuentasModificado = $formulario->guardarModificacion($pCodigo, $post);
                    $this->addMessageSuccessful("Se guardo exitosamente el registro de " .$idCuentasModificado );
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
        //Zend_Debug::dd($pPage);
        $urlVolver = "default/cuentasfmo/listar/page/$pPage";
        $pCodigo = $this->getParam('numcuenta');
        $pConfirmar = $this->getParam('confirmar', 'N');
        try {
            $cuentasfmo = Application_Model_DbTable_CuentasFMO::findOneById($pCodigo);
            if ($pConfirmar == 'S') {
                $mensaje = "Se eliminó exitosamente el registro '{$cuentasfmo->numcuenta}'";
                $cuentasfmo->delete();
                $this->addMessageSuccessful($mensaje);
                $this->redirect($urlVolver);
            }
        } catch (Exception $ex) {
            if ($ex->getCode() == 23503) {
                $this->addMessageWarning('No puede eliminar esta cuenta porque existen registros asociados');
            } else {
                $this->addMessageException($ex);
                $this->redirect($urlVolver);
            }
        }
        $this->view->cuentasfmo = $cuentasfmo;
        $this->view->page = $pPage;
    }
    
    
    
    


}