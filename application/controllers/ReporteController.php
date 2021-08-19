<?php


class ReporteController extends Fmo_Controller_Action_Abstract
{

    public function nuevoAction()
    {
       //Application_Model_Proveedores::getByRifNombre(24889053);
        $formulario = new Application_Form_Reporte();
        $this->view->formulario = $formulario;
        $request = $this->getRequest();
  
        try{
            //Se verifica si se ha recibido una petición via POST
            if ($request->isPost()){
                $post = $request->getPost();
                $formulario->setDefaults($post);         
                //Zend_Debug::dd($post);

            ////LISTAR CUENTAS FMOP DEPENDIENDO DEL CODIGO DEL BANCO SELECCIONADO
            
            if ($post[Application_Form_Reporte::E_COD]) {
                try {
                    $tInterfaz = Application_Model_DbTable_Interfaz::findAllByColumn('codigo_banco', $this->getParam(Application_Form_Reporte::E_COD));
                    //Zend_Debug::dd($select);
                    $datos = $this->paginator($tInterfaz, 10);
                } catch (Exception $ex) {
                    $datos = null;
                    $this->addMessageException($ex);
                }
                $this->view->interfaces = $datos;
            }
            /*
            if ($post[Application_Form_Reporte::E_COD]) {
                try {
                    
                    $tInterfaz = Application_Model_Interfaz::getAllByCodigoEstado($this->getParam(Application_Form_Reporte::E_COD));
                    
                    //Zend_Debug::dd($select);
                    $datos = $this->paginator($tInterfaz, 10);
                } catch (Exception $ex) {
                    $datos = null;
                    $this->addMessageException($ex);
                }
                $this->view->ultima = $datos;
            }
            */
            
            }
        } catch (Exception $ex){
            $this->addMessageError('Error accediendo a la base de datos: ' . $ex->getMessage());
            $this->redirect("/");
        }
     
       
    }
    
   
    public function reporteAction()
    {
        try {
           
            $pCodigo= $this->getParam('id_interfaz');
            $id_interfaz = Application_Model_DbTable_Interfaz::findOneByColumn('id_interfaz', $pCodigo)->id_interfaz;
            $titulo = Application_Model_DbTable_Interfaz::findOneByColumn('id_interfaz', $pCodigo)->titulo;
            $tInterfaz = Application_Model_Interfaz::getAllDetalles($pCodigo);
            $datos = $this->paginator($tInterfaz, 999999);
            $this->view->reporte = $datos;
            $this->view->titulo = $titulo;
            $this->view->id_interfaz = $id_interfaz;

            $html   = $this->view->render('/reporte/reporte.phtml');
            $dompdf = new Fmo_Dompdf();
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->loadHtml($html);
            $dompdf->render();
            $dompdf->stream("Reporte", array('Attachment' => 0));
            $this->_helper->layout->disableLayout();
            $this->_helper->viewRenderer->setNoRender();
        } catch (Exception $e) {
            $this->addMessageError('Error: ' . $e->getMessage());
            $this->redirect('/default/reporte/nuevo/');
        } 
        

    }

}