<?php


class PagosController extends Fmo_Controller_Action_Abstract
{
  
    public function listarAction()
    {/*
        
         
        try {
            
            $tPagos= new Application_Model_DbTable_Pagos();
            $select = $tPagos->select()->order('pagos');
            //Zend_Debug::dd($select);
            $datos = $this->paginator($select, 10);
        } catch (Exception $ex) {
            $datos = null;
            $this->addMessageException($ex);
        }
        $this->view->pagos = $datos;*/
    }
    
    
   
    public function nuevoAction()
    {  /*    //Application_Model_Interfaz::getAllTxt(144);

        //Application_Model_Proveedores::getByRifNombre(24889053);
        $formulario = new Application_Form_Pagos();
        $this->view->formulario = $formulario;
        $request = $this->getRequest();
        
        if ($this->getParam(Application_Form_Pagos::E_CANCELAR)) {
            $this->addMessageInformation('Ha cancelado la operación');
            $this->redirect("/");
        }

        
        
        //Seleccion de Estructuras creadas dependiendo del codigo del banco seleccionado    
        try{
            //Se verifica si se ha recibido una petición via POST
            if ($request->isPost()){
                $post = $request->getPost();
                $formulario->setDefaults($post);         
                //Zend_Debug::dd($post);
                
            if(!empty($post[Application_Form_Pagos::E_COD])){                   
                     $estructura = $post[Application_Form_Pagos::E_COD]; 
                     //Zend_Debug::dd($post);
                     $formulario->cargarEstructura($estructura);
            }else{
                $formulario->setDefaults($this->getAllParams());
            }
            
            ////LISTAR CUENTAS FMO DEPENDIENDO DEL CODIGO DEL BANCO SELECCIONADO
            
            if ($post[Application_Form_Pagos::E_COD]) {
                try {
                    
                    $tCuentasFMO = Application_Model_DbTable_CuentasFMO::findAllByColumn('codigo_banco', $this->getParam(Application_Form_Pagos::E_COD));
                    
                    //Zend_Debug::dd($select);
                    $datos = $this->paginator($tCuentasFMO, 10);
                  
                } catch (Exception $ex) {
                    $datos = null;
                    $this->addMessageException($ex);
                }
                $this->view->cuentasfmo = $datos;
            }
                 
            if ($post[Application_Form_Pagos::E_COD]) {
                try {
                    
                    $tInterfaz = Application_Model_Interfaz::getAllByCodigoEstado($this->getParam(Application_Form_Pagos::E_COD));
                    
                    //Zend_Debug::dd($select);
                    $datos = $this->paginator($tInterfaz, 10);
                } catch (Exception $ex) {
                    $datos = null;
                    $this->addMessageException($ex);
                }
                $this->view->ultima = $datos;
            }
            
            /////////////////////////////////////////////////////////////////////
            
            if (isset($post[Application_Form_Pagos::E_GUARDAR])) {
               
                if ($formulario->isValid($this->getRequest()->getPost()) ) {
                    $formulario->generarNuevo($post);
                    $this->addMessageSuccessful("Ha realiado el pago satisfactoriamente. " );
                   // $this->redirect($urlVolver);
                }

            }
            }
        } catch (Exception $ex){
            $this->addMessageError('Error accediendo a la base de datos: ' . $ex->getMessage());
            $this->redirect("/");
        }*/
       
    }
    
   
    public function editarAction()
    {/*
        $urlVolver = "default/Pagos/listar/page/{$this->getParam('page')}";

        if ($this->getParam(Application_Form_Pagos::E_CANCELAR)) {
            $this->redirect($urlVolver);
        }

        $pCodigo = $this->getParam('codigo');
        try {
            $form = new Application_Form_Pagos;
            $this->view->form = $form;
            $form->valoresPorDefecto($pCodigo);
            if ($this->getRequest()->isPost()) {
                    $post = $this->getRequest()->getPost();
                    $idPagosModificado = $form->guardarModificacion($pCodigo, $post);
                    $this->addMessageSuccessful("Se guardo exitosamente el registro de " .$idPagosModificado);                    $this->redirect($urlVolver);
                
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
        $urlVolver = "default/pagos/listar/page/$pPage";
        $pCodigo = $this->getParam('codigo');
        $pConfirmar = $this->getParam('confirmar', 'N');

        try {
            $pagos = Application_Model_DbTable_Pagos::findOneById($pCodigo);
            if ($pConfirmar == 'S') {
                $mensaje = "Se eliminó exitosamente el registro '{$pagos->id_pagos}'";
                $pagos->delete();
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
        $this->view->pagos = $pagos;
        $this->view->page = $pPage;   */
    }
    
    
 
  
    
   


}