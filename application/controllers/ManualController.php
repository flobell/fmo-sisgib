<?php


class ManualController extends Fmo_Controller_Action_Abstract
{
  
    public function listarAction()
    {/*
        
         
        try {
            
            $tManual= new Application_Model_DbTable_Manual();
            $select = $tManual->select()->order('manual');
            //Zend_Debug::dd($select);
            $datos = $this->paginator($select, 10);
        } catch (Exception $ex) {
            $datos = null;
            $this->addMessageException($ex);
        }
        $this->view->manual = $datos;*/
    }
    
    
   
     public function verAction() 
    {

        $idDocumento = $this->getParam(Application_Model_Documento::ID);
       // exit('hola');
        try {
            $documento = Application_Model_DbTable_Documento::findOneByColumn('id',$idDocumento);

            if (!$documento) {
                throw new Exception("No existe el documento de código '{$idDocumento}'");
            }
            if (!is_readable($documento->ruta)) {
                throw new Exception("No es posible leer el documento '{$documento->nombre}'");
            }
            $this->_helper->viewRenderer->setNoRender();
            $this->_helper->layout()->disableLayout();
            $this->getResponse()
                 ->setHeader('Content-Type', "{$documento->tipo_mime}; charset=UTF-8")
                 ->setHeader('Content-disposition', "attachment; filename=\"{$documento->nombre}\"")
                 ->setHeader('Cache-Control', 'public, must-revalidate, max-age=0')
                 ->setHeader('Content-Length', $documento->tamanio)
                 ->setHeader('Pragma', 'public')
                 ->setHeader('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT')
                 ->setHeader('Last-Modified', gmdate('D, d M Y H:i:s \G\M\T'));
            ob_clean();
            readfile($documento->ruta);
        } catch (Exception $ex) {
            $this->addMessageException($ex);
            $this->backUrlSession();            
        }

    }
    
   
    public function editarAction()
    {/*
        $urlVolver = "default/reporte/listar/page/{$this->getParam('page')}";

        if ($this->getParam(Application_Form_Manual::E_CANCELAR)) {
            $this->redirect($urlVolver);
        }

        $pCodigo = $this->getParam('codigo');
        try {
            $form = new Application_Form_Manual;
            $this->view->form = $form;
            $form->valoresPorDefecto($pCodigo);
            if ($this->getRequest()->isPost()) {
                    $post = $this->getRequest()->getPost();
                    $idReporteModificado = $form->guardarModificacion($pCodigo, $post);
                    $this->addMessageSuccessful("Se guardo exitosamente el registro de " .$idReporteModificado );
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
        $urlVolver = "default/manual/listar/page/$pPage";
        $pCodigo = $this->getParam('codigo');
        $pConfirmar = $this->getParam('confirmar', 'N');

        try {
            $manual = Application_Model_DbTable_Manual::findOneById($pCodigo);
            if ($pConfirmar == 'S') {
                $mensaje = "Se eliminó exitosamente el registro '{$Cargar->id_manual}'";
                $manual->delete();
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
        $this->view->manual = $manual;
        $this->view->page = $pPage; */
    }
    
    
    
    


}