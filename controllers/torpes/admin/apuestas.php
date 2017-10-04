<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Apuestas extends CI_Controller {

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


        //$datos=array();
        $rs = $this->db->query("SELECT d.idTemporada, a.idJornada,d.numTemporada, a.numJornada, b.nombre as local, c.nombre as visitante, fecha, golesLocal, golesVisitante , signo
FROM tor_temporadas d, tor_jornadas a, tor_equipos b, tor_equipos c
WHERE d.idTemporada = ".$this->session->userdata("tor_idTemporada")." 
and d.idTemporada=a.idTemporada
and a.activa = 1
and a.idLocal = b.idEquipo
and a.idVisitante = c.idEquipo
");

        $fila = $rs->row();
        $idTemporada = $fila->idTemporada;
        $numJornada = $fila->numJornada;

        $datos = array('partidos' => $rs, 'numJornada' => $numJornada, 'idTemporada' => $idTemporada);
        $this->load->view('torpes/admin/apuestas', $datos);
        //$this->session->unset_userdata('tor_usuario');
        return;
    }

   function listado($numJornada = "")
   {
       if (!$this->session->userdata('tor_usuario')) {
            $datos = array();
            $this->load->view('torpes/login', $datos);
            return;
        }
        
        $this->load->model("Torpes_model");

        if ($numJornada == "")
        {
            $datos_vuelta = $this->Torpes_model->jornada_activa();
            
            $jornada_cerrada = jornada_cerrada();
            //echo"<pre>";
            //print_r($datos_vuelta);
            //echo"</pre>";
            //echo "<br><b>jornada cerrada=$jornada_cerrada</b>";
            //die();
            $datos = array('datos' => $datos_vuelta, 'numJornada' => $datos_vuelta['numJornada'], 'jornadaCerrada' => $jornada_cerrada);
            
        $this->load->view('torpes/admin/apuestas', $datos);
          
        }
   }
    function nueva() {
        if (!$this->session->userdata('tor_usuario')) {
            $datos = array();
            $this->load->view('torpes/login', $datos);
            return;
        }

        //Buscar ultima jornada
        $idTemporada = $this->session->userdata('tor_idTemporada');
        $texto_error = "";

        if ($this->input->post('numJornada') == "") {

                  $rs = $this->db->query("SELECT a.idJornada,d.numTemporada, a.numJornada, b.nombre as local, 
                      c.nombre as visitante, fecha, numPartidos
FROM tor_temporadas d, tor_jornadas a, tor_equipos b, tor_equipos c
WHERE d.idTemporada = ".$this->session->userdata("tor_idTemporada")." 
and d.idTemporada=a.idTemporada
and a.activa = 1
and a.idLocal = b.idEquipo
and a.idVisitante = c.idEquipo
");

        $fila = $rs->row();
        $numJornada = $fila->numJornada;
        $numPartidos=$fila->numPartidos;

        // fecha de cierre es la menor de las fechas de la jornada
        $rs_3 = $this->db->query("SELECT min(fecha) as fecha
FROM tor_temporadas d, tor_jornadas a
WHERE d.idTemporada = ".$this->session->userdata("tor_idTemporada")." 
and d.idTemporada=a.idTemporada
and a.activa = 1
");
        $fila = $rs_3->row();
        $fechaCierre = $fila->fecha;

        // Partidos por jugadores
        $rs_2 = $this->db->query("SELECT numJornada, e.idUsuario, f.nombre AS nombreUsuario, c.nombre AS
LOCAL , d.nombre AS visitante, e.golesLocal AS golesLocalApuesta, e.golesVisitante AS golesVisitanteApuesta, b.golesLocal AS golesLocalResultado, 
b.golesVisitante AS golesVisitanteResultado, puntos_res, puntos_signo, puntos_goles, puntos_resta, puntos_total, fecha, penalizacion, cerrada
FROM tor_temporadas a, tor_jornadas b, tor_equipos c, tor_equipos d, tor_apuestas e, tor_usuarios f
WHERE a.idTemporada = ".$this->session->userdata("tor_idTemporada")." 
AND a.idTemporada = b.idTemporada
AND b.activa =1
AND b.idLocal = c.idEquipo
AND b.idVisitante = d.idEquipo
AND b.idJornada = e.idJornada
AND f.idUsuario = e.idUsuario
AND b.fecha >= '" . $fechaCierre . "'
and penalizacion = 0
ORDER BY if (fecha > now(), fecha, '2999-12-31') ,b.idJornada, puntos_total, e.golesLocal, e.golesVisitante, f.nombre
");
       
        } else {
            // GRABAR DATOS
//            echo "GRABAR $idTemporada<br>";
//            echo "<pre>";
//            print_r($_POST);
//            echo "</pre>";

            $numJornada = $this->input->post('numJornada');
            $activa = $this->input->post('activa');
            $numPartidos = $this->input->post('numPartidos');

            $aux = array();
            foreach ($_POST as $dato => $valor) {
                if ($dato == "numJornada" || $dato == "idTemporada" || $dato == "activa" || $dato == "numPartidos")
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
            $error = array();
            foreach ($aux as $pos => $datos) {
                // Si no existe idLocal o idVisitante insertar equipo
                if ($datos['idLocal'] == "" && $datos['nombreLocal'] != "") {
                    $datos_insert = array(
                        'nombre' => $datos['nombreLocal'],
                        'escudo' => "",
                        'escudoPeq' => ""
                    );
                    $this->db->insert('tor_equipos', $datos_insert);
                    //echo $this->db->set($datos_insert)->get_compiled_insert('tor_equipos')."<br>";

                    $id = $this->db->insert_id();
                    $aux[$pos]['idLocal'] = $id;
                    echo "ID=" . $aux[$pos]['idLocal'] . "<br>";
                    echo "INSERTAR EQUIPO LOCAL<br>";
                }
                if ($datos['idVisitante'] == "" && $datos['nombreVisitante'] != "") {
                    $datos_insert = array(
                        'nombre' => $datos['nombreVisitante'],
                        'escudo' => "",
                        'escudoPeq' => ""
                    );
                    $this->db->insert('tor_equipos', $datos_insert);
                    //echo $this->db->set($datos_insert)->get_compiled_insert('tor_equipos')."<br>";

                    $id = $this->db->insert_id();
                    $aux[$pos]['idVisitante'] = $id;
                    echo "ID=" . $aux[$pos]['idLocal'] . "<br>";

                    echo "INSERTAR EQUIPO VISITANTE<br>";
                }

                if ($datos['idLocal'] == $datos['idVisitante'])
                    $error[] = "Partido " . $pos . " con equipos iguales";
                if ($datos['fecha'] == "")
                    $error[] = "Partido " . $pos . " sin fecha";

                // comprobar formato fecha
                $fecha = $datos['fecha'];

                $fecha_arr = explode(' ', $fecha);
                if (count($fecha_arr) != 2)
                    $error[] = "Partido " . $pos . " tiene el formato de la fecha incorrecta";
                else {

                    $fecha = explode("/", $fecha_arr[0]);
                    if (count($fecha) != 3)
                        $error[] = "Partido " . $pos . " tiene el formato de la fecha incorrecta";
                    else {
                        $hora = explode(":", $fecha_arr[1]);
                        if (count($hora) != 2)
                            $error[] = "Partido " . $pos . " tiene el formato de la fecha incorrecta";
                    }
                }
            }

            $texto_error = "";
            if (count($error) > 0 && 1 == 2) {


                foreach ($error as $key => $value) {
                    $texto_error.="<p>" . $value . "</p>";
                }
                echo "hay error: $texto_error<br>";
            } else {
                //echo "NO hay error<br>";
                // Borrar activas previas
                if ($activa == 1) {
                    $this->db->where('activa', 1);
                    $this->db->set('activa', 0);
                    $this->db->update('tor_jornadas');
                }

                foreach ($aux as $pos => $datos) {
                    $fecha = $datos['fecha'];
                    $fecha_arr = explode(' ', $fecha);
                    $fecha = explode("-", $fecha_arr[0]);


                    $hora = explode(":", $fecha_arr[1]);

                    $fecha_grabar = $fecha[2] . "-" . $fecha[1] . "-" . $fecha[0] . " " . $hora[0] . ":" . $hora[1] . ":00";

                    $datos_insert = array('idTemporada' => $idTemporada,
                        'numJornada' => $numJornada,
                        'idLocal' => $datos['idLocal'],
                        'idVisitante' => $datos['idVisitante'],
                        'activa' => $activa,
                        'golesLocal' => 0,
                        'golesVisitante' => 0,
                        'signo' => '',
                        'fecha' => $fecha_grabar,
                        'idCompeticion' => 1);
                    $this->db->insert('tor_jornadas', $datos_insert);
                    //echo $this->db->get_compiled_insert()."<br>";
                    echo $this->db->last_query() . "<br>";
                }
            }
        }

        // equipos
        $eq = $this->db->query('select idEquipo,nombre from tor_equipos order by nombre');

/*
        $datos_salida['error'] = $texto_error;
        //$datos_salida['listaEquipos'] = $listaEquipos;
        $datos_salida['idTemporada'] = $idTemporada;
        $datos_salida['numJornada'] = $numJornada;
        $datos_salida['numPartidos'] = $numPartidos;
        $datos_salida['equipos'] = $eq;
*/
 $datos = array('partidos' => $rs,
            'partidos_jornada' => $rs_2,
            'numJornada' => $numJornada,
            'fechaCierre' => $fechaCierre,
     'numPartidos' => $numPartidos);

        $this->load->view('torpes/admin/apuesta_nueva', $datos);
    }

   

    function modificar_resultado()
    {
        $idApuesta = $this->input->post("idAp");
        $golesLocal = $this->input->post("golesL");
        $golesVisitante = $this->input->post("golesV");
        
        $this->db->where('idApuesta',$idApuesta);
        $this->db->set("golesLocal",$golesLocal);
        $this->db->set("golesVisitante",$golesVisitante);
        $this->db->update('tor_apuestas');
        
        $this->listado();
    }
}
