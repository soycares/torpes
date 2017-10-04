<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Temporadas extends CI_Controller {

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

        }


        //$datos=array();
        $rs= $this->db->query("SELECT * from tor_temporadas");

        $datos = array('temporadas' => $rs);

        $this->load->view('torpes/admin/temporadas',$datos);
        //$this->session->unset_userdata('tor_usuario');
    }

    function editar($id)
    {
        if (!$this->session->userdata('tor_usuario')) {
            //echo $this->input->post('usuario');

            $datos = array();
            $this->load->view('torpes/login', $datos);
            return;

        }
        $error="";

        //$datos=array();
        $rs= $this->db->query("SELECT * from tor_temporadas where idTemporada=".$id);
        $fila = $rs->row();

        $datos = array('descripcion' => $fila->descripcion,
            'idTemporada' => $fila->idTemporada,
            'numTemporada' => $fila->numTemporada,
            'numJornadas' => $fila->numJornadas,
            'numPartidos' => $fila->numPartidos,
            'activa' => $fila->activa,
            'error' => $error,
            'num_puntos_res' => $fila->num_puntos_res,
            'num_puntos_signo' => $fila->num_puntos_signo,
            'num_puntos_goles' => $fila->num_puntos_goles,
            'num_puntos_resta' => $fila->num_puntos_resta,

            'imp_jornada' => $fila->imp_jornada,
            'num_tramos' => $fila->num_tramos,
            'num_tramos_jornadas' => $fila->num_tramos_jornadas,
            'imp_general_1' => $fila->imp_general_1,
            'imp_general_2' => $fila->imp_general_2,
            'imp_general_3' => $fila->imp_general_3,
            'imp_general_4' => $fila->imp_general_4,
            'imp_tramo_1' => $fila->imp_tramo_1,
            'imp_tramo_2' => $fila->imp_tramo_2,
            'imp_tramo_3' => $fila->imp_tramo_3,
            'imp_tramo_4' => $fila->imp_tramo_4,
            'imp_jornada_1' => $fila->imp_jornada_1,
            'imp_jornada_2' => $fila->imp_jornada_2,
            'imp_jornada_3' => $fila->imp_jornada_3,
            'imp_jornada_4' => $fila->imp_jornada_4,
            'diferencia_puntos' => $fila->diferencia_puntos,
            
            'maxima_puntuacion' => $fila->maxima_puntuacion,
            'imp_maxima_puntuacion' => $fila->imp_maxima_puntuacion,
            'puntos_dobles_tramo' => $fila->puntos_dobles_tramo,

            'aciertos_equipos' => $fila-> aciertos_equipos,
            'imp_aciertos_equipos_1' => $fila-> imp_aciertos_equipos_1,
            'imp_aciertos_equipos_2' => $fila-> imp_aciertos_equipos_2,
            'imp_aciertos_equipos_3' => $fila-> imp_aciertos_equipos_3

            );

        $this->load->view('torpes/admin/temporadas_modificar',$datos);
    }

    function grabar()
    {

        //var_dump($_POST);
        $datos= array(
            'descripcion' => $this->input->post("descripcion"),
            'numTemporada' => $this->input->post("numTemporada"),
            'numJornadas' => $this->input->post("numJornadas"),
            'numPartidos' => $this->input->post("numPartidos"),
            'activa' => $this->input->post("activa"),
            'num_puntos_res' => $this->input->post("num_puntos_res"),
            'num_puntos_signo' => $this->input->post("num_puntos_signo"),
            'num_puntos_goles' => $this->input->post("num_puntos_goles"),
            'num_puntos_resta' => $this->input->post("num_puntos_resta"),

            'imp_jornada' => $this->input->post("imp_jornada"),
            'num_tramos' => $this->input->post("num_tramos"),
            'num_tramos_jornadas' => $this->input->post("num_tramos_jornadas"),
            'imp_general_1' => $this->input->post("imp_general_1"),
            'imp_general_2' => $this->input->post("imp_general_2"),
            'imp_general_3' => $this->input->post("imp_general_3"),
            'imp_general_4' => $this->input->post("imp_general_4"),
            'imp_tramo_1' => $this->input->post("imp_tramo_1"),
            'imp_tramo_2' => $this->input->post("imp_tramo_2"),
            'imp_tramo_3' => $this->input->post("imp_tramo_3"),
            'imp_tramo_4' => $this->input->post("imp_tramo_4"),
            'imp_jornada_1' => $this->input->post("imp_jornada_1"),
            'imp_jornada_2' => $this->input->post("imp_jornada_2"),
            'imp_jornada_3' => $this->input->post("imp_jornada_3"),
            'imp_jornada_4' => $this->input->post("imp_jornada_4"),
             'diferencia_puntos' => $this->input->post("diferencia_puntos"),
             'maxima_puntuacion' => $this->input->post("maxima_puntuacion"),
             'imp_maxima_puntuacion' => $this->input->post("imp_maxima_puntuacion"),
             'puntos_dobles_tramo' => $this->input->post("puntos_dobles_tramo"),

            'aciertos_equipos' => $this->input->post("aciertos_equipos"),
            'imp_aciertos_equipos_1' => $this->input->post("imp_aciertos_equipos_1"),
            'imp_aciertos_equipos_2' => $this->input->post("imp_aciertos_equipos_2"),
            'imp_aciertos_equipos_3' => $this->input->post("imp_aciertos_equipos_3")
        );

        // poner resto de temporadas como no activas si esta es activa
        if ($this->input->post("activa") == 1)
        {
            $this->db->set('activa', 0);
            $this->db->update('tor_temporadas');
            $this->session->set_userdata('idTemporada', $this->input->post('idTemporada'));
            $this->session->set_userdata('tor_descripcion',$this->input->post('descripcion'));
            //$sql = $this->db->set($datos)->get_compiled_update('tor_temporadas');

        }
        //echo $this->db->last_query()."<br>";
        if ($this->input->post("idTemporada") == "") {
            // NUEVO
            $this->db->insert('tor_temporadas', $datos);
            //$sql = $this->db->set($datos)->get_compiled_insert('tor_temporadas');
            //echo $sql;

        }
        else {
            // EDITAR
            $this->db->where('idTemporada', $this->input->post('idTemporada'));
            $this->db->update('tor_temporadas',$datos);
//            $sql = $this->db->set($datos)->get_compiled_update('tor_temporadas');
//            echo $sql;

        }

        //echo $this->db->last_query()."<br>";
         redirect("torpes/admin/temporadas");


    }


    function nuevo()
    {
        if (!$this->session->userdata('tor_usuario')) {
            //echo $this->input->post('usuario');

            $datos = array();
            $this->load->view('torpes/login', $datos);
            return;

        }
        $error="";

        $datos = array('error' => $error);

        $this->load->view('torpes/admin/temporadas_nueva',$datos);
    }

    function usuarios($idTemporada)
    {
        if (!$this->session->userdata('tor_usuario')) {
            //echo $this->input->post('usuario');

            $datos = array();
            $this->load->view('torpes/login', $datos);
            return;

        }
        $error="";

        $rs=$this->db->query("select descripcion from tor_temporadas where idTemporada=".$idTemporada);
        $fila = $rs->row();
        $descripcion = $fila->descripcion;

        $rs = $this->db->query("select * from tor_usuarios a where activo = 1 and not exists (select 1 from tor_usuarios_temporada b where b.idTemporada=".$idTemporada." and a.idUsuario=b.idUsuario)");

        $rs2 = $this->db->query("select idUsuario,usuario, nombre from tor_usuarios a where activo = 1 and exists (select 1 from tor_usuarios_temporada b where b.idTemporada=".$idTemporada." and a.idUsuario=b.idUsuario)");

        $datos = array('error' => $error,
            'usuarios_no' => $rs,
            'usuarios_si' => $rs2,
            'descripcion' => $descripcion ,
            'idTemporada' => $idTemporada);

        $this->load->view('torpes/admin/temporadas_usuarios',$datos);

    }

    function incluir($idTemporada = "", $idUsuario ="")
    {
        if (!$this->session->userdata('tor_usuario')) {
            //echo $this->input->post('usuario');

            $datos = array();
            $this->load->view('torpes/login', $datos);
            return;

        }

        $datos = array('idUsuario' => $idUsuario,
            'idTemporada' => $idTemporada);

        $this->db->insert("tor_usuarios_temporada", $datos);

        redirect("torpes/admin/temporadas/usuarios/".$idTemporada);
    }

    function quitar($idTemporada = "", $idUsuario ="")
    {
        if (!$this->session->userdata('tor_usuario')) {
            //echo $this->input->post('usuario');

            $datos = array();
            $this->load->view('torpes/login', $datos);
            return;

        }

        $datos = array('idUsuario' => $idUsuario,
            'idTemporada' => $idTemporada);

        $this->db->where('idTemporada', $idTemporada);
        $this->db->where('idUsuario', $idUsuario);
        $this->db->delete("tor_usuarios_temporada");

        redirect("torpes/admin/temporadas/usuarios/".$idTemporada);
    }

	// invitara usuarios a la temporada
	function invitar($idTemporada="",$idUsuario="")
	{


	/*	if (!$this->session->userdata('tor_usuario')) {
            $datos = array();
            $this->load->view('torpes/login', $datos);
            return;
        }
*/
		if ($idUsuario != "")
		{
			// si se manda usuario, inicializar datos
	        $rs= $this->db->query("SELECT * from tor_usuarios where idUsuario=".$idUsuario);
			$fila = $rs->row();

			$email = $fila->email;
		}
		else
		{
			$email="";
		}

		$rs_temporadas= $this->db->query("SELECT * from tor_temporadas where activa=1");
        $error = "";
		$datos = array('email' => $email,
		'idUsuario' => $idUsuario,
		'temporadas' => $rs_temporadas,
		'error' => $error);

		$this->load->view('torpes/invitacion',$datos);

	}

	function enviar()
	{

	echo "<pre>";
	print_r($_POST);
	echo "</pre>";
		if (!$this->session->userdata('tor_usuario')) {
            $datos = array();
            $this->load->view('torpes/login', $datos);
            return;
        }

		if ($this->input->post('idUsuario') == "")
			$idUsuario = 0;
		else
			$idUsuario = $this->input->post('idUsuario');

		$idTemporada =$this->input->post('idTemporada');
		$email = $this->input->post('email');
		echo $idUsuario.";".$idTemporada.";".$email."<br>";

		$cadena = base64_encode($idUsuario.";".$idTemporada.";".$email);

    // Quitar == de la cadena para evitar errores en la url
    $cadena = rtrim(strtr(base64_encode($cadena), '+/', '-_'), '=');;

		echo $cadena."<br><br>";
		$url = "http://www.oviedin.com/ci_ov/torpes/inicio/nuevo/".$cadena;

		echo $url."<br>";

		$url = '<a href="'.$url.'">Alta de usuario</a>';
		echo 'Hola, <br>Puedes darte de alta como usuario de la Porra Los Torpes en el siguiente enlace:<br><br>'.$url."<br><br>Saludos";

		$this->load->library('email');

		$this->email->from('soycares@gmail.com', 'Porra los Torpes');
		$this->email->to($email);

		$this->email->subject('Invitacion a la Porra Los Torpes');
		$this->email->message('Hola, <br>Puedes darte de alta como usuario de la Porra Los Torpes en el siguiente enlace:<br><br>'.$url."<br><br>Saludos");

		$this->email->send();




	}
}
