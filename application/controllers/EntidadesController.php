<?php


class EntidadesController extends Fmo_Controller_Action_Abstract
{
  
    public function listarAction()
    {
        
         
        try {
            
            $tEntidades= new Application_Model_DbTable_Bancos();
            $select = $tEntidades->select()->order('codigo');
            //Zend_Debug::dd($select);
            $datos = $this->paginator($select, 10);
        } catch (Exception $ex) {
            $datos = null;
            $this->addMessageException($ex);
        }
        $this->view->bancos = $datos;
    }
    
    
   
    public function nuevoAction()
    {
        $this->view->jQuery()->enable();
        $this->view->jQuery()->uienable();
        
        $this->view->headScript()
                ->appendFile($this->view->baseUrl('public/js/entidades/nuevo.js'));
        
        $formulario = new Application_Form_EntidadesBancarias();
        $urlVolver = "default/entidades/nuevo";
        $this->view->formulario = $formulario;
        $request = $this->getRequest();
        if ($this->getParam(Application_Form_EntidadesBancarias::E_CANCELAR)) {
            $this->addMessageInformation('Ha cancelado la operación');
            $this->redirect("/");
        }
        try{
            //Se verifica si se ha recibido una petición via POST
            if ($request->isPost()){
                $post = $request->getPost();
                //Zend_Debug::dd($post);
            if (isset($post[Application_Form_EntidadesBancarias::E_GUARDAR])) {
               
                if ($formulario->isValid($this->getRequest()->getPost()) ) {
                    $formulario->guardarNuevo($post);
                    $this->addMessageSuccessful("La Entidad Bancaria ha sido agregada exitosamente " );
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
        $urlVolver = "default/entidades/listar/page/{$this->getParam('page')}";

        if ($this->getParam(Application_Form_EntidadesBancarias::E_CANCELAR)) {
            $this->redirect($urlVolver);
        }

        $pCodigo = $this->getParam('codigo');
        try {
            $formulario = new Application_Form_EntidadesBancarias();
            $this->view->formulario = $formulario;
            $formulario->valoresPorDefecto($pCodigo);
            if ($this->getRequest()->isPost()) {
                 if ($formulario->isValid($this->getRequest()->getPost())){
                    $post = $this->getRequest()->getPost();
                    $idEntidadesModificado = $formulario->guardarModificacion($pCodigo, $post);
                    $this->addMessageSuccessful("Se guardo exitosamente el registro de " .$idEntidadesModificado);
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
        $urlVolver = "default/entidades/listar/page/$pPage";
        $pCodigo = $this->getParam('codigo');
        $pConfirmar = $this->getParam('confirmar', 'N');
        try {
            $entidades = Application_Model_DbTable_Bancos::findOneById($pCodigo);
            if ($pConfirmar == 'S') {
                $mensaje = "Se eliminó exitosamente el registro '{$entidades->nombre_banco}'";
                $entidades->delete();
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
        $this->view->entidades = $entidades;
        $this->view->page = $pPage;
    }
    
    
    
    


}