<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Estadisticas extends CI_Controller {

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
            $datos = array();
            $this->load->view('torpes/login', $datos);
            return;
        }


        // lista de usuarios
        $rs = $this->db->query("SELECT DISTINCT d.idUsuario, nombre
                FROM tor_jornadas a, tor_usuarios c, tor_temporadas b, tor_apuestas d
                WHERE b.activa =1
                AND a.idTemporada = b.idTemporada
                AND a.idJornada = d.idJornada
                AND d.idUsuario = c.idUsuario");

     
        foreach ($rs->result() as $fila)
            $listaUsuarios[$fila->idUsuario] = $fila->nombre;


        $idUsuario = $this->session->userdata('tor_idUsuario');

//var_dump($this->session->all_userdata()); die();

        $rs2 = $this->db->query(" select nombreUsuario, count(1) as numPartidos, sum(if(puntos_res = ".$this->session->userdata('tor_num_puntos_res').",1,0)) as aciertosResultado ,
                sum(if(puntos_signo = ".$this->session->userdata('tor_num_puntos_signo').",1,0)) as aciertosSigno,
                sum(if(golesLocalApuesta = golesLocalResultado,1,0)) as aciertosGolesLocal, sum(if(golesVisitanteApuesta = golesVisitanteResultado,1,0)) as aciertosGolesVisitante
                From v_tor_consulta_nueva where idTemporada=".$this->session->userdata('tor_idTemporada')."
                group by nombreUsuario order by nombreUsuario;");

        $rs3= $this->db->query(" select 'Real Oviedo' as equipo ,nombreUsuario, count(1) as numPartidos, 
        sum(if(puntos_res = ".$this->session->userdata('tor_num_puntos_res').",1,0)) as aciertosResultado ,
        sum(if(puntos_signo = ".$this->session->userdata('tor_num_puntos_signo').",1,0)) as aciertosSigno,
  sum(if(golesLocalApuesta = golesLocalResultado,1,0)) as aciertosGolesLocal, sum(if(golesVisitanteApuesta = golesVisitanteResultado,1,0)) as aciertosGolesVisitante
 From v_tor_consulta_nueva 
 where (equipoLocal = 'Real Oviedo' or equipoVisitante='Real Oviedo') and  idTemporada=".$this->session->userdata('tor_idTemporada')." 
 group by 'Real Oviedo', nombreUsuario
 union
 select 'Real Madrid' as equipo ,nombreUsuario, count(1) as numPartidos, sum(if(puntos_res = ".$this->session->userdata('tor_num_puntos_res').",1,0)) as aciertosResultado ,
 sum(if(puntos_signo = ".$this->session->userdata('tor_num_puntos_signo').",1,0)) as aciertosSigno
 ,sum(if(golesLocalApuesta = golesLocalResultado,1,0)) as aciertosGolesLocal, sum(if(golesVisitanteApuesta = golesVisitanteResultado,1,0)) as aciertosGolesVisitante
 From v_tor_consulta_nueva
 where (equipoLocal = 'Real Madrid' or equipoVisitante='Real Madrid') and  idTemporada=".$this->session->userdata('tor_idTemporada')."
 group by 'Real Madrid', nombreUsuario
 union
 select 'F.C. Barcelona' as equipo ,nombreUsuario, count(1) as numPartidos, sum(if(puntos_res = ".$this->session->userdata('tor_num_puntos_res').",1,0)) as aciertosResultado ,
 sum(if(puntos_signo = ".$this->session->userdata('tor_num_puntos_signo').",1,0)) as aciertosSigno
 ,sum(if(golesLocalApuesta = golesLocalResultado,1,0)) as aciertosGolesLocal, sum(if(golesVisitanteApuesta = golesVisitanteResultado,1,0)) as aciertosGolesVisitante
 From v_tor_consulta_nueva
  where (equipoLocal = 'F.C. Barcelona' or equipoVisitante='F.C. Barcelona') and  idTemporada=".$this->session->userdata('tor_idTemporada')."
 group by 'F.C. Barcelona', nombreUsuario
  union
 select 'Sporting de Gijón' as equipo ,nombreUsuario, count(1) as numPartidos, sum(if(puntos_res = ".$this->session->userdata('tor_num_puntos_res').",1,0)) as aciertosResultado ,
 sum(if(puntos_signo = ".$this->session->userdata('tor_num_puntos_signo').",1,0)) as aciertosSigno
 ,sum(if(golesLocalApuesta = golesLocalResultado,1,0)) as aciertosGolesLocal, sum(if(golesVisitanteApuesta = golesVisitanteResultado,1,0)) as aciertosGolesVisitante
 From v_tor_consulta_nueva
 where (equipoLocal = 'Sporting de Gijón' or equipoVisitante='Sporting de Gijón') and  idTemporada=".$this->session->userdata('tor_idTemporada')."
 group by 'Sporting de Gijón', nombreUsuario");
        
        
        // fecha de cierre es la menor de las fechas de la jornada
        $rs_3 = $this->db->query("SELECT min(fecha) as fecha
FROM tor_temporadas d, tor_jornadas a
WHERE d.activa = 1
and d.idTemporada=a.idTemporada
and a.activa = 1
");
        $fila = $rs_3->row();
        $fechaCierre = $fila->fecha;
        
        //echo $this->db->last_query();
        $datos_salida = array('idUsuario' => $idUsuario, 'nombreUsuario' => $listaUsuarios[$idUsuario],
            'listaUsuarios' => $listaUsuarios,
            'estadisticas_resumen' => $rs2,
            'estadisticas_equipo_usuario' => $rs3,
            'fechaCierre' => $fechaCierre);
        $this->load->view('torpes/estadisticas', $datos_salida);
    }

}
