<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Jornada extends CI_Controller {

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
        $rs = $this->db->query("SELECT numJornada, e.idUsuario, f.nombre AS nombreUsuario, c.nombre AS
LOCAL , d.nombre AS visitante, e.golesLocal AS golesLocalApuesta, e.golesVisitante AS golesVisitanteApuesta, b.golesLocal AS golesLocalResultado, b.golesVisitante AS golesVisitanteResultado, puntos_res, puntos_signo, puntos_goles, puntos_resta, puntos_total
FROM tor_temporadas a, tor_jornadas b, tor_equipos c, tor_equipos d, tor_apuestas e, tor_usuarios f
WHERE a.activa =1
AND a.idTemporada = b.idTemporada
AND b.activa =1
AND b.idLocal = c.idEquipo
AND b.idVisitante = d.idEquipo
AND b.idJornada = e.idJornada
AND f.idUsuario = e.idUsuario
");
        $datos = array('partidos' => $rs);
        $this->load->view('torpes/inicio', $datos);
        //$this->session->unset_userdata('tor_usuario');
        return;
    }

    function clasificacion($numJornada = "") {
        if (!$this->session->userdata('tor_usuario')) {
            $datos = array();
            $this->load->view('torpes/login', $datos);
            return;
        }
        $this->load->model('Torpes_model');
        
        if ($numJornada == "") {
            
//            echo "<pre>";
            $datos_jornadas = $this->Torpes_model->jornada_activa($numJornada);
//            echo print_r($datos_jornadas);
//            echo "</pre>";
            $jornadas = $datos_jornadas['jornadas'];
//            echo "<pre>";
//            echo print_r($jornadas);
//            echo "</pre>";

            $numJornada = $datos_jornadas['numJornada'];
//            echo "JORNADA ACTIVA = ".$numJornada."<br>";

            $where_in = "";
            foreach ($jornadas as $key => $value) {
                if ($where_in == "")
                    $where_in = " (" . $key;
                else
                    $where_in.=", " . $key;
            }
            if ($where_in != "")
                $where_in.=") ";

            $rs = $this->db->query("SELECT b.nombre as nombreUsuario, numJornada,e.nombre as local, f.nombre as visitante, a.golesLocal as golesLocalApuesta, a.golesVisitante as golesVisitanteApuesta
,d.golesLocal as golesLocalResultado, d.golesVisitante as golesVisitanteResultado, puntos_res, puntos_signo, puntos_goles, puntos_resta, puntos_total, fecha, penalizacion,
puntos_dif_goles
FROM tor_apuestas a, tor_usuarios b, tor_jornadas d, tor_equipos e, tor_equipos f
where d.idJornada in " . $where_in . " 
and d.idTemporada  =".$this->session->userdata("tor_idTemporada")."
and d.idJornada = a.idJornada
and a.idUsuario=b.idUsuario
and d.idLocal = e.idEquipo
and d.idVisitante = f.idEquipo
order by b.nombre, d.idJornada");

            $rs2 = $this->db->query("SELECT b.nombre as nombreUsuario, sum(puntos_res) as puntos_res, sum(puntos_signo) as puntos_signo
                , sum(puntos_goles) as puntos_goles, sum(puntos_resta) as puntos_resta, sum(puntos_total) as puntos_total,
                sum(puntos_dif_goles) as puntos_dif_goles
FROM tor_apuestas a, tor_usuarios b, tor_jornadas d, tor_equipos e, tor_equipos f, tor_temporadas c 
where c.idTemporada = ".$this->session->userdata("tor_idTemporada")." and c.idTemporada=d.idTemporada and d.idJornada in " . $where_in . "  and d.idJornada = a.idJornada and a.idUsuario=b.idUsuario and d.idLocal = e.idEquipo and d.idVisitante = f.idEquipo 
group by b.nombre  order by sum(puntos_total) desc ");
 
            $jornadas = $this->Torpes_model->lista_jornadas2();

            //echo $this->db->last_query();
            $datos_salida = array('numJornada' => $numJornada,
                'partidos' => $rs,
                'partidos_resumen' => $rs2,
                'jornadas' => $jornadas);
            $this->load->view('torpes/clasificacion', $datos_salida);
        }
        else {

            $rs = $this->db->query("SELECT b.nombre as nombreUsuario, d.idJornada,numJornada,e.nombre as local, f.nombre as visitante, a.golesLocal as golesLocalApuesta, a.golesVisitante as golesVisitanteApuesta ,d.golesLocal as golesLocalResultado, d.golesVisitante as golesVisitanteResultado, puntos_res, puntos_signo, puntos_goles, puntos_resta, puntos_total, fecha, penalizacion,puntos_dif_goles 
                    FROM tor_apuestas a, tor_usuarios b, tor_jornadas d, tor_equipos e, tor_equipos f, tor_temporadas c where c.idTemporada=".$this->session->userdata("tor_idTemporada").""
                    . " and c.idTemporada=d.idTemporada and d.numJornada = " . $numJornada . " and d.idJornada = a.idJornada and a.idUsuario=b.idUsuario and d.idLocal = e.idEquipo and d.idVisitante = f.idEquipo order by b.nombre, d.idJornada ");

            $rs2 = $this->db->query("SELECT b.nombre as nombreUsuario, sum(puntos_res) as puntos_res, sum(puntos_signo) as puntos_signo
                , sum(puntos_goles) as puntos_goles, sum(puntos_resta) as puntos_resta, sum(puntos_total) as puntos_total,  sum(puntos_dif_goles) as puntos_dif_goles
FROM tor_apuestas a, tor_usuarios b, tor_jornadas d, tor_equipos e, tor_equipos f, tor_temporadas c 
where c.idTemporada=".$this->session->userdata("tor_idTemporada")." and c.idTemporada=d.idTemporada and d.numJornada = " . $numJornada . " and d.idJornada = a.idJornada and a.idUsuario=b.idUsuario and d.idLocal = e.idEquipo and d.idVisitante = f.idEquipo 
group by b.nombre  order by sum(puntos_total) desc ");
            //echo $this->db->last_query();

            $jornadas = $this->Torpes_model->lista_jornadas2();
            
             // fecha de cierre es la menor de las fechas de la jornada
            $rs_3 = $this->db->query("SELECT min(fecha) as fecha
                FROM tor_temporadas d, tor_jornadas a
                WHERE d.activa = 1
                and d.idTemporada=a.idTemporada
                and a.activa = 1
                ");
            $fila = $rs_3->row();
            $fechaCierre = $fila->fecha;
            
            $datos_salida = array('numJornada' => $numJornada,
                'partidos' => $rs,
                'partidos_resumen' => $rs2,
                'jornadas' => $jornadas,
                'fechaCierre' => $fechaCierre);
            $this->load->view('torpes/clasificacion', $datos_salida);
        }
    }

    function clasificacion_general() {
        if (!$this->session->userdata('tor_usuario')) {
            $datos = array();
            $this->load->view('torpes/login', $datos);
            return;
        }

        $rs2 = $this->db->query("SELECT b.nombre as nombreUsuario, sum(puntos_res) as puntos_res, sum(puntos_signo) as puntos_signo
                , sum(puntos_goles) as puntos_goles, sum(puntos_resta) as puntos_resta, sum(puntos_total) as puntos_total
FROM tor_apuestas a, tor_usuarios b, tor_jornadas d, tor_equipos e, tor_equipos f, tor_temporadas c 
where c.idTemporada =".$this->session->userdata("tor_idTemporada")." and c.idTemporada=d.idTemporada and d.idJornada = a.idJornada and a.idUsuario=b.idUsuario and d.idLocal = e.idEquipo and d.idVisitante = f.idEquipo 
group by b.nombre  order by sum(puntos_total) desc");
        //echo $this->db->last_query();
        $datos_salida = array(
            'clasificacion' => $rs2);
        
         // fecha de cierre es la menor de las fechas de la jornada
        $rs_3 = $this->db->query("SELECT min(fecha) as fecha
           FROM tor_temporadas d, tor_jornadas a
           WHERE d.activa = 1
           and d.idTemporada=a.idTemporada
           and a.activa = 1
           ");
        $fila = $rs_3->row();
        $fechaCierre = $fila->fecha;
        $datos_salida['fechaCierre'] = $fechaCierre;
        
        $this->load->view('torpes/clasificacion_general', $datos_salida);
    }

    function clasificacion_usuario($idUsuario = "") {
        if (!$this->session->userdata('tor_usuario')) {
            $datos = array();
            $this->load->view('torpes/login', $datos);
            return;
        }
        // lista de jornadas
        $listaJornadas = array();
        $listaUsuarios = array();
        $rs = $this->db->query("select distinct numJornada from tor_jornadas a, tor_temporadas b where b.idTemporada =".$this->session->userdata("tor_idTemporada")." and a.idTemporada=b.idTemporada");
        foreach ($rs->result() as $fila)
            $listaJornadas[$fila->numJornada] = $fila->numJornada;


        // lista de usuarios
        $rs = $this->db->query("SELECT DISTINCT d.idUsuario, nombre
FROM tor_jornadas a, tor_usuarios c, tor_temporadas b, tor_apuestas d
WHERE b.idTemporada = ".$this->session->userdata("tor_idTemporada")."
AND a.idTemporada = b.idTemporada
AND a.idJornada = d.idJornada
AND d.idUsuario = c.idUsuario");

     
        foreach ($rs->result() as $fila)
            $listaUsuarios[$fila->idUsuario] = $fila->nombre;





        if ($idUsuario == "") {
            $idUsuario = $this->session->userdata('tor_idUsuario');
        }
        /*            $rs=$this->db->query("SELECT b.nombre as nombreUsuario, numJornada,e.nombre as local, f.nombre as visitante, a.golesLocal as golesLocalApuesta, a.golesVisitante as golesVisitanteApuesta
          ,d.golesLocal as golesLocalResultado, d.golesVisitante as golesVisitanteResultado, puntos_res, puntos_signo, puntos_goles, puntos_resta, puntos_total, fecha
          FROM tor_apuestas a, tor_usuarios b, tor_jornadas d, tor_equipos e, tor_equipos f
          where d.idJornada in ".$where_in."
          and d.idJornada = a.idJornada
          and a.idUsuario=b.idUsuario
          and d.idLocal = e.idEquipo
          and d.idVisitante = f.idEquipo
          order by b.nombre, d.idJornada");
         */
        $rs2 = $this->db->query("SELECT b.nombre as nombreUsuario, d.numJornada, sum(puntos_res) as puntos_res, sum(puntos_signo) as puntos_signo
                , sum(puntos_goles) as puntos_goles, sum(puntos_resta) as puntos_resta, sum(puntos_total) as puntos_total
FROM tor_apuestas a, tor_usuarios b, tor_jornadas d, tor_equipos e, tor_equipos f, tor_temporadas c 
where c.idTemporada=".$this->session->userdata("tor_idTemporada")." and c.idTemporada=d.idTemporada and a.idUsuario= " . $idUsuario . "  and d.idJornada = a.idJornada and a.idUsuario=b.idUsuario and d.idLocal = e.idEquipo and d.idVisitante = f.idEquipo 
group by b.nombre,d.numJornada  order by d.numJornada desc");

                $rs2 = $this->db->query("select nombreUsuario, numJornada , sum(puntos_res) as puntos_res, sum(puntos_signo) as puntos_signo
                , sum(puntos_goles) as puntos_goles, sum(puntos_resta) as puntos_resta, sum(puntos_total) as puntos_total,
(select sum(b.puntos_total) from v_tor_consulta b where b.idUsuario=a.idUsuario and b.numJornada <= a.numJornada) as puntos_acumulados
from v_tor_consulta a
where idUsuario = ".$idUsuario." 
group by nombreUsuario, numJornada
order by nombreUsuario, numJornada desc");
                
        //echo $this->db->last_query();
        $datos_salida = array('idUsuario' => $idUsuario, 'nombreUsuario' => $listaUsuarios[$idUsuario],
            'listaUsuarios' => $listaUsuarios,
            'listaJornadas' => $listaJornadas,
            'partidos_resumen' => $rs2);
        
        
         // fecha de cierre es la menor de las fechas de la jornada
        $rs_3 = $this->db->query("SELECT min(fecha) as fecha
           FROM tor_temporadas d, tor_jornadas a
           WHERE d.activa = 1
           and d.idTemporada=a.idTemporada
           and a.activa = 1
           ");
        $fila = $rs_3->row();
        $fechaCierre = $fila->fecha;
        $datos_salida['fechaCierre'] = $fechaCierre;
        
        
        $this->load->view('torpes/clasificacion_usuario', $datos_salida);
        /*        }
          else
          {

          $rs=$this->db->query("SELECT b.nombre as nombreUsuario, d.idJornada,numJornada,e.nombre as local, f.nombre as visitante, a.golesLocal as golesLocalApuesta, a.golesVisitante as golesVisitanteApuesta ,d.golesLocal as golesLocalResultado, d.golesVisitante as golesVisitanteResultado, puntos_res, puntos_signo, puntos_goles, puntos_resta, puntos_total, fecha FROM tor_apuestas a, tor_usuarios b, tor_jornadas d, tor_equipos e, tor_equipos f, tor_temporadas c where c.activa = 1 and c.idTemporada=d.idTemporada and d.numJornada = ".$numJornada." and d.idJornada = a.idJornada and a.idUsuario=b.idUsuario and d.idLocal = e.idEquipo and d.idVisitante = f.idEquipo order by b.nombre, d.idJornada ");

          $rs2=$this->db->query("SELECT b.nombre as nombreUsuario, sum(puntos_res) as puntos_res, sum(puntos_signo) as puntos_signo
          , sum(puntos_goles) as puntos_goles, sum(puntos_resta) as puntos_resta, sum(puntos_total) as puntos_total
          FROM tor_apuestas a, tor_usuarios b, tor_jornadas d, tor_equipos e, tor_equipos f, tor_temporadas c
          where c.activa = 1 and c.idTemporada=d.idTemporada and d.numJornada = ".$numJornada." and d.idJornada = a.idJornada and a.idUsuario=b.idUsuario and d.idLocal = e.idEquipo and d.idVisitante = f.idEquipo
          group by b.nombre  order by sum(puntos_total) desc ");
          //echo $this->db->last_query();
          $datos_salida = array('numJornada' => $numJornada,
          'partidos' => $rs,
          'partidos_resumen' => $rs2);
          $this->load->view('torpes/clasificacion_usuario',$datos_salida);
          }
         * 
         */
    }

    
    function clasificacion_acumulada($idJornada="") {
        
        if (!$this->session->userdata('tor_usuario')) {
            $datos = array();
            $this->load->view('torpes/login', $datos);
            return;
        }
        
        $this->load->model('Torpes_model');
//      echo "<pre>";
        $datos_jornadas = $this->Torpes_model->lista_jornadas($idJornada);
        
        $datos_jornadas=$datos_jornadas['listaJornadas'];
        
        
        
        // lista de jornadas
        $listaJornadas = array();
        $listaUsuarios = array();
        if ($idJornada != "")
            $where = " where numJornada <= ".$idJornada." and numJornada >= ".($idJornada - 6)." ";
        else
            $where ="";
        
        $rs = $this->db->query("select nombreUsuario, numJornada , sum(puntos_total) as puntos_total,
(select sum(b.puntos_total) from v_tor_consulta b where b.idUsuario=a.idUsuario and b.numJornada <= a.numJornada) as puntos_acumulados
from v_tor_consulta a ".$where." 
group by nombreUsuario, numJornada
order by nombreUsuario,numJornada desc");
//(select sum(b.puntos_total) from v_tor_consulta b where b.idUsuario=a.idUsuario and b.numJornada <= a.numJornada) desc;
//");
        $tabla=array();
        foreach ($rs->result() as $fila)
        {
         //   $listaJornadas[$fila->numJornada] = $fila->numJornada;
         
            $tabla[$fila->nombreUsuario][$fila->numJornada] = array('puntosTotal' => $fila->puntos_total,
                'puntosAcumulados' =>$fila->puntos_acumulados);

        }

        // fecha de cierre es la menor de las fechas de la jornada
       $rs_3 = $this->db->query("SELECT min(fecha) as fecha
           FROM tor_temporadas d, tor_jornadas a
           WHERE d.activa = 1
           and d.idTemporada=a.idTemporada
           and a.activa = 1
           ");
       $fila = $rs_3->row();
       $fechaCierre = $fila->fecha;

        $datos_salida = array('jornadas' => $datos_jornadas, 
            'tabla' => $tabla,
            'fechaCierre' => $fechaCierre);
        $this->load->view('torpes/clasificacion_acumulada', $datos_salida);
        
    
    }
    
    function ganancias()
    {
        $this->load->model('Torpes_model');        
        $datos = $this->Torpes_model->ganancias_usuario();
        $datos['numJornada'] = "";
        
        // fecha de cierre es la menor de las fechas de la jornada
        $rs_3 = $this->db->query("SELECT min(fecha) as fecha
           FROM tor_temporadas d, tor_jornadas a
           WHERE d.activa = 1
           and d.idTemporada=a.idTemporada
           and a.activa = 1
           ");
        $fila = $rs_3->row();
        $fechaCierre = $fila->fecha;
        $datos['fechaCierre'] = $fechaCierre;
        
        $this->load->view('torpes/ganancias', $datos);
    }
}
