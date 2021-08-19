<?php


class InterfazController extends Fmo_Controller_Action_Abstract
{
  
    public function listarAction()      
    {   

        $formulario = new Application_Form_Interfaz();
        $this->view->formulario = $formulario;
        $request = $this->getRequest();
        
        try{
            if($request->isPost() && $formulario->isValid($request->getPost())) {
                $post = $request->getPost();
                // Zend_Debug::dd($post);
                $this->forward('listado', null, null, $post);
            }     
        }
        catch (Exception $ex){
            $this->addMessageError('Error accediendo a la base de datos: ' . $ex->getMessage());
            $this->redirect("/");
        }
        
        /*
        try{
            //Se verifica si se ha recibido una petición via POST
            if ($request->isPost()){
                $post = $request->getPost();
                $formulario->setDefaults($post);         
                //Zend_Debug::dd($post);
                
           
            
            ////LISTAR CUENTAS FMO DEPENDIENDO DEL CODIGO DEL BANCO SELECCIONADO
            
            if ($post[Application_Form_Interfaz::E_COD]) {
                try {
                    
                    $tInterfaz = Application_Model_DbTable_Interfaz::findAllByColumn('codigo_banco', $post[Application_Form_Interfaz::E_COD]);
                    
                    //Zend_Debug::dd($select);
                    $datos = $this->paginator($tInterfaz, 50);
                } catch (Exception $ex) {
                    $datos = null;
                    $this->addMessageException($ex);
                }
                $this->view->interfaz = $datos;
            }
 
            }
        } catch (Exception $ex){
            $this->addMessageError('Error accediendo a la base de datos: ' . $ex->getMessage());
            $this->redirect("/");
        }
     
*/
    }

    public function listadoAction()
    {
        $this->view->bootstrap()->enable();
        $this->view->jQueryX()->enable();
        $this->view->bootstrap()->jsEnable();
        $this->view->dataTables()->enable();

        $parametros  = $this->getAllParams();
        
        $banco = Application_Model_Bancos::findByCodigo($parametros[Application_Form_Interfaz::E_COD]);
        $tInterfaz = Application_Model_Interfaz::getAllByBanco($parametros[Application_Form_Interfaz::E_COD]);

        $this->view->banco = $banco;
        $this->view->detalle = $tInterfaz;
    }

    public function listadopatriaAction()
    {
        $this->view->bootstrap()->enable();
        $this->view->jQueryX()->enable();
        $this->view->bootstrap()->jsEnable();
        $this->view->dataTables()->enable();

        $banco = Application_Model_Bancos::findByCodigo('1');
        $tInterfaz = Application_Model_Interfaz::getAllByBanco('1');

        $this->view->banco = $banco;
        $this->view->detalle = $tInterfaz;
    }
    
    public function eliminarAction()
    {
        $pPage = $this->getParam('page');
        $urlVolver = "default/interfaz/listar/page/$pPage";
        $pCodigo = $this->getParam('id_interfaz');
        $pConfirmar = $this->getParam('confirmar', 'N');

        try {
            $interfaz = Application_Model_DbTable_Interfaz::findOneById($pCodigo);
            if ($pConfirmar == 'S') {
                $mensaje = "Se eliminó exitosamente el registro '{$interfaz->titulo}'";
                $interfaz->delete();
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
        $this->view->interfaz = $interfaz;
        $this->view->page = $pPage;
    }
    
    public function detalleAction()
    {
    
        $pCodigo= $this->getParam('id_interfaz');
        try {
            $interfaz = Application_Model_DbTable_Interfaz::findOneByColumn('id_interfaz', $pCodigo);
            //Zend_Debug::dd($interfaz);
            if($interfaz->estado != 1) { $this->addMessageWarning('Esta interfaz no esta generada!'); }
            
            $pCuentas = Application_Model_Interfaz::getAllDetalles($pCodigo);
            //Zend_Debug::dd($cliente);
           
            $datos = $this->paginator($pCuentas, 20);
            
            $banco = Application_Model_Bancos::findByCodigo($interfaz->codigo_banco);
        } catch (Exception $ex) {
            $datos = null;
            $this->addMessageException($ex);
        }

        $this->view->banco = $banco;
        $this->view->interfaz = $interfaz;
        $this->view->detalle_interfaz = $datos;
        $this->view->titulo = $interfaz->titulo;
        $this->view->limite = intval(round($interfaz->numpagos/$interfaz->partes,0));
        
        
    }
    
     public function pagosAction()
    {       //Application_Model_Interfaz::getAllTxt(144);

        //Application_Model_Proveedores::getByRifNombre(24889053);
        $urlVolver = "default/interfaz/pagos";
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
                        $CuentaPago = rawurldecode($this->getParam(Application_Form_Pagos::E_SELECCION));
                        
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
                   
                    //Zend_Debug::dd($this->getParam(Application_Form_Pagos::E_SELECCION)); 
                    //$pagos = $post;

                    //if ($formulario->isValid($this->getRequest()->getPost())){
                        //Zend_Debug::dd($post);
                        $pCodigo = $this->getParam(Application_Form_Pagos::E_GENERAR);
                        //$pNumero = Application_Model_Interfaz::getNumPagos($this->getParam(Application_Form_Pagos::E_GENERAR));
                        //$this->view->pagofmo=$CuentaPago;
                        $formulario->generarNuevo($post,$pCodigo);
                        $this->addMessageSuccessful("Ha realizado el pago satisfactoriamente. " );
                        $this->redirect($urlVolver);
                    //} 

                }
            }
        } catch (Exception $ex){
            $this->addMessageError('Error accediendo a la base de datos: ' . $ex->getMessage());
            $this->redirect($urlVolver);
        }
       
    }
    
    
    public function txtAction()
    {
        $pInterfaz = rawurldecode($this->getParam('id_interfaz')); //ID DE LA INTERFAZ
        $pCodigo = rawurldecode($this->getParam('codigo_banco')); //CODIGO DEL BANCO
        $grupo = rawurldecode($this->getParam('grupo'));
        
        $this->_helper->layout()->disableLayout();
        try{
            // $this->_helper->viewRenderer->setNoRender();
            $interfaz = Application_Model_DbTable_Interfaz::findOneById($pInterfaz);
            $formato = Application_Model_Estructuras::getFormatoTxt($pCodigo); //FORMATO DEL BANCO
            $detalle = Application_Model_Interfaz::getDatosTxt($pInterfaz,$grupo); 
            $banco = Application_Model_Bancos::findByCodigo($pCodigo)->bcv;
            //$cabecera = Application_Model_Interfaz::getDatosTxt($pInterfaz);
            $DatosTXT = $this->paginator($detalle, 999999999999);
               //Zend_Debug::dd($DatosTXT->$titulo);
            $DatosForm = $this->paginator($formato, 999999999999);

            
        }catch (Exception $ex){
            $DatosTXT = null;
            //$DatosForm = null;
            $this->addMessageException($ex);
        }
        //Zend_Debug::dd($DatosTXT->toJson());
        $this->view->pCodigo = $banco;
        $this->view->pInterfaz = $pInterfaz;
        $this->view->txt = $DatosTXT ;
        //$this->view->cabecera = $cabecera;
        $this->view->formato = $DatosForm;
        $this->view->titulo = $interfaz->titulo;
        
    }
    


}
    
