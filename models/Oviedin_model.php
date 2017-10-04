<?php

class Oviedin_model extends CI_Model {

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

    /**
     * Muestra la plantilla p�blica
     *
     * @param 	string	Archivo de contenido a cargar
     * @param	array	Datos que se pasar�n a la vista
     * @return 	void
     */
    function display_template($template, $data, $tipo = 0) {
        // Carga el contenido


        $data['template'] = $template;
        $data['body'] = $this->load->view($template, $data, true);
		$cfg = $data["conf"];
		
        // Real Oviedo
        if ($tipo == 0) {
			$proxima_jornada ="";
			$clasificaciones = "";
			$goleadores = "";
			$porra = "";
            //proxima jornada
			if ($cfg->proximo_partido==1)
            	$proxima_jornada = $this->Oviedin_model->proximo_partido($data["conf"]);
            $data["proximo_partido"] = $proxima_jornada;

            // clasificacion
			if ($cfg->clasificacion==1)
            	$clasificaciones = $this->Oviedin_model->clasificacion_plantilla($data["conf"]);
            $data['clasificaciones'] = $clasificaciones;

            // goleadores
			if ($cfg->goleadores==1)
            	$goleadores = $this->Oviedin_model->clasificacion_goles($data["conf"]);
            $data['goleadores'] = $goleadores;

            // porra
			if ($cfg->porra==1)
            	$porra = $this->Oviedin_model->clasificacion_porra($data['conf']);
            $data['porra'] = $porra;

            // Nombre de la plantilla principal
            $layout_file = "plantilla_oviedo";
        }

        // Filial
        if ($tipo == 1) {
            //proxima jornada (del primer equipo)
            $proxima_jornada = $this->Oviedin_model->proximo_partido_filial($data["conf"]);
            $data["proximo_partido"] = $proxima_jornada;

            // clasificacion
            $clasificaciones = $this->Oviedin_model->clasificacion_plantilla_filial($data["conf"]);
            $data['clasificaciones'] = $clasificaciones;

             // goleadores
            $goleadores = $this->Oviedin_model->clasificacion_goles_filial($data["conf"]);
            $data['goleadores'] = $goleadores;




            // Nombre de la plantilla principal
            $layout_file = "plantilla_filial";
        }

        // Juvenil
        if ($tipo == 2) {
			// Proximo Partido
			$proxima_jornada = $this->Oviedin_model->proximo_partido_juvenil($data["conf"]);
            $data["proximo_partido"] = $proxima_jornada;

            // clasificacion
            $clasificaciones = $this->Oviedin_model->clasificacion_plantilla_juvenil($data["conf"]);
            $data['clasificaciones'] = $clasificaciones;

            // Nombre de la plantilla principal
            $layout_file = "plantilla_juvenil";
        }
		
		// Categorias inferiores
		if ($tipo == 3) {
			//proxima jornada
            $proxima_jornada = $this->Oviedin_model->proximo_partido($data["conf"]);
            $data["proximo_partido"] = $proxima_jornada;

            // clasificacion
            $clasificaciones = $this->Oviedin_model->clasificacion_plantilla($data["conf"]);
            $data['clasificaciones'] = $clasificaciones;

            // Nombre de la plantilla principal
            $layout_file = "plantilla_juvenil";
        }
        $this->load->view($layout_file, $data);
    }

	function display_admin_template($template, $data, $js="", $datatables=0) {
        // Carga el contenido


        $data['template'] = $template;
        $data['body'] = $this->load->view($template, $data, true);
		    $data['js'] = $js;
        $data['datatables'] = $datatables;

        $layout_file = "plantilla_admin";

        $this->load->view($layout_file, $data);
    }

	function display_admin_template_nuevo($template, $data) {
        // Carga el contenido

        $data['template'] = $template;
		// INI NUEVO

		// FIN NUEVO

        $data['body'] = $this->load->view($template, $data, true);

        $layout_file = "plantilla_admin";

        $this->load->view($layout_file, $data);
    }


function display_template_directo($template, $data, $js="", $datatables=0) {
        // Carga el contenido


        $data['template'] = $template;
        $data['body'] = $this->load->view($template, $data, true);
		$data['js'] = $js;
        $data['datatables'] = $datatables;

        $layout_file = "plantilla_directo";

        $this->load->view($layout_file, $data);
    }

    function proximo_partido($conf) {
        $salida = "";
		
        if ($conf->proximo_partido == 1) {
            $rs_proximo_partido = $this->db->query("SELECT jornada,anio,idLocal,idVisitante  FROM ov_jornadas WHERE anio=" . $this->session->userdata("idTemporada") . " and activa=1 order by anio desc");
            // No hay proximo partido definido
            if ($rs_proximo_partido->num_rows() == 0)
                return;
            $fila = $rs_proximo_partido->row();
            if ($fila->idLocal == 1)
                $contrario = " idVisitante=" . $fila->idVisitante;
            else
                $contrario = " idLocal=" . $fila->idLocal;
            $local = $fila->idLocal;
            $visitante = $fila->idVisitante;

            $sqlquery_partido = "SELECT a.jornada,a.anio,a.idLocal,a.idVisitante,a.local,a.visitante,b.escudo as escudoLocal, c.escudo as escudoVisitante, b.campo, d.fecha ";
            $sqlquery_partido.="FROM ov_jornadas a, ov_equipos b, ov_equipos c, po_jornadas d WHERE a.activa=1 and a.anio=" . $this->session->userdata("idTemporada") . " and " . $contrario . " and a.idLocal=b.idEquipo and a.idVisitante=c.idEquipo and d.idJornada=a.jornada and d.idTemporada=a.anio order by anio desc";

            $rs_proximo_partido = $this->db->query($sqlquery_partido);


            // dibujar html
            $salida = "";
            $fila = $rs_proximo_partido->row();
            $salida.='<div class="heading-title heading-dotted text-center">
					<h4>
					Próximo
					<span>Partido</span>
					</h4>
				</div>
				<table class="table">
					<tbody>
						<tr>
							<td><img src="http://www.oviedin.com/' . $fila->escudoLocal . '"></td>
							<td><h3>' . $fila->local . '</h3></td>
						</tr>
						<tr class="total">
							<td><img src="http://www.oviedin.com/' . $fila->escudoVisitante . '"></td>
							<th ><h3>' . $fila->visitante . '</h3></th>
						</tr>
						<tr>
							<td colspan="2" style="background:#fafafa"><center>';
            if ($fila->idLocal == 1)
                $salida.= "Estadio Carlos Tartiere";
            else
                $salida.= $fila->campo;
            $salida.='</center></td>
						</tr>
						<tr>
							<td colspan="2">
								<center>';
            $dias = array("DOMINGO", "LUNES", "MARTES", "MIERCOLES", "JUEVES", "VIERNES", "SABADO");
            $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

            $faux = explode(" ", $fila->fecha);
            $faux = explode("-", $faux[0]);
            $fecha = mktime(0, 0, 0, $faux[1], $faux[2], $faux[0]);
            $dia = date("w", $fecha);
            $mes = date("m", $fecha);
            $mes = $mes - 1;
            $dia_num = date("j", $fecha);
            $dia_semana = $dias[$dia];

            $salida.=strtolower($dias[$dia]) . " " . $dia_num . " de " . strtolower($meses[$mes]) . " de " . $faux[0];
            $salida.='		</center>
							</td>
						</tr>
						</tbody>
					</table>
					<div class="divider margin-top-5 margin-bottom-5"></div>';
        }

        return $salida;
    }

	function proximo_partido_filial($conf) {
        $salida = "";
		$num_partidos_filial=10;
        if ($conf->proximo_partido == 1) 
		{
			// buscar ultima jornada con partidos finalizados
			$rs=$this->db->query("select count(1), idJornada From ov_partidos_liga_filial
								where idTemporada=" . $this->session->userdata('idTemporada') . "
								and finalizado=1
								group by idJornada
								order by idJornada desc");

			if ($rs->num_rows() == $num_partidos_filial)
			{
				// si todos los partidos estan finalizados, jornada actual es la jornada siguiente
				$fila = $rs->row();
				$idJornada = $fila->idJornada + 1;
			}
			else{
				// si no hay partidos, jornada actual la primera
				if ($rs->num_rows() == 0)
					$idJornada = 1;
				else{
					// si aun no estan finalizados todos, jornada devuelta por la conslta
					$fila = $rs->row();
					$idJornada = $fila->idJornada;
				}
			}
			
			$sqlquery_partido = "SELECT a.idTemporada,a.idJornada,a.idLocal,a.idVisitante,b.Nombre as local ,c.Nombre as visitante,
								b.escudo as escudoLocal, c.escudo as escudoVisitante, b.campo  as campoLocal, c.campo  as campoVisitante ";
            $sqlquery_partido.="FROM ov_partidos_liga_filial a, ov_equipos b, ov_equipos c 
								WHERE a.idJornada=".$idJornada." and a.idTemporada=" . $this->session->userdata("idTemporada") . " and (a.idLocal = 60 or a.idVisitante=60) and a.idLocal=b.idEquipo and a.idVisitante=c.idEquipo ";
		
			$rs_proximo_partido = $this->db->query($sqlquery_partido);
			if ($rs_proximo_partido->num_rows() == 0)
				return "";
			else
			{
				$fila = $rs_proximo_partido->row();
				$salida.='<div class="heading-title heading-dotted text-center">
						<h4>
						Próximo
						<span>Partido</span>
						</h4>
					</div>
					<table class="table">
						<tbody>
							<tr>
								<td><img src="http://www.oviedin.com/' . $fila->escudoLocal . '"></td>
								<td><h3>' . $fila->local . '</h3></td>
							</tr>
							<tr class="total">
								<td><img src="http://www.oviedin.com/' . $fila->escudoVisitante . '"></td>
								<th ><h3>' . $fila->visitante . '</h3></th>
							</tr>';
				if ( $fila->campoLocal != "")
					$salida.='<tr><td colspan="2" style="background:#fafafa"><center>'. $fila->campoLocal.'</center></td></tr>';
				
				$salida.='
							</tbody>
						</table>
						<div class="divider margin-top-5 margin-bottom-5"></div>';
			}
			return $salida;
		}
	}
	
	function proximo_partido_juvenil($conf) {
        $salida = "";
		$num_partidos_filial=10;
        if ($conf->proximo_partido == 1) 
		{
			// buscar ultima jornada con partidos finalizados
			$rs=$this->db->query("select count(1), idJornada From ov_partidos_liga_juvenil
								where idTemporada=" . $this->session->userdata('idTemporada') . "
								and finalizado=1
								group by idJornada
								order by idJornada desc");

			if ($rs->num_rows() == $num_partidos_filial)
			{
				// si todos los partidos estan finalizados, jornada actual es la jornada siguiente
				$fila = $rs->row();
				$idJornada = $fila->idJornada + 1;
			}
			else{
				// si no hay partidos, jornada actual la primera
				if ($rs->num_rows() == 0)
					$idJornada = 1;
				else{
					// si aun no estan finalizados todos, jornada devuelta por la conslta
					$fila = $rs->row();
					$idJornada = $fila->idJornada;
				}
			}
			
			$sqlquery_partido = "SELECT a.idTemporada,a.idJornada,a.idLocal,a.idVisitante,b.Nombre as local ,c.Nombre as visitante,
								b.escudo as escudoLocal, c.escudo as escudoVisitante, b.campo  as campoLocal, c.campo  as campoVisitante ";
            $sqlquery_partido.="FROM ov_partidos_liga_juvenil a, ov_equipos b, ov_equipos c 
								WHERE a.idJornada=".$idJornada." and a.idTemporada=" . $this->session->userdata("idTemporada") . " and (a.idLocal = 102 or a.idVisitante=102) and a.idLocal=b.idEquipo and a.idVisitante=c.idEquipo ";
		
			$rs_proximo_partido = $this->db->query($sqlquery_partido);
			if ($rs_proximo_partido->num_rows() == 0)
				return "";
			else
			{
				$fila = $rs_proximo_partido->row();
				$salida.='<div class="heading-title heading-dotted text-center">
						<h4>
						Próximo
						<span>Partido</span>
						</h4>
					</div>
					<table class="table">
						<tbody>
							<tr>
								<td><img src="http://www.oviedin.com/' . $fila->escudoLocal . '"></td>
								<td><h3>' . $fila->local . '</h3></td>
							</tr>
							<tr class="total">
								<td><img src="http://www.oviedin.com/' . $fila->escudoVisitante . '"></td>
								<th ><h3>' . $fila->visitante . '</h3></th>
							</tr>';
				if ( $fila->campoLocal != "")
					$salida.='<tr><td colspan="2" style="background:#fafafa"><center>'. $fila->campoLocal.'</center></td></tr>';
				
				$salida.='
							</tbody>
						</table>
						<div class="divider margin-top-5 margin-bottom-5"></div>';
			}
			return $salida;
		}
	}
	
    function clasificacion_plantilla($conf) {
        $num_clasificacion = $conf->num_clasificacion; // mostrar todos los equipos
        $clasificacion = $this->db->query("SELECT idTemporada,a.idEquipo,nombre,puntos, escudo, escudoPeq FROM ov_liga a, ov_equipos b WHERE idTemporada=" . $this->session->userdata('idTemporada') . " AND a.idEquipo=b.idEquipo order by puntos DESC,(golesC - golesF) desc, golesF desc, golesC, posicion limit 0," . $num_clasificacion);

        $salida = "";


        $salida.='<div id="tab_1" class="tab-pane active">
			<div class="panel panel-danger">

				<div class="nopadding panel-body">
					<table class="table nomargin">
						<thead>
							<tr>
								<th></th>
								<th colspan="2">Equipo</th>
								<th>Puntos</th>
							</tr>
						</thead>
						<tbody>';

        $i = 0;
        foreach ($clasificacion->result() as $fila) {
            ++$i;

            $salida.='	<tr>
								<td>' . $i . '</td>
								<td>
									<a href="#">
										<img alt="" src="http://www.oviedin.com/' . $fila->escudoPeq . '" class="responsive">
									</a>
								</td>
								<td><a href="#" >' . $fila->nombre . '</a></td>
								<td>' . $fila->puntos . '</td>
							</tr>
						';
        }
        $salida.='
						</tbody>
					</table>
				</div>
			</div>
		</div>';

        return $salida;
    }

    function clasificacion_goles($conf) {
        $salida = "";
        //Goleadores
        $num_goleadores = $conf->num_goleadores;
        $goleadores = $this->db->query("SELECT a.idJugador,b.NombreCorto, foto,fotoPeq, SUM(goles) as goles from li_puntos a, ov_jugadores b where a.idTemporada = " . $this->session->userdata('idTemporada') . " and a.idJugador=b.idJugador and b.puesto>0 group  by idJugador,idTemporada having SUM(goles) > 0 order by goles desc limit 0," . $num_goleadores);

        $salida = '
      <div id="tab_2" class="tab-pane">
		<div class="panel panel-danger">
				<div class="nopadding panel-body">
					<table class="table nomargin">
						<thead>
							<tr>
								<th></th>
								<th colspan="2">Nombre</th>
								<th>Goles</th>
							</tr>
						</thead>
						<tbody>';

        $i = 0;
        foreach ($goleadores->result() as $fila) {
            ++$i;
            $salida.='
							<tr>
								<td>' . $i . '</td>
								<td>
									<a href="#">
										<img alt="" src="http://www.oviedin.com/' . $fila->foto . '" class="responsive">
									</a>
								</td>
								<td><a href="#" >' . $fila->NombreCorto . '</a></td>
								<td>' . $fila->goles . '</td>
							</tr>';
        }
        $salida.='
						</tbody>
					</table>
				</div>
			</div>
			</div>';

        return $salida;
    }

    function clasificacion_porra($conf) {
        $salida = "";

        //Porra
        $num_porra = $conf->num_porra;
        $num_porra = 20;
        $porra = $this->db->query("select idTemporada,usuario,sum(total) as total, sum(puntos_resultado) as pr, sum(puntos_signo) as ps, sum(puntos_goles) as pg from po_puntos_jornada group by idTemporada,usuario having idTemporada=" . $this->session->userdata('idTemporada') . " ORDER BY total DESC, pr desc, pg desc, ps desc LIMIT 0," . $num_porra);

        $salida.='<div id="tab_3" class="tab-pane">
				<div class="nopadding panel-body">
					<table class="table nomargin">
						<thead>
							<tr>
								<th></th>
								<th >Usuario</th>
								<th>Puntos</th>
							</tr>
						</thead>
						<tbody>';

        $i = 0;
        foreach ($porra->result() as $fila) {
            ++$i;
            $salida.='
							<tr>
								<td>' . $i . '</td>

								<td><a href="#" >' . $fila->usuario . '</a></td>
								<td>' . $fila->total . '</td>
							</tr>';
        }
        $salida.='
						</tbody>
					</table>
				</div>
			</div>
			';
        return $salida;
    }


    // FILIAL
    function clasificacion_plantilla_filial($conf) {
        $num_clasificacion = $conf->num_clasificacion; // mostrar todos los equipos
        $clasificacion = $this->db->query("SELECT idTemporada,a.idEquipo,nombre,puntos, escudoPeq FROM ov_liga_filial a, ov_equipos b WHERE idTemporada=" . $this->session->userdata('idTemporada') . " AND a.idEquipo=b.idEquipo order by puntos DESC,(golesC - golesF) desc, golesF desc, golesC, posicion limit 0," .$num_clasificacion);

        $salida = "";


        $salida.='<div id="tab_1" class="tab-pane active">
			<div class="panel panel-danger">

				<div class="nopadding panel-body">
					<table class="table nomargin">
						<thead>
							<tr>
								<th></th>
								<th colspan="2">Equipo</th>
								<th>Puntos</th>
							</tr>
						</thead>
						<tbody>';

        $i = 0;
        foreach ($clasificacion->result() as $fila) {
            ++$i;

            $salida.='	<tr>
								<td>' . $i . '</td>
								<td>
									<a href="#">
										<img alt="" src="http://www.oviedin.com/' . $fila->escudoPeq . '" class="responsive">
									</a>
								</td>
								<td><a href="#" >' . $fila->nombre . '</a></td>
								<td>' . $fila->puntos . '</td>
							</tr>
						';
        }
        $salida.='
						</tbody>
					</table>
				</div>
			</div>
		</div>';

        return $salida;
    }


    function clasificacion_goles_filial($conf) {
        $salida = "";
        //Goleadores
        $num_goleadores = $conf->num_goleadores;
        $goleadores = $this->db->query("SELECT a.idJugador, NombreCorto, fotoPeq as foto, SUM(goles) as goles
from ov_cronica_filial_jugadores a, ov_jugadores b
where a.idJugador=b.idJugador and puesto > 0
group by idTemporada, nombreCorto,fotoPeq having idTemporada=" . $this->session->userdata('idTemporada') . " and sum(goles) > 0 order by SUM(goles) desc" ." limit 0," . $num_goleadores);

        $salida = '
      <div id="tab_2" class="tab-pane">
		<div class="panel panel-danger">
				<div class="nopadding panel-body">
					<table class="table nomargin">
						<thead>
							<tr>
								<th></th>
								<th colspan="2">Nombre</th>
								<th>Goles</th>
							</tr>
						</thead>
						<tbody>';

        $i = 0;
        foreach ($goleadores->result() as $fila) {
            ++$i;
            $salida.='
							<tr>
								<td>' . $i . '</td>
								<td>
									<a href="#">
										<img alt="" src="http://www.oviedin.com/' . $fila->foto . '" class="responsive">
									</a>
								</td>
								<td><a href="#" >' . $fila->NombreCorto . '</a></td>
								<td>' . $fila->goles . '</td>
							</tr>';
        }
        $salida.='
						</tbody>
					</table>
				</div>
			</div>
			</div>';

        return $salida;
    }

	// Juvenil
	function clasificacion_plantilla_juvenil($conf) {
        $num_clasificacion = $conf->num_clasificacion; // mostrar todos los equipos
        $clasificacion = $this->db->query("SELECT idTemporada,a.idEquipo,nombre,puntos, escudoPeq FROM ov_liga_juvenil a, ov_equipos b WHERE idTemporada=" . $this->session->userdata('idTemporada') . " AND a.idEquipo=b.idEquipo order by puntos DESC,(golesC - golesF) desc, golesF desc, golesC, posicion limit 0," .$num_clasificacion);

        $salida = "";


        $salida.='<div id="tab_1" class="tab-pane active">
			<div class="panel panel-danger">

				<div class="nopadding panel-body">
					<table class="table nomargin">
						<thead>
							<tr>
								<th></th>
								<th colspan="2">Equipo</th>
								<th>Puntos</th>
							</tr>
						</thead>
						<tbody>';

        $i = 0;
        foreach ($clasificacion->result() as $fila) {
            ++$i;

            $salida.='	<tr>
								<td>' . $i . '</td>
								<td>
									<a href="#">
										<img alt="" src="http://www.oviedin.com/' . $fila->escudoPeq . '" class="responsive">
									</a>
								</td>
								<td><a href="#" >' . $fila->nombre . '</a></td>
								<td>' . $fila->puntos . '</td>
							</tr>
						';
        }
        $salida.='
						</tbody>
					</table>
				</div>
			</div>
		</div>';

        return $salida;
    }

    // Devuelve array con las jornadas y partidos de la temporada que se le pasa como parámetro
    function lista_jornadas($idTemporada = "",$sel ="")
    {

       if ($idTemporada == "")
           $idTemporada =$this->session->userdata("idTemporada");

       $rs = $this->db->query("SELECT anio,jornada,local,visitante,idLocal,idVisitante,fecha, activa FROM  ov_jornadas WHERE anio=".$idTemporada."  order by jornada desc");

	$lista_jor='<option value="">--- Seleccionar ---</option> ';


        foreach ($rs->result() as $row3)
	{
            if ($row3->fecha == "0000-00-00")
                continue;
            if ($row3->activa == 1)
                $sel=" selected";
            else
                $sel="";
            $lista_jor.='<option value="'.$row3->anio.';'.$row3->jornada.'" '.$sel.'>'.$row3->jornada.": ".$row3->local." - ".$row3->visitante." (".$row3->fecha.")</option>";



	}
        return $lista_jor;
    }

    // Para el tipo de equipo y la temporada, devuelve la jornada actual
    function jornada_actual($tipo,$idTemporada)
    {
		// jornada activa oviedo
		if ($tipo == 0)
		{
			$rs = $this->db->query("select min(idJornada) as idJornada from ov_partidos_liga where idTemporada=".$idTemporada." and finalizado = 0 and (idLocal=1 or idVisitante = 1)");
			$fila = $rs->row();
			$idJornada = $fila->idJornada;
		}

		// jornada activa filial
		if ($tipo == 1)
		{
			$rs = $this->db->query("select min(idJornada) as idJornada from ov_partidos_liga_filial where idTemporada=".$idTemporada." and finalizado = 0");
			$fila = $rs->row();
			$idJornada = $fila->idJornada;
		}
		
		// jornada activa juvenil
		if ($tipo == 2)
		{
			$rs = $this->db->query("select min(idJornada) as idJornada from ov_partidos_liga_juvenil where idTemporada=".$idTemporada." and finalizado = 0");
			$fila = $rs->row();
			$idJornada = $fila->idJornada;
		}
		return $idJornada;
    }


	// genera estructura de datos para el procesado posterior de la noticia
	function procesar_noticia($rs_noticias)
	{
		// TEMPORAL: No procesar imagenes en noticias
		//$no_tratar =1;
		// FIN TEMPORAL
					
		$datos_noticias = array();
		
		$dias = array("DOMINGO", "LUNES", "MARTES", "MIERCOLES", "JUEVES", "VIERNES", "SABADO");
        $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $meses_corto = array("ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL", "AGO", "SEP", "OCT", "NOV", "DIC");

        foreach ($rs_noticias->result() as $fila) {
			$faux = explode("-", $fila->fecha);
			$fecha = mktime(0, 0, 0, $faux[1], $faux[2], $faux[0]);
			$dia = date("w", $fecha);
			$mes = date("m", $fecha);
			$mes = $mes - 1;
			$dia_num = date("j", $fecha);
			$dia_semana = $dias[$dia];

			$fecha_larga= $dias[$dia] . " " . $dia_num . " de " . strtoupper($meses[$mes]) . " de " . $faux[0];
			$fecha_corta_dia = $dia_num;
			$fecha_corta_mes = $meses_corto[$mes];
			//	if ($fecha_ant != $fila->fecha)
	//		{
			
			switch ($fila->tipo_noticia) {
				case 0: 	$tipo_noticia = "Real Oviedo"; break;
				case 4: 	$tipo_noticia = "Real Oviedo B"; break;
				case 5: 	$tipo_noticia = "Real Oviedo Juvenil"; break;
				case 6: 	$tipo_noticia = "Categorias Inferiores"; break;
				default: $tipo_noticia = "Noticias"; break;
			}
                                
			$str_img='<img src';
			$num_imagenes=substr_count($fila->texto,$str_img);
			if ($num_imagenes == 0)
			{
				$str_img='<img alt="" src';
				$num_imagenes=substr_count($fila->texto,$str_img);
			}

			if ($num_imagenes > 0 )
			{
				$pos=strpos($fila->texto,$str_img);
						
				$pos_ini=strpos($fila->texto,"src=",$pos);
				$pos_fin=strpos($fila->texto,'"',$pos_ini+5);
				
				$url_imagen = "http://www.oviedin.com/".substr($fila->texto, $pos_ini + 5, ($pos_fin - $pos_ini - 5));
					 
				list($ancho, $alto, $tipo, $atributos) = @getimagesize($url_imagen);
				
				if ($ancho > 400)
					$fila->texto = str_replace(' src="', ' class="img-responsive" src="http://www.oviedin.com/', $fila->texto);
				else
					$fila->texto = str_replace(' src="', ' src="http://www.oviedin.com/', $fila->texto);
			}

			$fila->texto = strip_tags($fila->texto,'<span></span><img>,<p>,</p>,<br>,<br/>,<u>,</u>,<i>,</i>,<a>,</a>');
            // imagen de entrada
			$imagen ="";
			 if ($fila->imagen != null && $fila->imagen != "") { 
				list($ancho, $alto, $tipo, $atributos) = @getimagesize(base_url().$fila->imagen);
				if ($ancho > 400)
					$imagen =  '<img class="img-responsive" src="'.base_url().$fila->imagen.'" style="padding-bottom: 10px">';
				else
					$imagen =  '<img  src="'.base_url().$fila->imagen.'"  style="padding-bottom: 10px">';
			}

			$datos_noticias[$fila->fecha][$fila->id] = array('titulo' => $fila->titulo, 
				'texto' => $fila->texto, 
				'tipo' => $tipo_noticia,
				'fecha_larga' => $fecha_larga,
				'fecha_corta_dia' => $fecha_corta_dia,
				'fecha_corta_mes' => $fecha_corta_mes,
				'id' => $fila->id,
                'imagen' => $imagen);
//			}
			
			//$fecha_ant = $fila->fecha;
		}
	return $datos_noticias;
	}
}
