<?php

class Application_Form_PatriaTxt extends Fmo_Form_Abstract
{
    const E_TITULO = 'titulo';
    const E_COD = 'cod';
    const E_ESTADO = 'estado';
    const E_FECHA = 'fecha';
    const E_ARCHIVO = 'archivo';
    const E_CLIENTE = 'cliente';
    const E_MAX = 'maximo';

    

    //CONSTANTES PARA LOS BOTONES DE GUARDADO Y CANCELAR
    const E_CARGAR = 'cargarTip';
    const E_CANCELAR = 'cancelarTip';


    /**
     * Inicialización del formulario
     */
    public function __construct()
    { 
        parent::__construct(null);
    }
    
    /*
        $cboImplemento= new Zend_Form_Element_Select(self::E_IMPLEMENTO);
        $cboImplemento->setLabel('Implemento')
                ->addMultiOption(' ', 'Seleccione Implemento')
                ->addMultiOptions(Application_Model_Implemento::getLista());
        $this->addElement($cboImplemento);
    */
    public function init()
    {
        //$this->setAction($this->getView()->url());
       $this->setAction($this->getView()->url())
                ->setEnctype(self::ENCTYPE_MULTIPART)
                ->setLegend('');
        //$tCargar = new Application_Model_DbTable_Cargar();
 
///////////////////// DATOS DE INTERFAZ PROVEEDORES ///////////////////////////////  
        

        
        $eTitulo = new Zend_Form_Element_Hidden(self::E_TITULO);
        $eTitulo->setLabel('titulo de la Interfaz')
        ->setAttrib('size', '30')
        ->setAttrib('maxlength', '100')
        ->setValue('PATRIA-'.time());
        $this->addElement($eTitulo);
           
        $eFecha= new Zend_Form_Element_Hidden(self::E_FECHA); 
        $eFecha->setLabel('Fecha')
            ->setAttrib('size', '5')    
            ->setRequired(true)
            ->setValue(date('d/m/y'))
            ->setAttrib("readonly",""); 
        $this->addElement($eFecha);
        
        $eCliente = new Zend_Form_Element_Hidden(self::E_CLIENTE);
        $eCliente->setLabel('Tipo de Cliente:')
        ->setValue('TRABAJADORES');
        $this->addElement($eCliente);
        
        
 ////////// PARA CARGA POR EXCEL //////////////////////////////////      
        
        $eArchivo = new Zend_Form_Element_File(self::E_ARCHIVO);
        $eArchivo->addValidator('File_Size', false, array('min' => '1kB', 'max' => '8MB'))
                //->addValidator('File_MimeType', false, 'application/vnd.ms-excel,application/vnd.ms-office,application/vnd.oasis.opendocument.spreadsheet')
                ->addValidator('File_Extension', false, 'txt')
                ->setDescription('Seleccione un archivo de TXT (formatos válidos: txt)');
        $this->addElement($eArchivo);
/*
        $eMax = new Zend_Form_Element_Text(self::E_MAX);
        $eMax->setLabel('Monto Maximo')
                ->setAttrib('size', '10 ')
                ->setAttrib('style', 'text-align: right;')
                ->setAttrib('maxlength', '20')
                ->setRequired();        
        $this->addElement($eMax);*/

////////////// DATOS BANCARIOS ///////////////////////////////
        
        $eCodigo = new Zend_Form_Element_Hidden(self::E_COD);
        $eCodigo->setLabel('Código.')
        ->setValue(1);
        $this->addElement($eCodigo);

    ////////////// BOTONES DE GUARDADO Y CANCELADO ///////////////////////////////
        
        $eCargar = new Zend_Form_Element_Submit(self::E_CARGAR);
        $eCargar->setLabel('Cargar')
                 ->setIgnore(true);
        $this->addElement($eCargar);

        // $eCancelar = new Zend_Form_Element_Submit(self::E_CANCELAR);
        // $eCancelar->setLabel('Cancelar')
        //           ->setIgnore(true);
        // $this->addElement($eCancelar);
        
        //$this->setCustomDecorators();
    }

    
    function limpiar($string)
    {

        $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $string
            );

        $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $string
            );

        $string = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $string
            );

        $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $string
            );

        $string = str_replace(
                array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
                array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
                $string
                );

        $string = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C',),
            $string
            );

        $string = str_replace(array(".", ",","-"), "", $string);


        return $string;
    }
    /**
     * Método para inicializar los valores por defecto en el formulario
     *

     * @throws InvalidArgumentException
     */
    public function valoresPorDefecto($idCargar)
    {
        //$cargar= Application_Model_DbTable_Cargar::findOneById($idCargar);
/*
        $this->setDefault(self::E_ID, $proveedores->id)
             ->setDefault(self::E_RAZON, $proveedores->razon)
             ->setDefault(self::E_REPRESENTANTE, $proveedores->representante);
  */           
    }

    /**
     * Método para guardar los datos en la Base de Datos
     */
    public function guardarNuevo($params)
    {
        //Zend_Debug::dd('Monto BANCO: '.Application_Model_DbTable_Bancos::findOneByColumn('codigo', $params[self::E_COD])->monto_maximo);
        try {
            //Transacciones
            Zend_Db_Table::getDefaultAdapter()->beginTransaction();

            $upload = new Zend_File_Transfer();
            $upload->receive(); 
            $files = $upload->getFileInfo(); // Zend_Debug::dd($files);

            foreach ($files as $info) {
                if($info["received"] == TRUE){
                    $tInterfaz = new Application_Model_DbTable_Interfaz();
                    $realizadoPor = Fmo_Model_Seguridad::getUsuarioSesion()->{Fmo_Model_Personal::SIGLADO};
                    $registro = $tInterfaz->createRow();
                    $registro->cliente = $params[self::E_CLIENTE];        
                    $registro->titulo = strtoupper($params[self::E_TITULO]);
                    $registro->codigo_banco = $params[self::E_COD];
                    $registro->fecha = $params[self::E_FECHA];
                    $registro->usuario = $realizadoPor;
                    $registro->estado = 1;
                    $registro->save();
 
                    //leer el archivo
                    $archivo = fopen($info['tmp_name'],"r");
                    $cabecera = true;
                    $cabecera_flag = false; $array=array();
                    while (!feof($archivo)){
                        $linea = fgets($archivo);   
                        
                        if($cabecera && !$cabecera_flag){
                            $cabecera_flag=true;
                            continue;
                        }
                        
                        if(!$linea) break;

                        $array[] = $linea;
                        $tCargaTxt = new Application_Model_DbTable_CargaTxt();
                        $registroDetalle = $tCargaTxt->createRow();
                        $registroDetalle->registro = $linea;                      
                        $registroDetalle->save();
    
                    }
                    fclose($archivo);
                    
                    $filas = Application_Model_CargaTxt::getCarga();
                    // Zend_Debug::dd($filas);
                    if ($filas) {

                        $procesados = array();
                        $numGrupo = 1;
                        $acumGrupo = 0;
                        $montoMax = Application_Model_DbTable_Bancos::findOneByColumn('codigo', $params[self::E_COD])->monto_maximo;
                        $numpagos = 0;
                        $montototal = 0;

                        foreach($filas as $fila){
                            $tDetalle = new Application_Model_DbTable_DetalleInterfaz();
                            $reg = $tDetalle->createRow();
                            $reg->fk_id_interfaz = $registro->id_interfaz;                       
                            $reg->codigo_banco = $params[self::E_COD];
                            $reg->rif = $fila->{Application_Model_CargaTxt::CEDULA};
                            $reg->nombre = $this->limpiar($fila->{Application_Model_CargaTxt::NOMBRE});
                            $reg->numcuenta = $this->limpiar($fila->{Application_Model_CargaTxt::CUENTA});                                
                            $reg->monto = $fila->{Application_Model_CargaTxt::MONTO};
                            $reg->nacionalidad = $fila->{Application_Model_CargaTxt::LETRA};

                            $procesados[$fila->{Application_Model_CargaTxt::CEDULA}] = $fila;

                            if(($acumGrupo+$fila->{Application_Model_CargaTxt::MONTO})>$montoMax){ 
                                $numGrupo+=1; 
                                $acumGrupo = $fila->{Application_Model_CargaTxt::MONTO};
                            } else {
                                $acumGrupo = $acumGrupo+$fila->{Application_Model_CargaTxt::MONTO};
                            }
                            
                            $reg->grupo = $numGrupo;
                            $reg->save();
                            $montototal+=$fila->{Application_Model_CargaTxt::MONTO};
                            $numpagos+=1;

                            $delete = Application_Model_CargaTxt::delete($fila->{Application_Model_CargaTxt::ID});
                        }

                        $registro->monto_total = $montototal;
                        $registro->numpagos = $numpagos;
                        $registro->partes = $numGrupo;
                        $registro->save();
                    
                    } else {
                        throw new Exception("No hay registros cargados");
                    }

                } else {
                    throw new Exception("Fallo la carga del archivo");
                }
            }
            


            // $tInterfaz = new Application_Model_DbTable_Interfaz();

            // $archivo = $this->getElement(self::E_ARCHIVO);   
            // if (!$archivo->receive()){

            //     $realizadoPor = Fmo_Model_Seguridad::getUsuarioSesion()->{Fmo_Model_Personal::SIGLADO};
            //     $registro = $tInterfaz->createRow();
            //     $registro->cliente = $params[self::E_CLIENTE];        
            //     $registro->titulo = strtoupper($params[self::E_TITULO]);
            //     $registro->codigo_banco = $params[self::E_COD];
            //     $registro->fecha = $params[self::E_FECHA];
            //     $registro->usuario = $realizadoPor;
            //     $registro->estado = 1;
            //     $registro->save();
                
            //     $archivo = fopen($archivo->getFileName(),"r");

            //     $cabecera = true;
            //     $cabecera_flag = false;
            //     while (!feof($archivo)){
                    
            //         if($cabecera){
            //             if(!$cabecera_flag){
            //                 $cabecera_flag=true;
            //                 continue;
            //             }
            //         }
                    
            //         $linea = fgets($archivo);    
            //         $tCargaTxt = new Application_Model_DbTable_CargaTxt();
            //         $registroDetalle = $tCargaTxt->createRow();
            //         $registroDetalle->registro = $linea;                      
            //         $registroDetalle->save();

            //     }

            //     fclose($archivo);

            //     $filas = Application_Model_CargaTxt::getCarga();

            //     if (!$filas) {

            //         $archivo->addError('No hay registros cargados');

            //     } else {
 
            //         $procesados = array();
            //         $numGrupo = 1;
            //         $acumGrupo = 0;
            //         $montoMax = Application_Model_DbTable_Bancos::findOneByColumn('codigo', $params[self::E_COD])->monto_maximo;
            //         $numpagos = 0;
            //         $montototal = 0;

            //         $tDetalle = new Application_Model_DbTable_DetalleInterfaz();
            //         foreach($filas as $fila){
            //             $reg = $tDetalle->createRow();
            //             $reg->fk_id_interfaz = $registro->id_interfaz;                       
            //             $reg->codigo_banco = $params[self::E_COD];
            //             $reg->rif = $fila->{Application_Model_CargaTxt::CEDULA};
            //             $reg->nombre = $this->limpiar($fila->{Application_Model_CargaTxt::NOMBRE});
            //             $reg->numcuenta = $fila->{Application_Model_CargaTxt::CUENTA};                                
            //             $reg->monto = $fila->{Application_Model_CargaTxt::MONTO};
            //             $reg->nacionalidad = $fila->{Application_Model_CargaTxt::LETRA};

            //             if(($acumGrupo+$fila->{Application_Model_CargaTxt::CUENTA})>$montoMax){ 
            //                 $numGrupo+=1; 
            //                 $acumGrupo = $fila->{Application_Model_CargaTxt::CUENTA};
            //             } else {
            //                 $acumGrupo = $acumGrupo+$fila->{Application_Model_CargaTxt::CUENTA};
            //             }
                        
            //             $reg->grupo = $numGrupo;
            //             $reg->save();
            //             $montototal+=$fila->{Application_Model_CargaTxt::CUENTA};
            //             $numpagos+=1;
            //         }

            //         $registro->monto_total = $montototal;
            //         $registro->numpagos = $numpagos;
            //         $registro->partes = $numGrupo;
            //         $registro->save();
                
            //     }

            //     $tCargaTxt = new Application_Model_DbTable_CargaTxt();
            //     $tCargaTxt->delete();

            // } else {
            //     $archivo->addError('Fallo la carga del archivo');
            //     throw new Exception("Fallo en la carga del archivo");
            // }

            Zend_Db_Table::getDefaultAdapter()->commit();
        } catch (Exception $e) {
            Zend_Db_Table::getDefaultAdapter()->rollBack();
            throw $e;
        }
    }

    /**
     * Método para guardar los datos en la Base de Datos
     */
    public function guardarModificacion($idCentroAcopio, $params)
    {
       /* $centroacopio = Application_Model_DbTable_CentroAcopio::findOneById($idCentroAcopio);

        $centroacopio->acopio = $params[self::E_ACOPIO];
        $centroacopio->patio= $params[self::E_PATIO];
        $centroacopio->lote= $params[self::E_LOTE];
     
        return $centroacopio->save();*/
    }


}