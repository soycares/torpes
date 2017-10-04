<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Potras extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index() {
        if (!$this->session->userdata('tor_usuario')) {
            //echo $this->input->post('usuario');

            $datos = array();
            $this->load->view('torpes/login', $datos);
            return;
            /*
              $rs = $this->db->get('ov_config');
              if ($rs->num_rows() == 1) {
              $conf = $rs->row();
              //				echo "<br>-> temp=".$conf->idTemporada;
              }
              //		else
              //			echo "<br>Mas de un registro, ERROR";
              $this->session->set_userdata('idTemporada', $conf->idTemporada);
              $this->session->set_userdata('conf', $conf);
             */
        }

        $this->load->model('Torpes_model');
         $jornadas = $this->Torpes_model->lista_jornadas2();


        //$datos=array();
        $rs = $this->db->query("SELECT d.idTemporada, a.idJornada,d.numTemporada, a.numJornada, b.nombre as local, c.nombre as visitante, fecha, golesLocal, golesVisitante , signo
FROM tor_temporadas d, tor_jornadas a, tor_equipos b, tor_equipos c
WHERE d.activa = 1
and d.idTemporada=a.idTemporada
and a.activa = 1
and a.idLocal = b.idEquipo
and a.idVisitante = c.idEquipo
");

        $fila = $rs->row();
        $idTemporada = $fila->idTemporada;
        $numJornada = $fila->numJornada;

        $datos = array('partidos' => $rs, 
            'numJornada' => $numJornada, 
            'idTemporada' => $idTemporada,
            'jornadas' => $jornadas);
        $this->load->view('torpes/admin/potras', $datos);
        //$this->session->unset_userdata('tor_usuario');
        return;
    }

    public function listado($numJornada="")
    {
        if (!$this->session->userdata('tor_usuario')) {
            $datos = array();
            $this->load->view('torpes/login', $datos);
            return;
        }

        $this->load->model('Torpes_model');
        $jornadas = $this->Torpes_model->lista_jornadas2();


        //$datos=array();
        if ($numJornada == "")
        {
            $rs = $this->db->query("SELECT d.idTemporada, a.idJornada,d.numTemporada, a.numJornada, b.nombre as local, c.nombre as visitante, fecha, golesLocalPotra, golesVisitantePotra, signo
                FROM tor_temporadas d, tor_jornadas a, tor_equipos b, tor_equipos c
                WHERE d.activa = 1
                and d.idTemporada=a.idTemporada
                and a.activa = 1
                and a.idLocal = b.idEquipo
                and a.idVisitante = c.idEquipo
                ");
        }
        else
        {
            $rs = $this->db->query("SELECT d.idTemporada, a.idJornada,d.numTemporada, a.numJornada, b.nombre as local, c.nombre as visitante, fecha, golesLocalPotra, golesVisitantePotra, signo
            FROM tor_temporadas d, tor_jornadas a, tor_equipos b, tor_equipos c
            WHERE d.activa = 1
            and a.numJornada = ".$numJornada." 
            and d.idTemporada=a.idTemporada
            and a.idLocal = b.idEquipo
            and a.idVisitante = c.idEquipo
            ");
        }
        
        $fila = $rs->row();
        
        $idTemporada = $fila->idTemporada;
        $numJornada = $fila->numJornada;

        $datos = array('partidos' => $rs, 
            'numJornada' => $numJornada, 
            'idTemporada' => $idTemporada,
            'jornadas' => $jornadas);
        $this->load->view('torpes/admin/potras', $datos);
        //$this->session->unset_userdata('tor_usuario');
        return;

    }

    
    function resultado() {
        if (!$this->session->userdata('tor_usuario')) {
            $datos = array();
            $this->load->view('torpes/login', $datos);
            return;
        }
       
        $aux = array();
        foreach ($_POST as $dato => $valor) {
            
            $a = explode("_", $dato);
            if (count($a) == 2)  
            {
                $campo = $a[0];
                $id_jornada = $a[1];
            }
            else
            {
                continue;
            }
            $aux[$id_jornada][$campo] = $valor;
        }
        
        foreach ($aux as $idJornada => $datos) {
            // no actualizar si no hay datos metidos
            if ($datos["golesLocalPotra"] == -1 || $datos["golesVisitantePotra"] == -1)
                continue;

            //'idUsuario' => $this->session->userdata("tor_idUsuario")
            if ($datos["golesLocalPotra"] > $datos["golesVisitantePotra"])
                $datos["signo"] = "1";
            else if ($datos["golesLocalPotra"] == $datos["golesVisitantePotra"])
                $datos["signo"] = "X";
            else
                $datos["signo"] = "2";


            $data = array();

            foreach ($datos as $campo => $valor) {
                $data[$campo] = $valor;
            }

            $this->db->where('idJornada', $idJornada);
            $this->db->update('tor_jornadas', $data);
            log_message('error', 'SQL: ' . $this->db->last_query());
            echo $this->db->last_query()."<br>";

        }
        
        redirect('torpes/admin/potras/listado/'.$numJornada);
    }

    function puntos($idTemporada, $numJornada="") {
                if (!$this->session->userdata('tor_usuario')) {
            $datos = array();
            $this->load->view('torpes/login', $datos);
            return;
        }

        $this->load->model('Torpes_model');
        //echo "NumJornada=".$numJornada."<br><pre>";
        if ($numJornada == "")
            $datos_jornadas = $this->Torpes_model->jornada_activa($numJornada);
        else 
            $datos_jornadas = $this->Torpes_model->jornada_activa_numero($numJornada);
        
        //echo print_r($datos_jornadas);
        //echo "</pre>";
        $jornadas = $datos_jornadas['jornadas'];

/*        foreach ($jornadas as $key => $value) {
            $this->db->where('idJornada', $value['idJornada']);
            $this->db->where('penalizacion', 1);
            $this->db->delete('tor_apuestas');
            //echo $this->db->last_query()."<br>";
        }
*/


        $rs = $this->db->query("SELECT b.idApuesta, a.idJornada, a.golesLocalPotra AS golesLocalResultado, a.golesVisitantePotra AS golesVisitanteResultado, b.golesLocal AS golesLocalApuesta, b.golesVisitante AS golesVisitanteApuesta, b.idUsuario, c.nombre, signo
FROM tor_jornadas a, tor_apuestas b, tor_usuarios c
            WHERE idTemporada=" . $idTemporada . " and numJornada=" . $numJornada . " and signo in ('1','X','2') AND a.idJornada = b.idJornada
AND c.idUsuario = b.idUsuario and penalizacion = 0");
        
        log_message('error','[admin/jornada/puntos/'.$idTemporada.'/'.$numJornada.'] Calcular puntos ------------------------------------------------------');
        foreach ($rs->result() as $fila) {
           // echo "----------------------------------------------------------------<br>";
            
            log_message('error','[admin/jornada/puntos/'.$idTemporada.'/'.$numJornada.'] Actualizar Puntos: '.$fila->nombre . " - Resultado: " . $fila->golesLocalResultado . " - " . $fila->golesVisitanteResultado . " :: Apuesta: " . $fila->golesLocalApuesta . " - " . $fila->golesVisitanteApuesta);
            $puntos_signo = 0;
            $puntos_resultado = 0;
            $puntos_goles = 0;
            $puntos_resta = 0;
            $puntos_total = 0;
            if ($fila->golesLocalApuesta == $fila->golesVisitanteApuesta)
                $signo_apuesta = "X";
            else if ($fila->golesLocalApuesta > $fila->golesVisitanteApuesta)
                $signo_apuesta = "1";
            else
                $signo_apuesta = "2";

            if ($fila->golesLocalResultado == $fila->golesLocalApuesta && $fila->golesVisitanteResultado == $fila->golesVisitanteApuesta) {
                $puntos_resultado = 2;
          //      echo "  Puntos resultado: 2<br>";
            }
            if ($fila->signo == $signo_apuesta) {
                $puntos_signo = 5;
        //        echo "  Puntos signo: 5<br>";
            }
            if ($fila->golesLocalResultado == $fila->golesLocalApuesta) {
                $puntos_goles+=$fila->golesLocalResultado + 1;
      //          echo "  Puntos Goles local: " . ($fila->golesLocalResultado + 1) . "<br>";
            } else {
                $puntos_resta+= abs($fila->golesLocalResultado - $fila->golesLocalApuesta);
    //            echo "  Puntos Resta local: " . abs($fila->golesLocalResultado - $fila->golesLocalApuesta) . "<br>";
            }

            if ($fila->golesVisitanteResultado == $fila->golesVisitanteApuesta) {
                $puntos_goles+=$fila->golesVisitanteResultado + 1;
  //              echo "  Puntos Goles local: " . ($fila->golesVisitanteResultado + 1) . "<br>";
            } else {
                $puntos_resta+= abs($fila->golesVisitanteResultado - $fila->golesVisitanteApuesta);
//                echo "  Puntos Resta local: " . abs($fila->golesVisitanteResultado - $fila->golesVisitanteApuesta) . "<br>";
            }
            $puntos_total = $puntos_signo + $puntos_resultado + $puntos_goles - $puntos_resta;
            log_message('error','[admin/jornada/puntos/'.$idTemporada.'/'.$numJornada.'] Puntos: ' . $puntos_total ." ($puntos_resultado + $puntos_signo + $puntos_goles - $puntos_resta)");
            $data = array('puntos_res_potra' => $puntos_resultado,
                'puntos_signo_potra' => $puntos_signo,
                'puntos_goles_potra' => $puntos_goles,
                'puntos_resta_potra' => $puntos_resta,
                'puntos_total_potra' => $puntos_total);
            $this->db->where('idApuesta', $fila->idApuesta);
            $this->db->update('tor_apuestas', $data);
//            log_message('error', 'SQL: '.$this->db->last_query()); 
            //echo $this->db->last_query() . "<br>";
        }
        log_message('error','[admin/jornada/puntos/'.$idTemporada.'/'.$numJornada.'] Calcular penalizaciones -------------------------------------------------');
        // penalizaciones


        $datos = array();
        $rs = $this->db->query("select idUsuario, nombre from tor_usuarios a 
                                where not exists (select 1 from tor_temporadas d, tor_apuestas b, tor_jornadas c 
                                                  where d.activa=1 and d.idTemporada=c.idTemporada and b.idJornada=c.idJornada and a.idUsuario=b.idUsuario and numJornada = ".$numJornada.")");
        //echo $this->db->last_query()."<br><br>";
        foreach ($rs->result() as $fila) {
            $idUsuario = $fila->idUsuario;

            log_message('error','[admin/jornada/puntos/'.$idTemporada.'/'.$numJornada.'] Usuario ('.$fila->idUsuario.") ".$fila->nombre." sin apuestas.");
            
            $rs2 = $this->db->query("select idJornada from tor_jornadas a , tor_temporadas b where b.activa=1 and a.idTemporada = b.idTemporada and a.numJornada=".$numJornada." and not exists (select 1 from tor_apuestas b where a.idJornada=b.idJornada and b.idUsuario=" . $fila->idUsuario . ")");
            //echo $this->db->last_query()."<br>";
            foreach ($rs2->result() as $fila2) {
                $datos[$fila->idUsuario][] = $fila2->idJornada;
                log_message('error','[admin/jornada/puntos/'.$idTemporada.'/'.$numJornada.'] idJornada '.$fila2->idJornada." inexistente");
            }
            //echo "Jornadas con penalizacion: <pre>";
            //print_r($datos);
            //echo "</pre>";

            $rs3 = $this->db->query("select a.idJornada,min(puntos_total) as puntos_total 
from tor_apuestas a, tor_jornadas b, tor_temporadas c
where c.activa = 1 and c.idTemporada=b.idTemporada and b.numJornada=".$numJornada." and a.idJornada=b.idJornada group by a.idJornada");
//echo $this->db->last_query()."<br>";
            $puntos = array();
            foreach ($rs3->result() as $fila3) {
                if ($fila3->puntos_total > 0)
                    $fila3->puntos_total = 0;
                $puntos[$fila3->idJornada] = $fila3->puntos_total;
                log_message('error','[admin/jornada/puntos/'.$idTemporada.'/'.$numJornada.'] Puntos minimos en jornada '.$fila3->idJornada.": ".$fila3->puntos_total);
            }

//            echo "Puntos: <br><pre>";
//            print_r($puntos);
//            echo "</pre>";

            foreach ($datos as $idUsu => $value) {
  //              var_dump($value);
                foreach ($value as $id => $jor) {
                    $datos_insert = array(
                        'puntos_res_potra' => 0,
                        'puntos_signo_potra' => 0,
                        'puntos_goles_potra' => 0,
                        'puntos_resta_potra' => 0,
                        'puntos_total_potra' => $puntos[$jor]);
                    
                    
                    $this->db->where('idJornada', $jor);
                    $this->db->where('idUsuario', $idUsu);
                    $this->db->update('tor_apuestas', $datos_insert);
//                    echo "<pre>";
//                    print_r($datos_insert);
//                    echo "</pre>";
                    log_message('error','[admin/jornada/puntos/'.$idTemporada.'/'.$numJornada.'] Actualizamos penalizacion usaurio '.$idUsu.", jornada  ".$jor.". Puntos ".$puntos[$jor]);
                    log_message('error','[admin/jornada/puntos/'.$idTemporada.'/'.$numJornada.'] '.$this->db->last_query());
                }
            }
        }
        redirect('torpes/admin/jornada');
        
                }

    function nueva() {
        if (!$this->session->userdata('tor_usuario')) {
            $datos = array();
            $this->load->view('torpes/login', $datos);
            return;
        }

        //Buscar ultima jornada
        $idTemporada = $this->session->userdata('tor_idTemporada');
        $texto_error="";
        
        if ($this->input->post('numJornada') == "") {

            //echo "NUEVA $idTemporada<br>";
            $rs = $this->db->query("select max(numJornada) as numJornada from tor_jornadas where idTemporada=" . $idTemporada);
            $fila = $rs->row();
            $numJornada = $fila->numJornada + 1;
            //echo "Jornada=$numJornada<br>";
            $rs = $this->db->query("select numPartidos from tor_temporadas where idTemporada=" . $idTemporada);
            $fila = $rs->row();
            $numPartidos = $fila->numPartidos;
            //echo "Partidos=$numPartidos<br>";
        } else {
            // GRABAR DATOS
//            echo "GRABAR $idTemporada<br>";
//            echo "<pre>";
//            print_r($_POST);
//            echo "</pre>";
            
            $numJornada = $this->input->post('numJornada');
            $activa= $this->input->post('activa');
            $numPartidos = $this->input->post('numPartidos');
            
            $aux = array();
            foreach ($_POST as $dato => $valor) {
                if ($dato == "numJornada" || $dato=="idTemporada" || $dato=="activa" || $dato == "numPartidos" )
                    continue;
                //echo "$dato - $valor<br>";
                $a = explode("_", $dato);
                //var_dump($a);
                $campo = $a[0];
                $id_jornada = $a[1];
                
                $aux[$id_jornada][$campo] = $valor;
            }
            echo "<pre>";
            print_r($_POST);
            echo "</pre>";
            echo "<pre>";
            print_r($aux);
            echo "</pre>";
        
            //comprobar datos correctos
            $error=array();
            foreach ($aux as $pos => $datos)
            {
                // Si no existe idLocal o idVisitante insertar equipo
                if ($datos['idLocal'] == "" && $datos['nombreLocal'] != "")
                {
                    $datos_insert = array(
                        'nombre' => $datos['nombreLocal'],
                        'escudo' => "",
                        'escudoPeq' => ""
                        );
                    $this->db->insert('tor_equipos', $datos_insert);                    
                    //echo $this->db->set($datos_insert)->get_compiled_insert('tor_equipos')."<br>";
                    
                    $id = $this->db->insert_id();
                    $aux[$pos]['idLocal'] = $id;
                    echo "ID=".$aux[$pos]['idLocal']."<br>";
                    echo "INSERTAR EQUIPO LOCAL<br>";
                }
                if ($datos['idVisitante'] == "" && $datos['nombreVisitante'] != "")
                {
                    $datos_insert = array(
                        'nombre' => $datos['nombreVisitante'],
                        'escudo' => "",
                        'escudoPeq' => ""
                        );
                    $this->db->insert('tor_equipos', $datos_insert);                    
                    //echo $this->db->set($datos_insert)->get_compiled_insert('tor_equipos')."<br>";
                    
                    $id = $this->db->insert_id();
                    $aux[$pos]['idVisitante'] = $id;
                    echo "ID=".$aux[$pos]['idLocal']."<br>";

                    echo "INSERTAR EQUIPO VISITANTE<br>";
                }

                if ($datos['idLocal'] == $datos['idVisitante'])
                    $error[]="Partido ".$pos." con equipos iguales";
                if ($datos['fecha'] == "")
                    $error[]="Partido ".$pos." sin fecha";
                
                // comprobar formato fecha
                $fecha = $datos['fecha'];
                
                $fecha_arr = explode(' ',$fecha);
                if (count($fecha_arr) != 2)
                    $error[]="Partido ".$pos." tiene el formato de la fecha incorrecta";
                else
                {

                    $fecha = explode ("/",$fecha_arr[0]);
                    if (count($fecha) != 3)
                        $error[]="Partido ".$pos." tiene el formato de la fecha incorrecta";
                    else
                    {
                        $hora = explode (":",$fecha_arr[1]);
                        if (count($hora) != 2)
                            $error[]="Partido ".$pos." tiene el formato de la fecha incorrecta";
                    }
                }
                
                
            }
              
            $texto_error="";
            if (count($error) > 0 && 1 == 2)
            {
                
                
                foreach ($error as $key => $value)
                {
                    $texto_error.="<p>".$value."</p>";                    
                }
                echo "hay error: $texto_error<br>";
            }
            else 
                {
                //echo "NO hay error<br>";
                // Borrar activas previas
                if ($activa == 1)
                {
                    $this->db->where('activa',1);
                    $this->db->set('activa',0);
                    $this->db->update('tor_jornadas');
                }
                
                foreach ($aux as $pos => $datos)
                {
                    $fecha = $datos['fecha'];
                    $fecha_arr = explode(' ',$fecha);
                    $fecha = explode ("-",$fecha_arr[0]);

                    
                    $hora = explode (":",$fecha_arr[1]);
                    
                    $fecha_grabar = $fecha[2]."-".$fecha[1]."-".$fecha[0]." ".$hora[0].":".$hora[1].":00";
                    
                    $datos_insert = array('idTemporada' => $idTemporada,
                        'numJornada' => $numJornada,
                        'idLocal' => $datos['idLocal'],
                        'idVisitante' => $datos['idVisitante'],
                        'activa' => $activa,
                        'golesLocal' => 0,
                        'golesVisitante' => 0,
                        'signo' => '',
                        'fecha' => $fecha_grabar ,
                        'idCompeticion' => 1);
                    $this->db->insert('tor_jornadas', $datos_insert);                    
                    //echo $this->db->get_compiled_insert()."<br>";
                    echo $this->db->last_query()."<br>";
                }
                
            }
        }

        // equipos
        $eq = $this->db->query('select idEquipo,nombre from tor_equipos order by nombre');
        
        //$this->load->model('Torpes_model');

        //$listaEquipos = $this->Torpes_model->lista_equipos();

        $datos_salida['error']=$texto_error;
        //$datos_salida['listaEquipos'] = $listaEquipos;
        $datos_salida['idTemporada'] = $idTemporada;
        $datos_salida['numJornada'] = $numJornada;
        $datos_salida['numPartidos'] = $numPartidos;
        $datos_salida['equipos'] = $eq ;
        
        
        
        $this->load->view('torpes/admin/jornada_nueva', $datos_salida);
    }

    public function modificar($idTemporada="",$numJornada="") {

        if (!$this->session->userdata('tor_usuario')) {
            $datos = array();
            $this->load->view('torpes/login', $datos);
            return;
        }
        
        if (isset($_POST['numJornada']))
            echo "ACTUALIZAR<br>";
        
        //Buscar ultima jornada
        $idTemporada = $this->session->userdata('tor_idTemporada');
        $texto_error="";
        
        if ($this->input->post('numJornada') == "") {


            $rs = $this->db->query("select idJornada, idLocal,b.nombre as nombreLocal, idVisitante, c.nombre as nombreVisitante, fecha, d.activa from tor_equipos b, tor_equipos c, tor_jornadas d
                    where d.idTemporada=".$idTemporada." and numJornada=".$numJornada." and d.idLocal=b.idEquipo and d.idVisitante=c.idEquipo");

            //echo "Jornada=$numJornada<br>";
            $rs2 = $this->db->query("select numPartidos from tor_temporadas where idTemporada=" . $idTemporada);
            $fila = $rs2->row();
            $numPartidos = $fila->numPartidos;

                    // equipos
        $eq = $this->db->query('select idEquipo,nombre from tor_equipos order by nombre');
        
        //$this->load->model('Torpes_model');

        //$listaEquipos = $this->Torpes_model->lista_equipos();

        $datos_salida['error']=$texto_error;
        //$datos_salida['listaEquipos'] = $listaEquipos;
        $datos_salida['idTemporada'] = $idTemporada;
        $datos_salida['numJornada'] = $numJornada;
        $datos_salida['numPartidos'] = $numPartidos;
        $datos_salida['equipos'] = $eq ;
        $datos_salida['partidos'] = $rs ;
        
        $this->load->view('torpes/admin/jornada_modificar', $datos_salida);

            
            //echo "Partidos=$numPartidos<br>";
        } else {
            // GRABAR DATOS
            echo "GRABAR $idTemporada<br>";
//            echo "<pre>";
//            print_r($_POST);
//            echo "</pre>";
  
            $numJornada = $this->input->post('numJornada');
            $activa= $this->input->post('activa');
            $numPartidos = $this->input->post('numPartidos');
            
            $aux = array();
            foreach ($_POST as $dato => $valor) {
                if ($dato == "numJornada" || $dato=="idTemporada" || $dato=="activa" || $dato == "numPartidos" )
                    continue;
                //echo "$dato - $valor<br>";
                $a = explode("_", $dato);
                //var_dump($a);
                $campo = $a[0];
                $id_jornada = $a[1];
                
                $aux[$id_jornada][$campo] = $valor;
            }
            echo "<pre>";
            print_r($_POST);
            echo "</pre>";
            echo "<pre>";
            print_r($aux);
            echo "</pre>";
        
            //comprobar datos correctos
            $error=array();
            foreach ($aux as $pos => $datos)
            {
                // Si no existe idLocal o idVisitante insertar equipo
                if ($datos['idLocal'] == "" && $datos['nombreLocal'] != "")
                {
                    $datos_insert = array(
                        'nombre' => $datos['nombreLocal'],
                        'escudo' => "",
                        'escudoPeq' => ""
                        );
                    $this->db->insert('tor_equipos', $datos_insert);                    
                    //echo $this->db->set($datos_insert)->get_compiled_insert('tor_equipos')."<br>";
                    
                    $id = $this->db->insert_id();
                    $aux[$pos]['idLocal'] = $id;
                    echo "ID=".$aux[$pos]['idLocal']."<br>";
                    echo "INSERTAR EQUIPO LOCAL<br>";
                }
                if ($datos['idVisitante'] == "" && $datos['nombreVisitante'] != "")
                {
                    $datos_insert = array(
                        'nombre' => $datos['nombreVisitante'],
                        'escudo' => "",
                        'escudoPeq' => ""
                        );
                    $this->db->insert('tor_equipos', $datos_insert);                    
                    //echo $this->db->set($datos_insert)->get_compiled_insert('tor_equipos')."<br>";
                    
                    $id = $this->db->insert_id();
                    $aux[$pos]['idVisitante'] = $id;
                    echo "ID=".$aux[$pos]['idLocal']."<br>";

                    echo "INSERTAR EQUIPO VISITANTE<br>";
                }

                if ($datos['idLocal'] == $datos['idVisitante'])
                    $error[]="Partido ".$pos." con equipos iguales";
                if ($datos['fecha'] == "")
                    $error[]="Partido ".$pos." sin fecha";
                
                // comprobar formato fecha
                $fecha = $datos['fecha'];
                $fecha_arr = explode(' ',$fecha);

                if (count($fecha_arr) != 2)
                    $error[]="Partido ".$pos." tiene el formato de la fecha incorrecta";
                else
                {

                    $fecha = explode ("-",$fecha_arr[0]);
                    if (count($fecha) != 3 && count($fecha) != 2)
                        $error[]="Partido ".$pos." tiene el formato de la fecha incorrecta";
                    else
                    {
                        $hora = explode (":",$fecha_arr[1]);
                        if (count($hora) != 2 && count($hora) != 3)
                            $error[]="Partido ".$pos." tiene el formato de la hora incorrecta 2";
                    }
                }
                
                
            }
              
            $texto_error="";
            if (count($error) > 0)
            {
                foreach ($error as $key => $value)
                {
                    $texto_error.="<p>".$value."</p>";                    
                }
                echo "hay error: $texto_error<br>";
            }
            else 
                {
                //echo "NO hay error<br>";
                // Borrar activas previas
                if ($activa == 1)
                {
//                    $this->db->where('activa',1);
//                    $this->db->set('activa',0);
//                    $this->db->update('tor_jornadas');
                }
                
                foreach ($aux as $pos => $datos)
                {
                    $fecha = $datos['fecha'];
                    $fecha_arr = explode(' ',$fecha);
                    $fecha = explode ("-",$fecha_arr[0]);

                    
                    $hora = explode (":",$fecha_arr[1]);
                    
                    $fecha_grabar = $fecha[0]."-".$fecha[1]."-".$fecha[2]." ".$hora[0].":".$hora[1].":00";
                    
                    $datos_insert = array('idTemporada' => $idTemporada,
                        'numJornada' => $numJornada,
                        'idLocal' => $datos['idLocal'],
                        'idVisitante' => $datos['idVisitante'],
                        'activa' => $activa,
                        'golesLocal' => 0,
                        'golesVisitante' => 0,
                        'signo' => '',
                        'fecha' => $fecha_grabar ,
                        'idCompeticion' => 1);
                    
                    $this->db->where('idJornada', $datos['idJornada']);
                    $this->db->set('idLocal', $datos['idLocal']);
                    $this->db->set('idVisitante', $datos['idVisitante']);
                    $this->db->set('activa', $activa);
                    $this->db->set('fecha', $fecha_grabar);
                    
                    //var_dump($datos_insert );
                    $this->db->update('tor_jornadas', $datos_insert);                    
                    //echo $this->db->get_compiled_update('tor_apuestas')."<br>";
                    echo $this->db->last_query()."<br>";
                }
                
            }
        }
die();
        // equipos
        $eq = $this->db->query('select idEquipo,nombre from tor_equipos order by nombre');
        
        //$this->load->model('Torpes_model');

        //$listaEquipos = $this->Torpes_model->lista_equipos();

        $datos_salida['error']=$texto_error;
        //$datos_salida['listaEquipos'] = $listaEquipos;
        $datos_salida['idTemporada'] = $idTemporada;
        $datos_salida['numJornada'] = $numJornada;
        $datos_salida['numPartidos'] = $numPartidos;
        $datos_salida['equipos'] = $eq ;
        
        
        
        $this->load->view('torpes/admin/jornada_modificar', $datos_salida);


    }
}
