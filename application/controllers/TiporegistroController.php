<?php


class TiporegistroController extends Fmo_Controller_Action_Abstract
{
  
    public function listarAction()
    {
        
         
        try {
            
            $tTipoRegistro= new Application_Model_DbTable_TipoRegistro();
            $select = $tTipoRegistro->select()->order('id_registro');
            //Zend_Debug::dd($select);
            $datos = $this->paginator($select, 10);
        } catch (Exception $ex) {
            $datos = null;
            $this->addMessageException($ex);
        }
        $this->view->tiporegistro = $datos;
    }
    
    
   
    public function nuevoAction()
    {
        $this->view->jQuery()->enable();
        $this->view->jQuery()->uienable();
        
        $this->view->headScript()
                ->appendFile($this->view->baseUrl('public/js/tiporegistro/nuevo.js'));
        
        $formulario = new Application_Form_TipoRegistro();
        $urlVolver = "default/tiporegistro/nuevo";
        $this->view->formulario = $formulario;
        $request = $this->getRequest();
        if ($this->getParam(Application_Form_TipoRegistro::E_CANCELAR)) {
            $this->addMessageInformation('Ha cancelado la operación');
            $this->redirect("/");
        }
        try{
            //Se verifica si se ha recibido una petición via POST
            if ($request->isPost()){
                $post = $request->getPost();
                //Zend_Debug::dd($post);
            if (isset($post[Application_Form_TipoRegistro::E_GUARDAR])) {
               
                if ($formulario->isValid($this->getRequest()->getPost()) ) {
                    $formulario->guardarNuevo($post);
                    $this->addMessageSuccessful("El Tipo de Registro ha sido agregado exitosamente " );
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
        $urlVolver = "default/tiporegistro/listar/page/{$this->getParam('page')}";

        if ($this->getParam(Application_Form_TipoRegistro::E_CANCELAR)) {
            $this->redirect($urlVolver);
        }

        $pCodigo = $this->getParam('id_registro');
        try {
            $formulario = new Application_Form_TipoRegistro();
            $this->view->formulario = $formulario;
            $formulario->valoresPorDefecto($pCodigo);
            if ($this->getRequest()->isPost()) {
                if ($formulario->isValid($this->getRequest()->getPost())){
                    $post = $this->getRequest()->getPost();
                    $idRegistroModificado = $formulario->guardarModificacion($pCodigo, $post);
                    $this->addMessageSuccessful("Se guardo exitosamente el registro de " .$idRegistroModificado);
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
        $urlVolver = "default/tiporegistro/listar/page/$pPage";
        $pCodigo = $this->getParam('id_registro');
        $pConfirmar = $this->getParam('confirmar', 'N');
        try {
            $tiporegistro = Application_Model_DbTable_TipoRegistro::findOneById($pCodigo);
            if ($pConfirmar == 'S') {
                $mensaje = "Se eliminó exitosamente el registro '{$tiporegistro->nombre}'";
                $tiporegistro->delete();
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
        $this->view->tiporegistro = $tiporegistro;
        $this->view->page = $pPage;
    }
    
    
    
    


}
