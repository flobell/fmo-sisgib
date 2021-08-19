<?php


class RelacionController extends Fmo_Controller_Action_Abstract
{
  
    public function listarAction()
    {
        
         
        try {
            
            $tRelacion= new Application_Model_DbTable_Relacion();
            $select = $tRelacion->select()->order('id_relacion');
            //Zend_Debug::dd($select);
            $datos = $this->paginator($select, 10);
        } catch (Exception $ex) {
            $datos = null;
            $this->addMessageException($ex);
        }
        $this->view->relacion = $datos;
    }
    
    
   
    public function nuevoAction()
    {
        $this->view->jQuery()->enable();
        $this->view->jQuery()->uienable();
        
        $this->view->headScript()
                ->appendFile($this->view->baseUrl('public/js/relacion/nuevo.js'));
        
        $formulario = new Application_Form_Relacion();
        $urlVolver = "default/relacion/nuevo";
        $this->view->formulario = $formulario;
        $request = $this->getRequest();
        if ($this->getParam(Application_Form_Relacion::E_CANCELAR)) {
            $this->addMessageInformation('Ha cancelado la operación');
            $this->redirect("/");
        }
        try{
            //Se verifica si se ha recibido una petición via POST
            if ($request->isPost()){
                $post = $request->getPost();
                //Zend_Debug::dd($post);
            if (isset($post[Application_Form_Relacion::E_GUARDAR])) {
               
                if ($formulario->isValid($this->getRequest()->getPost()) ) {
                    $formulario->guardarNuevo($post);
                    $this->addMessageSuccessful("El Campo de Relación ha sido agregado exitosamente " );
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
        $urlVolver = "default/relacion/listar/page/{$this->getParam('page')}";

        if ($this->getParam(Application_Form_Relacion::E_CANCELAR)) {
            $this->redirect($urlVolver);
        }

        $pCodigo = $this->getParam('id_relacion');
        try {
            $form = new Application_Form_Relacion();
            $this->view->form = $form;
            $form->valoresPorDefecto($pCodigo);
            if ($this->getRequest()->isPost()) {
                    $post = $this->getRequest()->getPost();
                    $idRelacionModificado = $form->guardarModificacion($pCodigo, $post);
                    $this->addMessageSuccessful("Se guardo exitosamente el registro de " .$idRelacionModificado);
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
        $urlVolver = "default/relacion/listar/page/$pPage";
        $pCodigo = $this->getParam('id_relacion');
        $pConfirmar = $this->getParam('confirmar', 'N');
        try {
            $relacion = Application_Model_DbTable_Relacion::findOneById($pCodigo);
            if ($pConfirmar == 'S') {
                $mensaje = "Se eliminó exitosamente el registro '{$relacion->nombre}'";
                $relacion->delete();
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
        $this->view->relacion = $relacion;
        $this->view->page = $pPage;
    }
    
    
    
    


}
