<?php


class TipocuentaController extends Fmo_Controller_Action_Abstract
{
  
    public function listarAction()
    {
        
         
        try {
            
            $tTipoCuenta= new Application_Model_DbTable_TipoCuenta();
            $select = $tTipoCuenta->select()->order('id_tipo');
            //Zend_Debug::dd($select);
            $datos = $this->paginator($select, 10);
        } catch (Exception $ex) {
            $datos = null;
            $this->addMessageException($ex);
        }
        $this->view->tipocuenta = $datos;
    }
    
    
   
    public function nuevoAction()
    {
        $this->view->jQuery()->enable();
        $this->view->jQuery()->uienable();
        
        $this->view->headScript()
                ->appendFile($this->view->baseUrl('public/js/tipocuenta/nuevo.js'));
        
        $formulario = new Application_Form_TipoCuenta();
        $urlVolver = "default/tipocuenta/nuevo";
        $this->view->formulario = $formulario;
        $request = $this->getRequest();
        if ($this->getParam(Application_Form_TipoCuenta::E_CANCELAR)) {
            $this->addMessageInformation('Ha cancelado la operación');
            $this->redirect("/");
        }
        try{
            //Se verifica si se ha recibido una petición via POST
            if ($request->isPost()){
                $post = $request->getPost();
                //Zend_Debug::dd($post);
            if (isset($post[Application_Form_TipoCuenta::E_GUARDAR])) {
               
                if ($formulario->isValid($this->getRequest()->getPost()) ) {
                    $formulario->guardarNuevo($post);
                    $this->addMessageSuccessful("El tipo de cuenta ha sido agregado exitosamente " );
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
        $urlVolver = "default/tipocuenta/listar/page/{$this->getParam('page')}";

        if ($this->getParam(Application_Form_TipoCuenta::E_CANCELAR)) {
            $this->redirect($urlVolver);
        }

        $pCodigo = $this->getParam('id_tipo');
        try {
            $formulario = new Application_Form_TipoCuenta();
            $this->view->formulario = $formulario;
            $formulario->valoresPorDefecto($pCodigo);
            if ($this->getRequest()->isPost()) {
                if ($formulario->isValid($this->getRequest()->getPost())){
                    $post = $this->getRequest()->getPost();
                    $idTipoCuentaModificado = $formulario->guardarModificacion($pCodigo, $post);
                    $this->addMessageSuccessful("Se guardo exitosamente el registro de " .$idTipoCuentaModificado);
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
        $urlVolver = "default/tipocuenta/listar/page/$pPage";
        $pCodigo = $this->getParam('id_tipo');
        $pConfirmar = $this->getParam('confirmar', 'N');
        try {
            $tipocuenta = Application_Model_DbTable_TipoCuenta::findOneById($pCodigo);
            if ($pConfirmar == 'S') {
                $mensaje = "Se eliminó exitosamente el registro '{$tipocuenta->nombre}'";
                $tipocuenta->delete();
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
        $this->view->tipocuenta = $tipocuenta;
        $this->view->page = $pPage;
    }
    
    
    
    


}