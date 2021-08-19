<?php
/**
 * Clase modelo que permite obtener la información de los Datos Básicos de los
 * trabajadores de la empresa.
 */
class Application_Model_Trabajadores
{
    const SCHEMA = 'sch_rpsdatos';
    const FILTRO_UNIDAD_ORGANIZATIVA = 'UnidadOrganizativa';

    const FILTRO_CUMPLEANIOS = 'cumpleaños';

    const VALOR_ACTIVIDAD_SUSPENDIDO = 0;
    const VALOR_ACTIVIDAD_ACTIVO = 1;
    const VALOR_ACTIVIDAD_VACACIONES = 2;
    const VALOR_ACTIVIDAD_PERMISO_REMUNERADO = 3;
    const VALOR_ACTIVIDAD_REPOSO = 4;
    const VALOR_ACTIVIDAD_SERVICIO_MILITAR = 5;
    const VALOR_ACTIVIDAD_INASISTENTE = 6;
    const VALOR_ACTIVIDAD_PREVACACION = 8;
    const VALOR_ACTIVIDAD_RETIRADO = 9;

    const VALOR_NOMINA_GERENCIAL = '1';
    const VALOR_NOMINA_EJECUTIVA = '2';
    const VALOR_NOMINA_MENSUAL_NO_AMPARADA = '3';
    const VALOR_NOMINA_MENSUAL_MENOR_CONTRATO_INDIVIDUAL = '4';
    const VALOR_NOMINA_MENSUAL_AMPARADA = '5';
    const VALOR_NOMINA_DIARIA = '6';
    const VALOR_NOMINA_APRENDICES_INCE = '7';
    const VALOR_NOMINA_PASANTES = '8';
    const VALOR_NOMINA_PENSIONADOS = '11';
    const VALOR_NOMINA_JUBILADOS = '10';
    const VALOR_NOMINA_PENSIONADOS_INVALIDEZ = '12';
    const VALOR_NOMINA_DIETA_DIRECTORES = '20';
    const VALOR_NOMINA_PROFESORES = '9';
    const VALOR_NOMINA_MENSUAL_MENOR_CONTRATO_COLECTIVO = '15';

    public static $nominasTrabajadoresActivos = array(
        self::VALOR_NOMINA_GERENCIAL,
        self::VALOR_NOMINA_DIARIA,
        self::VALOR_NOMINA_DIETA_DIRECTORES,
        self::VALOR_NOMINA_EJECUTIVA,
        self::VALOR_NOMINA_MENSUAL_AMPARADA,
        self::VALOR_NOMINA_MENSUAL_MENOR_CONTRATO_COLECTIVO,
        self::VALOR_NOMINA_MENSUAL_MENOR_CONTRATO_INDIVIDUAL,
        self::VALOR_NOMINA_MENSUAL_NO_AMPARADA,
        self::VALOR_NOMINA_PROFESORES
    );

    const VALOR_ESTADO_CIVIL_CASADO = 'C';
    const VALOR_ESTADO_CIVIL_DIVORCIADO = 'D';
    const VALOR_ESTADO_CIVIL_CONCUBINO = 'K';
    const VALOR_ESTADO_CIVIL_SOLTERO = 'S';
    const VALOR_ESTADO_CIVIL_VIUDO = 'V';

    const VALOR_SEXO_FEMENINO = 'F';
    const VALOR_SEXO_MASCULINO = 'M';

    const FILTRO_BUSCAR = 'buscar';
    
//////////////////////// LAS QUE ME INTERESAN ///////////////////////////////
    
    const NACIONALIDAD = 'nacionalidad';
    const FICHA = 'ficha';
    const CEDULA = 'cedula';
    const TIPOCUENTA = 'tcuenta';
    const BANCO = 'banco';
    const NUMCUENTA = 'numcuenta';
    const NOMBRE = 'nombre';
    const APELLIDO = 'apellido';
    const TELEFONO_CELULAR = 'telefono_celular';
    const CORREO_ELECTRONICO = 'correo_electronico';
    
////////////////////////////////////////////////////////////////////////////    
    const PARENTESCO = 'parentesco';
    const ID_PARENTESCO = 'id_parentesco';
    
    const SIGLADO = 'siglado';
    
    const NOMBRE_APELLIDO = 'nombre_apellido';


    const ID_SEXO = 'id_sexo';
    const SEXO = 'sexo';
    const FECHA_NACIMIENTO = 'fecha_nacimiento';
    const FECHA_INGRESO = 'fecha_ingreso';
    const FECHA_EGRESO = 'fecha_egreso';
    const FECHA_EFECTIVA = 'fecha_efectiva';
    const ID_CARGO = 'id_cargo';
    const CARGO = 'cargo';
    const ID_CENTRO_COSTO = 'id_centro_costo';
    const CENTRO_COSTO = 'centro_costo';
    const ID_NOMINA = 'id_nomina';
    const NOMINA = 'nomina';
    const GERENCIA = 'gerencia';
    const EDAD = 'edad';
    const ANTIGUEDAD = 'antiguedad';
    const ID_ACTIVIDAD = 'id_actividad';
    const ACTIVIDAD = 'actividad';
    const CARGO_HISTORIA = 'cargo_historia';
    const ID_NIVEL_EDUCATIVO = 'id_nivel_educativo';
    const NIVEL_EDUCATIVO = 'nivel_educativo';
    const FAMILIARES = 'familiares';
    const DIRECCION = 'direccion';
    const TELEFONO_HABITACION = 'telefono_habitacion';
    const TELEFONO_OFICINA = 'telefono_oficina';
    
    const ID_PROFESION = 'id_profesion';
    const PROFESION = 'profesion';
    const ID_ESPECIALIDAD = 'id_especialidad';
    const ESPECIALIDAD = 'especialidad';
    const ID_ESTADO_CIVIL = 'id_estado_civil';
    const ESTADO_CIVIL = 'estado_civil';
    const MOTIVO = 'motivo';
    const ID_MOTIVO = 'id_motivo';
    const LUGAR_NACIMIENTO = 'lugar_nacimiento';
    const ORGANIGRAMA = 'organigrama';
    const LOCALIDAD = 'localidad';

    const SIQUEL_SNV610 = 'SNV610';
    const SIQUEL_RHV610 = 'RHV610';
    const SIQUEL_LIST_SEXO = '95';
    const SIQUEL_LIST_ESTADOCIVIL = '4';
    const SIQUEL_LIST_ACTIVIDAD = '5';
    const SIQUEL_LIST_PARENTESCO = '7';
    const SIQUEL_LIST_CARGO_MOTIVO = '41';

    private $_select = null;
    private $_selFam = null;
    private $_selCar = null;
    private $_conn = null;
    private $_filters = array();
    private $_orderBy = self::CEDULA;
    private $_limit = null;
    private $_selOrg = null;

    private static $_strSearch = array('  ', ' ', 'á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú');
    private static $_strReplace = array(' ', '%', 'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U');
    /**
     * Constructor de la clase
     */
    public function __construct()
    {
        // objeto de tabla generica
        $table = new Zend_Db_Table();

        $this->_conn = $table->getAdapter();
        $this->_conn->setFetchMode(Zend_Db::FETCH_OBJ);

        // consulta general
        $this->_select = $this->_conn
                              ->select()
                              ->distinct()
                              ->from(array('a' => 'sn_tdatbas'),
                                     array(
                                         self::NACIONALIDAD        => 'a.datb_nacion',
                                         self::BANCO               => 'a.datb_enti',
                                         self::NUMCUENTA           => 'z.forp_ccc',
                                         self::CEDULA              => 'a.datb_cedula',
                                         self::FICHA               => 'a.datb_nrotrab',
                                         self::NOMBRE              => 'a.datb_nombre',
                                         self::APELLIDO            => 'a.datb_apellid',
                                         self::TIPOCUENTA          => 'a.datb_tpoctas',
                                         self::TELEFONO_CELULAR    => 'i.eleg_nrotelc',
                                         self::CORREO_ELECTRONICO  => 'i.eleg_email',
                                         
                                         self::ID_SEXO             => 'a.datb_sexo',
                                         self::SEXO                => 'f.list_descri',
                                         self::FECHA_NACIMIENTO    => 'a.datb_fecnac',
                                         self::FECHA_INGRESO       => 'a.datb_fecing',
                                         self::FECHA_EGRESO        => 'a.datb_fecegr',
                                         self::ID_CARGO            => 'a.datb_carg',
                                         self::CARGO               => 'b.carg_descri',
                                         self::ID_CENTRO_COSTO     => 'a.datb_ceco',
                                         self::CENTRO_COSTO        => 'c.ceco_descri',
                                         self::ID_NOMINA           => 'a.datb_tpno',
                                         self::NOMINA              => 'd.tpno_descri',
                                         self::EDAD                => new Zend_Db_Expr('age(a.datb_fecnac)'),
                                         self::ANTIGUEDAD          => new Zend_Db_Expr('age(a.datb_fecing)'),
                                         self::ID_ACTIVIDAD        => 'a.datb_activi',
                                         self::ACTIVIDAD           => 'g.list_descri',
                                         self::CARGO_HISTORIA      => new Zend_Db_Expr('NULL'),
                                         self::ID_NIVEL_EDUCATIVO  => 'a.datb_nive',
                                         self::NIVEL_EDUCATIVO     => 'h.nive_descri',
                                         self::FAMILIARES          => new Zend_Db_Expr('NULL'),
                                         self::SIGLADO             => 'e.samaccount',
                                         
                                         self::DIRECCION           => new Zend_Db_Expr("concat_ws(' ', i.eleg_direc1, i.eleg_direc2, i.eleg_direc3)"),
                                         self::TELEFONO_HABITACION => 'i.eleg_nrotelh',
                                         self::TELEFONO_OFICINA    => 'i.eleg_nrotelo',
                                         
                                         self::ID_PROFESION        => 'i.eleg_prof',
                                         self::PROFESION           => 'j.prof_descri',
                                         self::ID_ESPECIALIDAD     => 'i.eleg_espe',
                                         self::ESPECIALIDAD        => 'k.espe_descri',
                                         self::ID_ESTADO_CIVIL     => 'a.datb_edociv',
                                         self::ESTADO_CIVIL        => 'l.list_descri',
                                         self::LUGAR_NACIMIENTO    => 'a.datb_lugnac',
                                         self::LOCALIDAD           => 'a.datb_lugp'
                                     ),
                                     self::SCHEMA)
                              ->joinLeft(array('b' => 'sn_tcarg'), 'a.datb_carg = b.carg_carg', null, self::SCHEMA)
                              ->joinLeft(array('c' => 'sn_tcecos'), 'a.datb_ceco = c.ceco_ceco AND a.datb_gcia = c.ceco_cias', null, self::SCHEMA)
                              ->joinLeft(array('d' => 'sn_ttpno'), 'a.datb_tpno = d.tpno_tpno', null, self::SCHEMA)
                              ->joinLeft(array('e' => 'activedirectory'), 'a.datb_nrotrab = e.nrotrab AND NOT e.nrotrab ISNULL', null, self::SCHEMA)
                              
                              ->joinLeft(array('z' => 'sn_tforpago'), 'a.datb_cedula = z.forp_cedula AND NOT z.forp_cedula ISNULL', null, self::SCHEMA)
                
                              ->joinLeft(array('f' => 'sq_tlist'), "a.datb_sexo = f.list_codigo AND f.list_apli = '" . self::SIQUEL_SNV610 . "' AND f.list_list = '" . self::SIQUEL_LIST_SEXO . "'", null, self::SCHEMA)
                              ->joinLeft(array('g' => 'sq_tlist'), "a.datb_activi = CAST(g.list_codigo AS NUMERIC) AND g.list_apli = '" . self::SIQUEL_SNV610 . "' AND g.list_list = '" . self::SIQUEL_LIST_ACTIVIDAD . "'", null, self::SCHEMA)
                              ->joinLeft(array('h' => 'rh_tnive'), 'a.datb_nive = h.nive_nive', null, self::SCHEMA)
                              ->joinLeft(array('i' => 'rh_teleg'), 'a.datb_cedula = i.eleg_cedula', null, self::SCHEMA)
                              ->joinLeft(array('j' => 'rh_tprof'), 'i.eleg_prof = j.prof_prof', null, self::SCHEMA)
                              ->joinLeft(array('k' => 'rh_tespe'), 'i.eleg_espe = k.espe_espe', null, self::SCHEMA)
                              ->joinLeft(array('l' => 'sq_tlist'), "a.datb_edociv = l.list_codigo AND l.list_apli = '" . self::SIQUEL_SNV610 . "' AND l.list_list = '" . self::SIQUEL_LIST_ESTADOCIVIL . "'", null, self::SCHEMA);
        // información de familiares
        $this->_selFam = $this->_conn
                              ->select()
                              ->from(array('a' => 'rh_tfami'),
                                     array(
                                         self::CEDULA           => 'a.fami_cedulaf',
                                         self::NOMBRE_APELLIDO  => 'a.fami_nomapel',
                                         self::ID_PARENTESCO    => 'a.fami_parent',
                                         self::PARENTESCO       => 'b.list_descri',
                                         self::FECHA_NACIMIENTO => 'a.fami_fecnac',
                                         self::EDAD             => new Zend_Db_Expr('age(a.fami_fecnac)'),
                                         self::ID_SEXO          => 'a.fami_sexo',
                                         self::SEXO             => 'c.list_descri'
                                     ),
                                     self::SCHEMA)
                              ->joinLeft(array('b' => 'sq_tlist'), "a.fami_parent = b.list_codigo AND b.list_apli = '" . self::SIQUEL_SNV610 . "' AND b.list_list = '" . self::SIQUEL_LIST_PARENTESCO . "'", null, self::SCHEMA)
                              ->joinLeft(array('c' => 'sq_tlist'), "a.fami_sexo = c.list_codigo AND c.list_apli = '" . self::SIQUEL_SNV610 . "' AND c.list_list = '" . self::SIQUEL_LIST_SEXO . "'", null, self::SCHEMA)
                              ->where('a.fami_cedula = ?')
                              ->where('a.fami_estatus = ?', 'A')
                              ->order(self::NOMBRE_APELLIDO);

        // información de los cargos
        $this->_selCar = $this->_conn
                              ->select()
                              ->from(array('a' => 'sn_tccarg'),
                                     array(
                                         self::ID_CARGO       => 'a.ccar_carg',
                                         self::CARGO          => 'b.carg_descri',
                                         self::FECHA_EFECTIVA => 'a.ccar_fecefec',
                                         self::ID_MOTIVO      => 'a.ccar_motivo',
                                         self::MOTIVO         => 'c.list_descri'
                                     ),
                                     self::SCHEMA)
                              ->joinLeft(array('b' => 'sn_tcarg'), 'a.ccar_carg = b.carg_carg', null, self::SCHEMA)
                              ->joinLeft(array('c' => 'sq_tlist'), "TRIM(a.ccar_motivo) = c.list_codigo AND c.list_apli = '" . self::SIQUEL_RHV610 . "' AND c.list_list = '" . self::SIQUEL_LIST_CARGO_MOTIVO . "'", null, self::SCHEMA)
                              ->where('a.ccar_cedula = ?')
                              ->order(self::FECHA_EFECTIVA . ' ' . Zend_Db_Table_Select::SQL_DESC);

        // información del organigrama de trabajador
        $this->_selOrg = $this->_conn
                              ->select()
                              ->union(array(
                                          $this->_conn
                                               ->select()
                                               ->from(array('a' => 'sn_tcecos'), array(self::ID_CENTRO_COSTO => 'a.ceco_ceco', self::CENTRO_COSTO => 'a.ceco_descri', 'orden' => new Zend_Db_Expr(0)), self::SCHEMA)
                                               ->join(array('b' => 'sn_tdatbas'), 'a.ceco_ceco = b.datb_ceco', null, self::SCHEMA)
                                               ->where('b.datb_cedula = ?'),
                                          $this->_conn
                                               ->select()
                                               ->from(array('a' => 'sn_tcecos'), array(self::ID_CENTRO_COSTO => 'a.ceco_ceco', self::CENTRO_COSTO => 'a.ceco_descri', 'orden' => new Zend_Db_Expr(1)), self::SCHEMA)
                                               ->join(array('b' => 'sn_tdatbas'), 'a.ceco_ceco = SUBSTRING(b.datb_ceco FOR 5)', null, self::SCHEMA)
                                               ->where('b.datb_cedula = ?'),
                                          $this->_conn
                                               ->select()
                                               ->from(array('a' => 'sn_tcecos'), array(self::ID_CENTRO_COSTO => 'a.ceco_ceco', self::CENTRO_COSTO => 'a.ceco_descri', 'orden' => new Zend_Db_Expr(2)), self::SCHEMA)
                                               ->join(array('b' => 'sn_tdatbas'), 'a.ceco_ceco = SUBSTRING(b.datb_ceco FOR 2)', null, self::SCHEMA)
                                               ->where('b.datb_cedula = ?')
                                      ),
                                      Zend_Db_Table_Select::SQL_UNION)
                              ->order('orden');

                             
    }

    /**
     * Método estático para buscar un trabajador
     *
     * @param string $by Campo de busqueda.
     * @param mixed $value Valor de busqueda.
     * @return array|null
     */
    private static function _find($by, $value)
    {
        if (Zend_Validate::is($value, 'NotEmpty')) {
            $modPers = new Fmo_Model_Personal();
            switch ($by) {
                case self::FICHA:
                    $modPers->addFilterByFicha($value);
                    break;

                case self::SIGLADO:
                    $modPers->addFilterBySiglado($value);
                    break;

                case self::CORREO_ELECTRONICO:
                    $modPers->addFilterByCorreoElectronico($value);
                    break;

                default: // self::CEDULA
                    $modPers->addFilterByCedula($value);
                    break;
            }
            return $modPers->findOne();
        }
        return null;
    }

    /**
     * Método que agrega valores de busqueda por el tipo de actividad.
     *
     * @param mixed $campo Indica el campo o filtro de busqueda de la tabla.
     * @param string $igual Indica el operador a utilizar igual 'IN' o diferente 'NOT IN'.
     * @param mixed $valor Valor de la consulta.
     * @return Fmo_Model_Personal
     */
    private function _addFilterBy($campo, $igual, $valor)
    {
        $cond = '';
        $type = null;
        $op = $igual ? 'IN (?)' : 'NOT IN (?)';
        if (is_null($valor)) {
            $op = $igual ? 'IS NULL' : 'IS NOT NULL';
        }
        switch ($campo) {
            case self::FICHA:
                $cond = "a.datb_nrotrab $op";
                break;
            case self::ID_NOMINA:
                $cond = "a.datb_tpno $op";
                break;
            case self::ID_SEXO:
                $cond = "a.datb_sexo $op";
                break;
            case self::SIGLADO:
                $cond = "e.samaccount $op";
                break;
            case self::CORREO_ELECTRONICO:
                $cond = "i.eleg_email $op";
                break;
            case self::CEDULA:
                $cond = "a.datb_cedula $op";
                $type = Zend_Db::INT_TYPE;
                break;
            case self::ID_ESTADO_CIVIL:
                $cond = "a.datb_edociv $op";
                break;
            case self::ID_ACTIVIDAD:
                $cond = "a.datb_activi $op";
                $type = Zend_Db::INT_TYPE;
                break;
            case self::ID_PROFESION:
                $cond = "i.eleg_prof $op";
                $type = Zend_Db::INT_TYPE;
                break;
            case self::ID_NIVEL_EDUCATIVO:
                $cond = "a.datb_nive $op";
                break;
            case self::FILTRO_CUMPLEANIOS:
                $cond = "TO_CHAR(a.datb_fecnac, 'MMDD') $op";
                break;
            case self::FILTRO_UNIDAD_ORGANIZATIVA:
                $op = $igual ? 'LIKE' : 'NOT LIKE';
                $cond = $this->_conn->quoteInto("a.datb_ceco $op ANY (ARRAY[?])", $valor);
                $valor = null;
                break;
            default: // self::FILTRO_BUSCAR
                if (!empty($valor)) {
                    if (!is_array($valor)) {
                        $valor = (array) $valor;
                    }
                    $cond = '(a.datb_nrotrab IN (?) OR lower(e.samaccount) IN (?) OR i.eleg_email IN (?) ';
                    $lowerArrayValor = Fmo_Util::arrayMbConvertCase($valor);
                    foreach ($lowerArrayValor as $v) {
                        $where = 'OR (b.carg_descri ILIKE ?) OR (c.ceco_descri ILIKE ?) '
                               . 'OR (d.tpno_descri ILIKE ?) OR (a.datb_nombre ILIKE ?) '
                               . "OR (a.datb_apellid ILIKE ?) OR (a.datb_nombre || ' ' || a.datb_apellid ILIKE ?) ";
                        $cond .= $this->_conn->quoteInto($where, '%' . str_replace(self::$_strSearch, self::$_strReplace, trim($v)) . '%');
                    }
                    $cond .= 'OR (CAST(a.datb_cedula AS VARCHAR) IN (?)))';
                }
                break;
        }
        if (!empty($cond)) {
            if (!isset($this->_filters[$cond]['type'])) {
                $this->_filters[$cond]['type'] = $type;
            }
            $this->_filters[$cond]['value'][] = $valor === NULL || is_array($valor) ? $valor : (string) $valor;
        }
        return $this;
    }

    /**
     * Método para procesar la consulta y devolver la información completa de los trabajadores.
     *
     * @return array|object
     */
    private function _execute()
    {
        $datos = $this->_conn
                      ->fetchAll($this->getSelect());

        if (!empty($datos)) {
            /*
             * Completa la información del registro, añadiendo los familiares y cargos del trabajador. Además, modifica
             * el texto de Antigüedad y Edad para poner el texto en español, los campos de fecha de nacimiento, ingreso
             * e ingreso cómo objeto de Zend_Date.
             */
            foreach ($datos as $fila) {
                $cedula = (integer) $fila->{self::CEDULA};

                $fila->{self::ANTIGUEDAD} = Fmo_Util::translateAgeEnglighToSpanish($fila->{self::ANTIGUEDAD});
                $fila->{self::EDAD} = Fmo_Util::translateAgeEnglighToSpanish($fila->{self::EDAD});
                $fila->{self::FECHA_NACIMIENTO} = Fmo_Util::stringToZendDate($fila->{self::FECHA_NACIMIENTO});
                $fila->{self::FECHA_INGRESO} = Fmo_Util::stringToZendDate($fila->{self::FECHA_INGRESO});

                // la fecha de egreso puede ser nula
                if (Zend_Validate::is($fila->{self::FECHA_EGRESO}, 'NotEmpty')) {
                    $fila->{self::FECHA_EGRESO} = Fmo_Util::stringToZendDate($fila->{self::FECHA_EGRESO});
                }

                // histórico de cargos
                $fila->{self::CARGO_HISTORIA} = $this->_conn
                                                     ->fetchAll($this->_selCar, array($cedula));

                // familiares
                $fila->{self::FAMILIARES} = $this->_conn
                                                 ->fetchAll($this->_selFam, array($cedula));
                // organigrama
                $fila->{self::ORGANIGRAMA} = $this->_conn
                                                  ->fetchAll($this->_selOrg, array($cedula, $cedula, $cedula));

                foreach ($fila->{self::CARGO_HISTORIA} as $carg) {
                    $carg->{self::FECHA_EFECTIVA} = Fmo_Util::stringToZendDate($carg->{self::FECHA_EFECTIVA});
                }

                foreach ($fila->{self::FAMILIARES} as $fam) {
                    $fam->{self::FECHA_NACIMIENTO} = Fmo_Util::stringToZendDate($fam->{self::FECHA_NACIMIENTO});
                    $fam->{self::EDAD} = Fmo_Util::translateAgeEnglighToSpanish($fam->{self::EDAD});
                }
            }
        }

        return $datos;
    }
    
    /**
     * Devuelve el ordenamiento
     *
     * @return mixed
     */
    public function getOrderBy()
    {
        return $this->_orderBy;
    }

    /**
     * Método para el ordenamiento de los registros devueltos
     *
     * @param $orderBy Ordenado por. Pueder ser un texto o arreglo.
     * @return Fmo_Model_Personal
     */
    public function setOrderBy($orderBy)
    {
        $this->_orderBy = $orderBy;

        return $this;
    }

    /**
     * Elimina todas las busquedas.
     *
     * @return Fmo_Model_Personal
     */
    public function clearFilters()
    {
        $this->_filters = array();

        return $this;
    }

    /**
     * Agrega los tipo de ACTIVIDAD igual a ACTIVO.
     *
     * @return Fmo_Model_Personal
     */
    public function addFilterByActividadActivo($igual = true)
    {
        return $this->_addFilterBy(self::ID_ACTIVIDAD, $igual, self::VALOR_ACTIVIDAD_ACTIVO);
    }

    /**
     * Agrega los tipo de ACTIVIDAD igual a INASISTENTE
     *
     * @return Fmo_Model_Personal
     */
    public function addFilterByActividadInasistente($igual = true)
    {
        return $this->_addFilterBy(self::ID_ACTIVIDAD, $igual, self::VALOR_ACTIVIDAD_INASISTENTE);
    }

    /**
     * Agrega los tipo de ACTIVIDAD igual a PERMISO REMUNERADO
     *
     * @return Fmo_Model_Personal
     */
    public function addFilterByActividadPermisoRemunerado($igual = true)
    {
        return $this->_addFilterBy(self::ID_ACTIVIDAD, $igual, self::VALOR_ACTIVIDAD_PERMISO_REMUNERADO);
    }

    /**
     * Agrega los tipo de ACTIVIDAD igual a PREVACACION
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByActividadPrevacacion($igual = true)
    {
        return $this->_addFilterBy(self::ID_ACTIVIDAD, $igual, self::VALOR_ACTIVIDAD_PREVACACION);
    }

    /**
     * Agrega los tipo de ACTIVIDAD igual a REPOSO
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByActividadReposo($igual = true)
    {
        return $this->_addFilterBy(self::ID_ACTIVIDAD, $igual, self::VALOR_ACTIVIDAD_REPOSO);
    }

    /**
     * Agrega los tipo de ACTIVIDAD igual a RETIRADO
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByActividadRetirado($igual = true)
    {
        return $this->_addFilterBy(self::ID_ACTIVIDAD, $igual, self::VALOR_ACTIVIDAD_RETIRADO);
    }

    /**
     * Agrega los tipo de ACTIVIDAD igual a SERVICIO MILITAR
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByActividadServicioMilitar($igual = true)
    {
        return $this->_addFilterBy(self::ID_ACTIVIDAD, $igual, self::VALOR_ACTIVIDAD_SERVICIO_MILITAR);
    }

    /**
     * Agrega los tipo de ACTIVIDAD igual a SUSPENDIDO
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByActividadSuspendido($igual = true)
    {
        return $this->_addFilterBy(self::ID_ACTIVIDAD, $igual, self::VALOR_ACTIVIDAD_SUSPENDIDO);
    }

    /**
     * Agrega los tipo de ACTIVIDAD igual a VACACIONES
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByActividadVacaciones($igual = true)
    {
        return $this->_addFilterBy(self::ID_ACTIVIDAD, $igual, self::VALOR_ACTIVIDAD_VACACIONES);
    }

    /**
     * Agrega un filtro para las busquedas por Cédula de Identidad.
     *
     * @param mixed $cedula CI N° del trabajar, puede ser un String, Integer o Array.
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByCedula($cedula, $igual = true)
    {
        return $this->_addFilterBy(self::CEDULA, $igual, $cedula);
    }

    /**
     * Agrega un filtro para las busquedas por el Siglado.
     *
     * @param mixed $siglado Siglado del trabajar.
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterBySiglado($siglado, $igual = true)
    {
        return $this->_addFilterBy(self::SIGLADO, $igual, $siglado);
    }

    /**
     * Agrega un filtro para las busquedas por el Correo Electrónico.
     *
     * @param mixed $correoEletronico Correo Eletrónico del trabajar.
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByCorreoElectronico($correoEletronico, $igual = true)
    {
        return $this->_addFilterBy(self::CORREO_ELECTRONICO, $igual, $correoEletronico);
    }

    /**
     * Agrega un filtro para las busquedas por el Siglado.
     *
     * @param mixed $siglado Siglado del trabajar.
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByUsuarioEnSesion($igual = true)
    {
        return $this->addFilterBySiglado(Fmo_Model_Seguridad::getSiglado(), $igual);
    }

    /**
     * Agrega un filtro para las busquedas por la Ficha.
     *
     * @param mixed $siglado Siglado del trabajar.
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByFicha($ficha, $igual = true)
    {
        return $this->_addFilterBy(self::FICHA, $igual, $ficha);
    }

    /**
     * Método para realizar una busqueda en todos los campos.
     *
     * @param string $texto Texto a buscar
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByTexto($texto, $igual = true)
    {
        return $this->_addFilterBy(self::FILTRO_BUSCAR, $igual, Fmo_Util::splitTextWordsToArray($texto));
    }

    /**
     * Filtro por la nómina GERENCIAL
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByNominaGerencia($igual = true)
    {
        return $this->_addFilterBy(self::ID_NOMINA, $igual, self::VALOR_NOMINA_GERENCIAL);
    }

    /**
     * Filtro por la nómina EJECUTIVA
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByNominaEjecutiva($igual = true)
    {
        return $this->_addFilterBy(self::ID_NOMINA, $igual, self::VALOR_NOMINA_EJECUTIVA);
    }

    /**
     * Filtro por la nómina MENSUAL NO AMPARADA
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByNominaMensualNoAmparada($igual = true)
    {
        return $this->_addFilterBy(self::ID_NOMINA, $igual, self::VALOR_NOMINA_MENSUAL_NO_AMPARADA);
    }

    /**
     * Filtro por la nómina MENSUAL MENOR CONTRATO INDIVIDUAL
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByNominaMensualMenorContratoIndividual($igual = true)
    {
        return $this->_addFilterBy(self::ID_NOMINA, $igual, self::VALOR_NOMINA_MENSUAL_MENOR_CONTRATO_INDIVIDUAL);
    }

    /**
     * Filtro por la nómina MENSUAL AMPARADA
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByNominaMensualAmparada($igual = true)
    {
        return $this->_addFilterBy(self::ID_NOMINA, $igual, self::VALOR_NOMINA_MENSUAL_AMPARADA);
    }

    /**
     * Filtro por la nómina DIARIA
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByNominaMensualDiaria($igual = true)
    {
        return $this->_addFilterBy(self::ID_NOMINA, $igual, self::VALOR_NOMINA_DIARIA);
    }

    /**
     * Filtro por la nómina APRENDICES INCE
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByNominaAprendicesInce($igual = true)
    {
        return $this->_addFilterBy(self::ID_NOMINA, $igual, self::VALOR_NOMINA_APRENDICES_INCE);
    }

    /**
     * Filtro por la nómina PASANTES
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByNominaPasantes($igual = true)
    {
        return $this->_addFilterBy(self::ID_NOMINA, $igual, self::VALOR_NOMINA_PASANTES);
    }

    /**
     * Filtro por la nómina PENSIONADOS
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByNominaPensionados($igual = true)
    {
        return $this->_addFilterBy(self::ID_NOMINA, $igual, self::VALOR_NOMINA_PENSIONADOS);
    }

    /**
     * Filtro por la nómina JUBILADOS
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByNominaJubilados($igual = true)
    {
        return $this->_addFilterBy(self::ID_NOMINA, $igual, self::VALOR_NOMINA_JUBILADOS);
    }

    /**
     * Filtro por la nómina PENSIONADOS INVALIDEZ
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByNominaPensionadosInvalidez($igual = true)
    {
        return $this->_addFilterBy(self::ID_NOMINA, $igual, self::VALOR_NOMINA_PENSIONADOS_INVALIDEZ);
    }

    /**
     * Filtro por la nómina DIETA DIRECTORES
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByNominaDietaDirectores($igual = true)
    {
        return $this->_addFilterBy(self::ID_NOMINA, $igual, self::VALOR_NOMINA_DIETA_DIRECTORES);
    }

    /**
     * Filtro por la nómina PROFESORES
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByNominaProfesores($igual = true)
    {
        return $this->_addFilterBy(self::ID_NOMINA, $igual, self::VALOR_NOMINA_PROFESORES);
    }

    /**
     * Filtro por la nómina MENSUAL MENOR CONTRATO COLECTIVO
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByNominaMensualMenorContratoColectivo($igual = true)
    {
        return $this->_addFilterBy(self::ID_NOMINA, $igual, self::VALOR_NOMINA_MENSUAL_MENOR_CONTRATO_COLECTIVO);
    }

    /**
     * Filtro por el sexo FEMENINO
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterBySexoFemenino($igual = true)
    {
        return $this->_addFilterBy(self::ID_SEXO, $igual, self::VALOR_SEXO_FEMENINO);
    }

    /**
     * Filtro por el sexo MASCULINO
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterBySexoMasculino($igual = true)
    {
        return $this->_addFilterBy(self::ID_SEXO, $igual, self::VALOR_SEXO_MASCULINO);
    }

    /**
     * Método para filtrar por un Mes y Dia de cumpleaños, por defecto se toma el día y mes actual.
     *
     * @param $mes Mes de cumpleaños.
     * @param $dia Día de cumpleaños.
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByCumpleanio($mes = null, $dia = null, $igual = true)
    {
        $fecha = new Zend_Date();

        if (!is_null($mes)) {
            $fecha->setMonth($mes);
        }

        if (!is_null($dia)) {
            $fecha->setDay($dia);
        }

        return $this->_addFilterBy(self::FILTRO_CUMPLEANIOS, $igual, $fecha->toString(Zend_Date::MONTH . Zend_Date::DAY));
    }

    /**
     * Usa para el filtro de LIKE
     * 
     * @param string $centrocosto Centro de costo
     * @param integer $tamanio Tamaño a cortar
     * @return Fmo_Model_Personal
     */
    protected function _completarUnidadOrganizativaFiltro(&$centrocosto, $tamanio)
    {
        if (is_array($centrocosto)) {
            foreach ($centrocosto as &$value) {
                $value = Fmo_Util::left($value, $tamanio) . '%';
            }
        } else {
            $centrocosto = Fmo_Util::left($centrocosto, $tamanio) . '%';
        }
        return $this;
    }

    /**
     * Filtra por la gerencia del Usuario en Sesión.
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByGerenciaViaCentroCosto($centroCosto, $igual = true)
    {
        return $this->_completarUnidadOrganizativaFiltro($centroCosto, 2)
                    ->_addFilterBy(self::FILTRO_UNIDAD_ORGANIZATIVA, $igual, $centroCosto);
    }

    /**
     * Filtra por la gerencia del Usuario en Sesión.
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByDepartamentoViaCentroCosto($centroCosto, $igual = true)
    {        
        return $this->_completarUnidadOrganizativaFiltro($centroCosto, 5)
                    ->_addFilterBy(self::FILTRO_UNIDAD_ORGANIZATIVA, $igual, $centroCosto);
    }


    /**
     * Filtra por la gerencia del Usuario en Sesión.
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByGerenciaViaUsuarioSesion($igual = true)
    {
        return $this->addFilterByGerenciaViaCentroCosto(Fmo_Model_Seguridad::getUsuarioSesion()->{self::ID_CENTRO_COSTO}, $igual);
    }

    /**
     * Filtra por la gerencia del Usuario en Sesión.
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByDepartamentoViaUsuarioSesion($igual = true)
    {
        return $this->addFilterByDepartamentoViaCentroCosto(Fmo_Model_Seguridad::getUsuarioSesion()->{self::ID_CENTRO_COSTO}, $igual);
    }

    /**
     * Filtra por el estado civil de CASADO.
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByEstadoCivilCasado($igual = true)
    {
        return $this->_addFilterBy(self::ID_ESTADO_CIVIL, $igual, self::VALOR_ESTADO_CIVIL_CASADO);
    }

    /**
     * Filtra por el estado civil de CONCUBINO.
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByEstadoCivilConcubino($igual = true)
    {
        return $this->_addFilterBy(self::ID_ESTADO_CIVIL, $igual, self::VALOR_ESTADO_CIVIL_CONCUBINO);
    }

    /**
     * Filtra por el estado civil de DIVORCIADO.
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByEstadoCivilDivorciado($igual = true)
    {
        return $this->_addFilterBy(self::ID_ESTADO_CIVIL, $igual, self::VALOR_ESTADO_CIVIL_DIVORCIADO);
    }

    /**
     * Filtra por el estado civil de SOLTERO.
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByEstadoCivilSoltero($igual = true)
    {
        return $this->_addFilterBy(self::ID_ESTADO_CIVIL, $igual, self::VALOR_ESTADO_CIVIL_SOLTERO);
    }

    /**
     * Filtra por el estado civil de VIUDO.
     *
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByEstadoCivilViudo($igual = true)
    {
        return $this->_addFilterBy(self::ID_ESTADO_CIVIL, $igual, self::VALOR_ESTADO_CIVIL_VIUDO);
    }

    /**
     * Filtra por la PROFESION.
     *
     * @param integer $codigo Código de la profesión.
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByProfesionId($codigo, $igual = true)
    {
        return $this->_addFilterBy(self::ID_PROFESION, $igual, $codigo);
    }

    /**
     * Filtra por la NIVEL EDUCATIVO.
     *
     * @param string $codigo Código del nivel educativo.
     * @param boolean $igual Indica que tipo de operador se utilizara; de inclusión (=, IN ó LIKE) o rechazo (<>, NOT IN, NOT LIKE).
     * @return Fmo_Model_Personal
     */
    public function addFilterByNivelEducativoId($codigo, $igual = true)
    {
        return $this->_addFilterBy(self::ID_NIVEL_EDUCATIVO, $igual, $codigo);
    }

    /**
     * Método que devuelve la consulta utilizada para obtener información de RpsDatos o el Sistema de Nómina.
     *
     * @return Zend_Db_Select
     */
    public function getSelect()
    {
        if (!empty($this->_filters)) {
            // filtros
            foreach ($this->_filters as $condition => $data) {
                $this->_select->where($condition, Fmo_Util::arrayMbConvertCase($data['value']), $data['type']);
                unset($this->_filters[$condition]);
            }
        }

        // ordenamiento
        if (!empty($this->_orderBy)) {
            $this->_select->order($this->_orderBy);
            $this->_orderBy = null;
        }

        if (!empty($this->_limit)) {
            $this->_select->limit($this->_limit);
            $this->_limit = null;
        }

        return $this->_select;
    }

    /**
     * Devuelve un registro coincidente.
     *
     * @return object|null
     */
    public function findOne()
    {
        $this->_limit = 1;

        $datos = $this->_execute();

        return isset($datos[0]) ? $datos[0] : null;
    }

    /**
     * Devuelve todos registro coincidentes.
     *
     * @return array
     */
    public function findAll()
    {
        return $this->_execute();
    }

    /**
     * Devuelve la cadena con los valores de esta clase.
     *
     * @return string
     */
    public function __toString()
    {
        return Zend_Debug::dump($this, __CLASS__, false);
    }

    /**
     * Buscar un registro por Ficha.
     *
     * @param string $ficha Valor a buscar.
     * @return object|null
     */
    public static function findOneByFicha($ficha)
    {
        return self::_find(self::FICHA, $ficha);
    }

    /**
     * Buscar un registro por Siglado.
     *
     * @param string $siglado Valor a buscar.
     * @return object|null
     */
    public static function findOneBySiglado($siglado)
    {
        return self::_find(self::SIGLADO, $siglado);
    }

    /**
     * Buscar un registro por Cédula de Identidad.
     *
     * @param string $cedula Valor a buscar.
     * @return object|null
     */
    public static function findOneByCedula($cedula)
    {
        return self::_find(self::CEDULA, $cedula);
    }

    /**
     * Buscar un registro por el usuario en sesión.
     *
     * @return object|null
     */
    public static function findByUserSession()
    {
        return self::findOneBySiglado(Fmo_Model_Seguridad::getSiglado());
    }

    /**
     * Buscar un registro por el Correo Electrónico.
     *
     * @param string $correoElectronico
     */
    public static function findOneByCorreoElectronico($correoElectronico)
    {
        return self::_find(self::CORREO_ELECTRONICO, $correoElectronico);
    }

    /**
     * Buscar trabajadores por los CI N°
     *
     * @param array $cedulas CI N° de trabajadores buscadas
     * @return array Devuelve el listado de trabajadores coincidentes.
     */
    public static function findAllByCedula($cedulas)
    {
        $modelPersona = new Fmo_Model_Personal();
        $modelPersona->addFilterByCedula($cedulas);
        return $modelPersona->findAll();
    }
    
    // CONSULTA PARA SELECT2 DE TRABAJADORES
    /*public static function getByFicha($param)
    {
        $tblTrabajadores = new Fmo_DbTable_Rpsdatos_DatoBasico();
        $sql = $tblTrabajadores->select()
                ->setIntegrityCheck(false)
                ->from(array('e' => $tblTrabajadores->info(Zend_Db_Table::NAME)),
                        array(
                            'id' => 'e.datb_cedula',
                            'text' => new Zend_Db_Expr("e.datb_nrotrab|| ' ' ||  e.datb_nacion || '-' || e.datb_cedula || ' ' || e.datb_nombre || ' ' || e.datb_apellid")
                        ), 
                        
                        $tblTrabajadores->info(Zend_Db_Table::SCHEMA))
                ->where('e.datb_nrotrab::text ILIKE ?', "%$param%")
                ->orWhere('e.datb_nombre::text ILIKE ?', "%$param%")
                ->order('e.datb_activi');
        //exit($sql->__toString());
        return $tblTrabajadores->fetchAll($sql);
    }*/
}
