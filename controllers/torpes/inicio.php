<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CI_Controller {

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
     * So any other public methods not prefixed with an underscore 
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index() {
        if (!$this->session->userdata('tor_usuario') ) {
            $datos = array();
            $this->load->view('torpes/login', $datos);
            return;
        }


        //$datos=array();
        $rs = $this->db->query("SELECT a.idJornada,d.numTemporada, a.numJornada, b.nombre as local, c.nombre as visitante, fecha
            FROM tor_temporadas d, tor_jornadas a, tor_equipos b, tor_equipos c
            WHERE d.idTemporada = ".$this->session->userdata('tor_idTemporada')."
            and d.idTemporada=a.idTemporada
            and a.activa = 1
            and a.idLocal = b.idEquipo
            and a.idVisitante = c.idEquipo
            ");

        if ($rs->num_rows() > 0)
        {
            $fila = $rs->row();
            $numJornada = $fila->numJornada;
         }
         else
            $numJornada ="";
        if ($numJornada == "")
            $numJornada = 1;

        // fecha de cierre es la menor de las fechas de la jornada
        $rs_3 = $this->db->query("SELECT min(fecha) as fecha
            FROM tor_temporadas d, tor_jornadas a
            WHERE d.idTemporada = ".$this->session->userdata('tor_idTemporada')."
            and d.idTemporada=a.idTemporada
            and a.activa = 1 and idLocal>0
            ");

        $fila = $rs_3->row();
        $fechaCierre = $fila->fecha;

        // Partidos por jugadores
        $rs_2 = $this->db->query("SELECT numJornada, e.idUsuario, f.nombre AS nombreUsuario, c.nombre AS
            LOCAL , d.nombre AS visitante, e.golesLocal AS golesLocalApuesta, e.golesVisitante AS golesVisitanteApuesta, b.golesLocal AS golesLocalResultado,
            b.golesVisitante AS golesVisitanteResultado, puntos_res, puntos_signo, puntos_goles, puntos_resta, puntos_total, fecha, penalizacion, cerrada,
            e.puntos_dif_goles, signo
            FROM tor_temporadas a, tor_jornadas b, tor_equipos c, tor_equipos d, tor_apuestas e, tor_usuarios f
            WHERE a.idTemporada = ".$this->session->userdata('tor_idTemporada')."
            AND a.idTemporada = b.idTemporada
            AND b.activa =1
            AND b.idLocal = c.idEquipo
            AND b.idVisitante = d.idEquipo
            AND b.idJornada = e.idJornada
            AND f.idUsuario = e.idUsuario
            AND b.fecha >= '" . $fechaCierre . "'
            and penalizacion = 0
            ORDER BY if (fecha > now(), fecha, '2999-12-31') ,b.idJornada, puntos_total desc, e.golesLocal, e.golesVisitante, f.nombre
            ");
//        echo $this->db->last_query();
//        die();

        $this->load->model("Torpes_model");
        $dibujar_apuestas=$this->Torpes_model->dibujar_apuestas($rs_2,$numJornada,$fechaCierre);

        $datos = array('partidos' => $rs,
            'partidos_jornada' => $rs_2,
            'numJornada' => $numJornada,
            'fechaCierre' => $fechaCierre,
        'apuestas' => $dibujar_apuestas);
            

        $this->load->view('torpes/inicio', $datos);
        //$this->session->unset_userdata('tor_usuario');
        return;
    }

    public function acceso() {
        $usr = $this->input->post('usuario');
        $pass = $this->input->post('password');

        if ($usr != '' && $pass != '') {
            $this->db->select('*');
            $this->db->from('tor_usuarios');
            $this->db->where('usuario', $usr);
            $this->db->where('password', $pass);
            $rs = $this->db->get();

            if ($rs->num_rows() == 1) {
		
                $mensaje_error = "OK";
                $fila = $rs->row();

                $this->session->set_userdata('tor_usuario', $usr);
                $this->session->set_userdata('tor_idUsuario', $fila->idUsuario);
      
                $this->session->set_userdata('tor_rol', $fila->rol);
      

                $rs = $this->db->query('select idTemporada, descripcion, numPartidos, 
										imp_tramo_1, imp_tramo_2, imp_tramo_3,  imp_tramo_4, 
										imp_jornada_1, imp_jornada_2, imp_jornada_3, imp_jornada_4, 
										imp_general_1, imp_general_2, imp_general_3, imp_general_4,
                                        numJornadas, puntos_dobles_tramo, num_tramos_jornadas, imp_maxima_puntuacion
										from tor_temporadas where activa = 1');
		
                $fila = $rs->row();
		
                // si no hay temporada activa pedir temporada
                if ($rs->num_rows() ==0 )
                {
                     $rs_usu = $this->db->query('select a.idTemporada, descripcion from 
						tor_usuarios_temporada a, 
						tor_temporadas b 
						where a.idTemporada=b.idTemporada and idUsuario = '.$this->session->userdata('tor_idUsuario'));

                    $datos = array('idUsuario' => $this->session->userdata('tor_idUsuario'),
                  'temporadas' => $rs_usu);
                    $this->load->view('torpes/cambiar_temporada', $datos);
                    return;
                }
                // localizar porra activa
                $rs_usu = $this->db->query('select a.idTemporada, descripcion from tor_usuarios_temporada a, tor_temporadas b where a.idTemporada=b.idTemporada and idUsuario = '.$this->session->userdata('tor_idUsuario'));

                // usuario en mas de una temporada
                if ($rs_usu->num_rows() > 1 && $rs->num_rows() > 1)
                {
                    $datos = array('idUsuario' => $this->session->userdata('tor_idUsuario'),
                  'temporadas' => $rs_usu);
                    $this->load->view('torpes/cambiar_temporada', $datos);
                    return;
                }

                $this->session->set_userdata('tor_idTemporada', $fila->idTemporada);
                $this->session->set_userdata('tor_descripcion', $fila->descripcion);
                $this->session->set_userdata('tor_numPartidos', $fila->numPartidos);
                
                $this->session->set_userdata('tor_imp_tramo_1', $fila->imp_tramo_1);
                $this->session->set_userdata('tor_imp_tramo_2', $fila->imp_tramo_2);
                $this->session->set_userdata('tor_imp_tramo_3', $fila->imp_tramo_3);
                $this->session->set_userdata('tor_imp_tramo_4', $fila->imp_tramo_4);
                
                $this->session->set_userdata('tor_imp_jornada_1', $fila->imp_jornada_1);
                $this->session->set_userdata('tor_imp_jornada_2', $fila->imp_jornada_2);
                $this->session->set_userdata('tor_imp_jornada_3', $fila->imp_jornada_3);
                $this->session->set_userdata('tor_imp_jornada_4', $fila->imp_jornada_4);
                
                $this->session->set_userdata('tor_imp_general_1', $fila->imp_general_1);
                $this->session->set_userdata('tor_imp_general_2', $fila->imp_general_2);
                $this->session->set_userdata('tor_imp_general_3', $fila->imp_general_3);
                $this->session->set_userdata('tor_imp_general_4', $fila->imp_general_4);
                
                $this->session->set_userdata('tor_numJornadas', $fila->numJornadas);
                $this->session->set_userdata('tor_puntos_dobles_tramo', $fila->puntos_dobles_tramo);
                $this->session->set_userdata('tor_num_tramos_jornadas', $fila->num_tramos_jornadas);
                $this->session->set_userdata('tor_imp_maxima_puntuacion', $fila->imp_maxima_puntuacion);

                // Lista de usuarios de la temporada
                $rsUsuarios = $this->db->query("select a.idUsuario, b.nombre  
                                                from tor_usuarios_temporada a, tor_usuarios b
                                                where idTemporada = ".$fila->idTemporada."  and a.idUsuario=b.idUsuario and b.activo = 1");
                $listaUsuarios = array();
                foreach ($rsUsuarios->result() as $fila)
                {
                    $listaUsuarios[$fila->idUsuario]=$fila->nombre;
                }
                $this->session->set_userdata('tor_lista_usuarios', $listaUsuarios);                                                                

                if ($usr == $pass) {
                    $this->load->view('torpes/cambiar_password');
                    return;
                }
                log_message('error', '[inicio/acceso] Login correcto usuario: ' . $usr);
            } else {
                log_message('error', '[inicio/acceso] Login incorrecto usuario: ' . $usr);
                $mensaje_error = "Datos incorrectos";
            }
        } else
            $mensaje_error = "Datos incorrectos";



        $datos = array(
            'mensaje_error' => $mensaje_error
        );
        //echo "ERROR: " . $mensaje_error;
        redirect("torpes/inicio");
    }

    function apuesta() {
        //var_dump($_POST);
        $aux = array();
        foreach ($_POST as $dato => $valor) {
            //  echo "$dato - $valor<br>";
            $a = explode("_", $dato);
            $campo = $a[0];
            $id_jornada = $a[1];
            $aux[$id_jornada][$campo] = $valor;
        }
        //var_dump($aux);

        foreach ($aux as $idJornada => $datos) {
            $data = array('idJornada' => $idJornada,
                'idUsuario' => $this->session->userdata("tor_idUsuario"));

            foreach ($datos as $campo => $valor) {
                $data[$campo] = $valor;
            }
            //var_dump($data);
            $rs = $this->db->query("select * from tor_apuestas where idJornada=" . $idJornada . " and idUsuario=" . $this->session->userdata("tor_idUsuario"));

            if ($rs->num_rows() == 0) {
                //echo "NO HAY<br>";
                $this->db->insert('tor_apuestas', $data);

//                    echo $this->db->last_query();
            } else {
                $this->db->where('idUsuario', $this->session->userdata("tor_idUsuario"));
                $this->db->where('idJornada', $idJornada);
                $this->db->update('tor_apuestas', $data);
                //echo "HAY<br>";
            }
            log_message('error', '[inicio/apuesta] SQL : ' . $this->db->last_query());
        }
        redirect("torpes/inicio");
    }

    public function cambio() {
        $pass = $this->input->post('password');


        if ($pass != '') {
            $this->db->where('idUsuario', $this->session->userdata('tor_idUsuario'));
            $this->db->set('password', $pass);
            $this->db->update('tor_usuarios');
        }
        redirect("torpes/inicio");
    }


function cambio_temporada(){

	  $rs = $this->db->query('select idTemporada, descripcion, numPartidos, 
										imp_tramo_1, imp_tramo_2, imp_tramo_3,  imp_tramo_4, 
										imp_jornada_1, imp_jornada_2, imp_jornada_3, imp_jornada_4, 
										imp_general_1, imp_general_2, imp_general_3, imp_general_4,
                                        numJornadas, puntos_dobles_tramo, num_tramos_jornadas, imp_maxima_puntuacion
							from tor_temporadas where idTemporada = '.$this->input->post('idTemporada'));
	  $fila = $rs->row();



                $this->session->set_userdata('tor_idTemporada', $fila->idTemporada);
                $this->session->set_userdata('tor_descripcion', $fila->descripcion);
                $this->session->set_userdata('tor_numPartidos', $fila->numPartidos);
                
                $this->session->set_userdata('tor_imp_tramo_1', $fila->imp_tramo_1);
                $this->session->set_userdata('tor_imp_tramo_2', $fila->imp_tramo_2);
                $this->session->set_userdata('tor_imp_tramo_3', $fila->imp_tramo_3);
                $this->session->set_userdata('tor_imp_tramo_4', $fila->imp_tramo_4);
                
                $this->session->set_userdata('tor_imp_jornada_1', $fila->imp_jornada_1);
                $this->session->set_userdata('tor_imp_jornada_2', $fila->imp_jornada_2);
                $this->session->set_userdata('tor_imp_jornada_3', $fila->imp_jornada_3);
                $this->session->set_userdata('tor_imp_jornada_4', $fila->imp_jornada_4);
                
                $this->session->set_userdata('tor_imp_general_1', $fila->imp_general_1);
                $this->session->set_userdata('tor_imp_general_2', $fila->imp_general_2);
                $this->session->set_userdata('tor_imp_general_3', $fila->imp_general_3);
                $this->session->set_userdata('tor_imp_general_4', $fila->imp_general_4);
                
                $this->session->set_userdata('tor_numJornadas', $fila->numJornadas);
                $this->session->set_userdata('tor_puntos_dobles_tramo', $fila->puntos_dobles_tramo);
                $this->session->set_userdata('tor_num_tramos_jornadas', $fila->num_tramos_jornadas);
                $this->session->set_userdata('tor_imp_maxima_puntuacion', $fila->imp_maxima_puntuacion);


  redirect("torpes/inicio");

}


    function cerrar()
    {
        if (!$this->session->userdata('tor_usuario')) {
                $datos = array();
            $this->load->view('torpes/login', $datos);
            return;
        }

        $this->load->model("Torpes_model");
        $datos=$this->Torpes_model->jornada_activa();
        $numJornada=$datos["numJornada"];
        $jornadas = $datos["jornadas"];

        

        $rs = $this->db->query('SELECT count(1) as total
        FROM `v_tor_consulta_nueva`
        WHERE idUsuario ='.$this->session->userdata('tor_idUsuario').' AND
            idTemporada = '.$this->session->userdata('tor_idTemporada').'
        AND jornadaActiva =1
        and golesLocalApuesta <> -1
        and golesVisitanteApuesta<> -1');
        $fila=$rs->row();

        
        // si no estan los 4 partidos metidos salir
        $numPartidos = $this->session->userdata('tor_numPartidos');
        if ($this->session->userdata("tor_idTemporada") == 3 && $numJornada != 1)
            $numPartidos=4;
        
        if ($fila->total != $numPartidos)
                redirect("torpes/inicio");


        foreach ($jornadas as $idJornada => $value)
        {
            //echo "update tor_apuestas set cerrada=1 where idJornada=".$idJornada." and idUsuario=".$this->session->userdata('tor_idUsuario')."<br>";
            $this->db->where('idUsuario',$this->session->userdata('tor_idUsuario'));
            $this->db->where('idJornada',$idJornada);
            $this->db->set('cerrada',1);
        $this->db->update('tor_apuestas');
        }


        redirect("torpes/inicio");
    }

	// Formulario de invitacion de usuario
	function nuevo($id = "")
	{
	$id=base64_decode(str_pad(strtr($id, '-_', '+/'), strlen($id) % 4, '=', STR_PAD_RIGHT));


		if ($id == "")
			redirect("torpes/inicio");

		$cadena = base64_encode("1;1;soycares2@gmail.com");

		$cadena=$id;
		$cadena = base64_decode($cadena);

		$arr= explode(";",$cadena);



		$idTemporada =$arr[1];

		$email=$arr[2];

		$rs=$this->db->query("select descripcion from tor_temporadas where idTemporada=$idTemporada");
		$fila =$rs->row();
		$descripcion=$fila->descripcion;

		$datos = array('idTemporada' => $idTemporada,
		'descripcion' => $descripcion,
		'email' => $email,
		'error' => "");


		$this->load->view('torpes/invitacion', $datos);
	}

	// crear usuario a partir de una invitacion
	function crear()
	{

		// crear usuario
		$datos = array('usuario' => $this->input->post("usuario"),
			'password' => $this->input->post("password"),
			'nombre' => $this->input->post("nombre"),
			'email' => $this->input->post("email"),
			'rol' => 0,
			'activo' => 1);


		// Comprobar si el email existe
		$rs = $this->db->query("select email,usuario from tor_usuarios where email='".$this->input->post("email")."' or usuario='".$this->input->post("usuario")."'");

		if ($rs->num_rows() == 0)
		{

			$this->db->insert('tor_usuarios',$datos);
			$idUsuario = $this->db->insert_id();

			$datos = array('idTemporada' => $this->input->post("idTemporada"),
				'idUsuario' => $idUsuario);


			$this->db->insert('tor_usuarios_temporada',$datos);

			echo $this->db->last_query();

			$error ="";
		}
		else
		{

			$fila=$rs->row();
			if ($fila->email == $this->input->post("email"))
				$error="Error: Email ya existente";
			if ($fila->usuario == $this->input->post("usuario"))
				$error="Error: Usuario ya existente";
		}

		$datos = array('idTemporada' => $this->input->post("idTemporada"),
		'descripcion' => $this->input->post("descripcion"),
		'email' => $this->input->post("email"),
		'error' => $error);

		if ($error == "")
			redirect("torpes/inicio");
		else
			$this->load->view('torpes/invitacion', $datos);
}
}
