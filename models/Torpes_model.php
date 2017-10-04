<?php class Torpes_model extends CI_Model {

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }

    public function jornada_activa()
    {
            $datos = array();
            $rs = $this->db->query("select * from tor_jornadas a, tor_temporadas b 
                     where b.idTemporada=".$this->session->userdata('tor_idTemporada')." and a.idTemporada=b.idTemporada and a.activa=1");
            $numJornada=1;
            foreach ($rs->result() as $fila)
            {
                    $numJornada = $fila->numJornada;
                    $datos[$fila->idJornada] = array('idJornada' => $fila->idJornada,
                            'idLocal' => $fila->idLocal,
                            'idVisitante' => $fila->idVisitante,
                            'golesLocal' => $fila->golesLocal,
                            'golesVisitante' => $fila->golesVisitante,
                            'fecha' => $fila->fecha);
            }
            //datos jornada completos
            $datos_completos = array();
            $rs = $this->db->query("select a.idJornada, a.idUsuario, b.golesLocal, b.golesVisitante, nombreUsuario, equipoLocal,
                    equipoVisitante, idApuesta, cerrada
                    from v_tor_consulta_nueva a, tor_apuestas b
                    where numJornada=".$numJornada." and a.idJornada=b.idJornada and a.idUsuario=b.idUsuario and a.idTemporada = ".$this->session->userdata('tor_idTemporada'));
            foreach ($rs->result() as $fila)
            {

                    $datos_completos[$fila->idJornada][$fila->idUsuario] = array('idJornada' => $fila->idJornada,

                            'golesLocal' => $fila->golesLocal,
                            'golesVisitante' => $fila->golesVisitante,

                        'nombreUsuario' => $fila->nombreUsuario,
                        'equipoLocal' => $fila->equipoLocal,
                        'equipoVisitante' => $fila->equipoVisitante,
                        'idApuesta' => $fila->idApuesta,
                        'cerrada' => $fila->cerrada);
            }
            $datos_vuelta = array('numJornada' => $numJornada,
            'jornadas' => $datos,
                'jornadas_completas' => $datos_completos);
            return $datos_vuelta;
    }

    public function jornada_activa_numero($num_jornada)
    {
            $datos = array();
            $rs = $this->db->query("select * from tor_jornadas a, tor_temporadas b where b.idTemporada=".$this->session->userdata('tor_idTemporada')."
                     and a.idTemporada=b.idTemporada and a.numJornada=".$num_jornada);
            foreach ($rs->result() as $fila)
            {
                    $numJornada = $fila->numJornada;
                    $datos[$fila->idJornada] = array('idJornada' => $fila->idJornada,
                            'idLocal' => $fila->idLocal,
                            'idVisitante' => $fila->idVisitante,
                            'golesLocal' => $fila->golesLocal,
                            'golesVisitante' => $fila->golesVisitante,
                            'fecha' => $fila->fecha);
            }
            $datos_vuelta = array('numJornada' => $numJornada,
            'jornadas' => $datos);
            return $datos_vuelta;
    }

    public function lista_equipos()
    {
            $datos = array();
            $rs = $this->db->query("select idEquipo, nombre, escudo, escudoPeq from tor_equipos order by nombre");
            foreach ($rs->result() as $fila)
            {
                    $datos[$fila->idEquipo] = array('idEquipo' => $fila->idEquipo,
                            'nombre' => $fila->nombre,
                            'escudo' => $fila->escudo,
                            'escudoPeq' => $fila->escudoPeq);
                    $datos2[$fila->idEquipo] = $fila->nombre;
            }
            $datos_vuelta = array('listaEquiposCompleta' => $datos, 'listaEquipos' => $datos2);
            return $datos_vuelta;
    }

    public function lista_jornadas($idJornada="")
    {

            $idTemporada = $this->session->userdata('tor_idTemporada');

             if ($idJornada != "")
$where = " and numJornada <= ".$idJornada." and numJornada >= ".($idJornada - 6)." ";
else
$where ="";

            $datos = array();
            $rs = $this->db->query("select distinct numJornada from tor_jornadas where idTemporada=".$idTemporada." ".$where." order by numJornada desc");
            foreach ($rs->result() as $fila)
            {
                    $datos[] = $fila->numJornada;

            }
            $datos_vuelta = array('listaJornadas' => $datos);


            return $datos_vuelta;
    }

    public function ganancias_usuario($numJornada = "")
    {
		
        $rs = $this->db->query("select nombreUsuario, count(1) as numPartidos,
            sum(if(puntos_res = 0, 0, 1)) as numPartidosAcertados,
            sum(if(puntos_signo = 0, 0, 1)) as numSignosAcertados,
            sum(if(golesLocalApuesta = golesLocalResultado, 1, 0)) as numGolesLocalAcertados,
            sum(if(golesVisitanteApuesta = golesVisitanteResultado, 1, 0)) as numGolesVisitanteAcertados,
            sum(puntos_total) as puntos_total
            from v_tor_consulta_nueva
            WHERE idTemporada = ".$this->session->userdata('tor_idTemporada')." 
            group by nombreUsuario
            order by sum(puntos_total) desc");

            if ($rs->num_rows() == 0)
            {
                $datos_salida=array();
                return;
            }
        $datos_salida=array('estadisticas' => $rs);

        if ($numJornada == "")
            $rs2=$this->db->query("SELECT a.nombreUsuario, a.numJornada, sum( a.puntos_total ) AS puntos_total
                FROM v_tor_consulta_nueva a, tor_jornadas b
                where a.idJornada=b.idJornada and b.activa = 0 and a.idTemporada = ".$this->session->userdata('tor_idTemporada')." 
                 GROUP BY nombreUsuario, numJornada order by numJornada");
        else {
            $rs2=$this->db->query("SELECT a.nombreUsuario, a.numJornada, sum( a.puntos_total ) AS puntos_total
            FROM v_tor_consulta_nueva a, tor_jornadas b
            where a.idJornada=b.idJornada and b.activa = 0 and a.numJornada=".$numJornada." and a.idTemporada = ".$this->session->userdata('tor_idTemporada')." 
            GROUP BY nombreUsuario, numJornada order by numJornada");
        }

        $datos=array();
        foreach ($rs2->result() as $fila)
        {
            $datos[$fila->numJornada][$fila->nombreUsuario] = $fila->puntos_total;
            //$datos[] = array($fila->nombreUsuario => $fila->puntos_total);
        }


        // ordenar por jornada
        foreach ($datos as $idJornada => $value)
        {
            arsort($value);
            $datos[$idJornada]  = $value;
        }

        $ganancia_jor = array();
        $ganancia_usr = array();

        foreach ($datos as $idJornada => $value)
        {
            $i=0;
            $pos=1;
            $puntos_anterior=0;
            foreach($value as $nombre => $puntos)
            {

                if ($i==0)
                {

                    $puntos_anterior = $puntos;
                    $ganancia_jor[$idJornada][$nombre] = $pos;
                }
                else
                {

                    if ($puntos == $puntos_anterior && $pos <= 2)
                    {
                        $ganancia_jor[$idJornada][$nombre] = $pos;
                    }
                    else
                    {
                        $puntos_anterior=$puntos;
                        $pos = $pos + 1;
                        if ($pos <= 2)
                        {
                            $ganancia_jor[$idJornada][$nombre] = $pos;
                        }
                    }
                }

                ++$i;
            }



        }

        //print_r($ganancia_jor);

        foreach ($ganancia_jor as $idJornada => $value)
        {
            $ganacia = 0;
            if (count($value) == 2)
            {       $compartir = 0;
                $dinero = array(1 => 4, 2 => 1);
        }
            else
            {
                $compartir = 1;
                // calular importe por posicion
                $dinero_arr = array_count_values($value);
                if ($dinero_arr[1] > 1)
                {
                    //quitar al segundo
                    $dinero[1] = 5 / $dinero_arr[1];
                    $dinero[2] = 0;
                }
                else
                {
                    $dinero[1] = 4 / $dinero_arr[1];
                    $dinero[2] = 1 / $dinero_arr[2];

                }
            }



            foreach ($value as $usuario => $pos)
            {
                if (array_key_exists($usuario,$ganancia_usr))
                    $ganancia_usr[$usuario] += $dinero[$pos]     ;
                else
                    $ganancia_usr[$usuario] = $dinero[$pos]     ;
            $ganancia_jor[$idJornada][$usuario] = $dinero[$pos]     ;
            }

        }
        
            
            
		// TRAMOS
		
		$rs_conf = $this->db->query("select num_tramos, num_tramos_jornadas, imp_tramo_1, imp_tramo_2, imp_tramo_3
		from tor_temporadas where idTemporada = ".$this->session->userdata('tor_idTemporada'));
		$fila_conf = $rs_conf->row();

		if ($fila_conf->imp_tramo_3 > 0)
			$num_tramos_ganacias = 3;
		else if ($fila_conf->imp_tramo_2 > 0)
			$num_tramos_ganacias = 2;
		else	
			$num_tramos_ganacias = 1;

		if ($fila_conf->num_tramos != 0)
		{
			
			$jornada_actual = $this->jornada_activa();

			// JORANDA ACTUAL. SI ES LA ULTIMA TENER EN CUENTA ESA JORNADA
			if ($jornada_actual["numJornada"] != $this->session->userdata("tor_numPartidos"))
				$jornada_actual = $jornada_actual["numJornada"] - 1;
			else
				$jornada_actual = $jornada_actual["numJornada"];
			$numTramos = floor($jornada_actual / $fila_conf->num_tramos_jornadas);
			$datos_ganacias = array();
			
			for ($i=1; $i<=$numTramos;++$i)
			{
				$imp_tramo_1 = $fila_conf->imp_tramo_1;
				$imp_tramo_2 = $fila_conf->imp_tramo_2;
				$imp_tramo_3 = $fila_conf->imp_tramo_3;
				
				$rs_tramos = $this->db->query("select nombreUsuario, idUsuario, sum(puntos_total) 
					FROM v_tor_consulta_nueva 
					WHERE idTemporada = ".$this->session->userdata('tor_idTemporada')."
					AND numJornada > ".($fila_conf->num_tramos_jornadas * ($i - 1))." 
					AND numJornada <= ".($fila_conf->num_tramos_jornadas * $i)." 
					group by nombreUsuario, idUsuario
					order by sum(puntos_total)  desc");	
				$total = 1;
				foreach ($rs_tramos->result() as $fila)
				{
					
					if ($total > $num_tramos_ganacias)
						continue;
					
					if ($total == 1)	
					{
						
						if (isset($datos_ganancias[$fila->nombreUsuario]))
						{
							$a=$datos_ganancias[$fila->nombreUsuario];
							$datos_ganancias[$fila->nombreUsuario] =  $a + $imp_tramo_1;	
						}
						else
							$datos_ganancias[$fila->nombreUsuario] = $imp_tramo_1;	
					}
					else if ($total == 2)	
					{
						if (isset($datos_ganancias[$fila->nombreUsuario])){
							$a=$datos_ganancias[$fila->nombreUsuario];
							$datos_ganancias[$fila->nombreUsuario] =  $a + $imp_tramo_2;	
						}
						else
							$datos_ganancias[$fila->nombreUsuario] = $imp_tramo_2;	
					}
					else
					{
						if (isset($datos_ganancias[$fila->nombreUsuario]))
						{								
							$a=$datos_ganancias[$fila->nombreUsuario];
							$datos_ganancias[$fila->nombreUsuario] =  $a + $imp_tramo_3;	
						}
						else
							$datos_ganancias[$fila->nombreUsuario] = $imp_tramo_3;	
					}
					$total++;
				}
			}
                        
                    if ($jornada_actual > 0)
                    {
						if (isset($datos_ganancias))
						{
                            foreach ($datos_ganancias as $usr => $imp)
                            {
                                if (isset($ganancia_usr[$usr]))
                                {
                                        $ganancia_usr[$usr] = $ganancia_usr[$usr] + $imp;
                                }
                                else
                                {
                                        $ganancia_usr[$usr] = $imp;
                                }
                            }
						}
                    }
        }
        
        // Ganacias de la tabla nueva
        $ganancia_usr_nuevo = $this->db->query("select a.idUsuario, nombre, ganancia, tipoGanancia from tor_clasificacion a, tor_usuarios b where a.idUsuario=b.idUsuario and  idTemporada=".$this->session->userdata('tor_idTemporada')." and idJornada=0 and numJornada = 0 order by ganancia desc");

        // Listado jornadas con premio
        //echo '&nbsp; <i class="fa fa-level-down fa-1" style="color:#E01F1F"
        //data-toggle="tooltip" data-placement="top" title="'. $jornada_ant[$fila->nombre]['posicion'].'"></i>';

        $ganancia_leyenda = $this->db->query("select idUsuario,numJornada, posicion, ganancia, tipoGanancia from tor_clasificacion where   idTemporada=".$this->session->userdata('tor_idTemporada')." and idJornada=0 and numJornada > 0 and ganancia > 0 order by numJornada");
        $leyenda = array();
        foreach($ganancia_leyenda->result() as $fila)
        {
                $leyenda[$fila->idUsuario] [$fila->numJornada] = $fila->ganancia;
        }

            $leyenda_final=array();
        foreach ( $leyenda as $idUsuario => $jornadas)
        {
            
            $leyenda_final[$idUsuario] = "";
            foreach ( $jornadas as $a => $b)
            {
                if ($leyenda_final[$idUsuario] == "")
                    $leyenda_final[$idUsuario] = "Jornada ".$a.": ".$b."€";
                else
                    $leyenda_final[$idUsuario] .= ", Jornada ".$a.": ".$b."€";
                
            }

        }
        foreach ( $leyenda_final as $idUsuario => $texto)
        {
            $leyenda_final[$idUsuario] = '&nbsp;<i class="fa fa-eur" style="color:#38A7BB" data-toggle="tooltip" data-placement="top" title="'. $texto.'"></i>';

        }
		arsort($ganancia_usr);
		$datos_salida = array ('ganancia_usr' => $ganancia_usr,
                               'ganancia_jor' => $ganancia_jor,
                               'ganancias_usr_nuevo' => $ganancia_usr_nuevo,
                               'leyenda_final' => $leyenda_final);

        return $datos_salida;
    }

	public function maxima_puntuacion()
	{
		$rs = $this->db->query("select numJornada, idUsuario, nombreUsuario, sum(puntos_total) as total from v_tor_consulta_nueva 
								where idTemporada = ".$this->session->userdata('tor_idTemporada')." 
								group by numJornada, idUsuario, nombreUsuario
								order by sum(puntos_total) desc");
//								limit 0,10");

		$datos_salida = array();
		if ($rs->num_rows() > 0)
		{
			$idUsuario = 0;
			$puntos = null;
			$nombre = "";

            $puntosUsuario = null;

			$iguales = 0;
			$iguales_arr = array();
            $pos = 0;
			foreach ($rs->result() as $fila)
			{
                
				if ($puntos  != null)
				{
					if ($puntos == $fila->total)
					{
						$iguales++;
						$iguales_arr[] = $fila->idUsuario;
					}
					else
                    {
                       
//                        break;
                    }
                    
				}
				else
				{
					$puntos = $fila->total;
					$nombre = $fila->nombreUsuario;
					$idUsuario =$fila->idUsuario;
					$iguales_arr[] = $fila->idUsuario;
				}
                
                // obtener maxima puntuacion del usuario
                if ($this->session->userdata('tor_idUsuario') == $fila->idUsuario && $pos>=0 && $puntosUsuario == null)
                {
                    $puntosUsuario = $fila->total;
                }
                ++$pos;
			}
			
			// Hay dos jugadores con el mismo maximo
			if ($iguales > 0)
			{
			}
			$datos_salida = array('idUsuario' => $idUsuario,
			'puntos' => $puntos,
			'nombre' => $nombre,
            'puntosUsuario' => $puntosUsuario);
			
		}
		
		return $datos_salida;
	
	}
	
    public function lista_jornadas2()
    {
        $rs = $this->db->query("select distinct numJornada from v_tor_consulta_nueva where idTemporada = ".$this->session->userdata('tor_idTemporada'));
        $datos_salida = array ();
        foreach ($rs->result() as $fila)
            $datos_salida[$fila->numJornada]=$fila->numJornada;

        return $datos_salida;
    }


    // Devuelve array con los puntos definidos en la temporada
    function temporada_puntos($idTemporada)
    {

        
        if (!$this->session->userdata('tor_puntos_temporada') ) {
            $rs=$this->db->query("SELECT num_puntos_res, num_puntos_signo, num_puntos_goles, num_puntos_resta, diferencia_puntos from tor_temporadas
                                   WHERE idTemporada=".$idTemporada);
            $fila=$rs->row();
            $this->session->set_userdata('tor_puntos_temporada', $fila);

        } 
        else
        {
            $fila = $this->session->userdata('tor_puntos_temporada');
        }

/*
        $rs=$this->db->query("SELECT num_puntos_res, num_puntos_signo, num_puntos_goles, num_puntos_resta, diferencia_puntos from tor_temporadas
                   WHERE idTemporada=".$idTemporada);
        $fila=$rs->row();
*/


        $datos=array('num_puntos_res' => $fila->num_puntos_res,
            'num_puntos_signo' => $fila->num_puntos_signo,
            'num_puntos_goles' => $fila->num_puntos_goles,
            'num_puntos_resta' => $fila->num_puntos_resta,
            'diferencia_puntos' => $fila->diferencia_puntos);


        return $datos;
    }

    public function dibujar_apuestas($partidos_jornada, $numJornada, $fechaCierre)
    {

        $cerrada = 0;
        $JornadaCerrada=0;
        $salida="";
        $fecha_ahora = date('Y-m-d H:i:s');


        if ($partidos_jornada->num_rows() > 0 &&   jornada_cerrada()) 
            $JornadaCerrada=1;

        foreach ($partidos_jornada->result() as $fila) {
            if ($fila->idUsuario != $this->session->userdata('tor_idUsuario'))
                continue;
            if ($fila->cerrada == 1)
                $cerrada = 1;
        }
        if ($cerrada == 1) {
              $partido_anterior="";
              // Calcular leyenda 
              foreach ($partidos_jornada->result() as $fila) {
                $partido = $fila->LOCAL." - ".$fila->visitante;
                
                if ($partido != $partido_anterior)
                {
                    $leyenda[$partido]["1"] = 0;
                    $leyenda[$partido]["X"] = 0;
                    $leyenda[$partido]["2"] = 0;
                }
                $leyenda[$partido][$fila->golesLocalApuesta."-".$fila->golesVisitanteApuesta] = 0;

                if ($fila->golesLocalApuesta == $fila->golesVisitanteApuesta)
                    $leyenda[$partido]["X"] += 1;
                if ($fila->golesLocalApuesta > $fila->golesVisitanteApuesta)
                $leyenda[$partido]["1"] += 1;
                if ($fila->golesLocalApuesta < $fila->golesVisitanteApuesta)
                $leyenda[$partido]["2"] += 1;
                $partido_anterior = $partido;
            }

            

            $partido_anterior="";
            $num=0;
            foreach ($partidos_jornada->result() as $fila) {
            
                $partido = $fila->LOCAL." - ".$fila->visitante;
                $cambioPartido=0;
                if ($partido != $partido_anterior)
                {
                    $cambioPartido=1;
                    if ($num>0) 
                    {
                        $salida.="</div>";  // cerrar bloque de partidos anterior
                        $salida.='
                        <div>
                            <h5><center><a id="btn-Convert-partido'.$num.'" href="#">Descargar Imagen</a></center></h5>
                            <h5><center><input id="btn-Preview-partido'.$num.'" type="button" value="Crear Imagen"/></center></h5>
                            <div id="previewImage'.$num.'"></div>
                        </div>';
                    }
                    ++$num;
                    $salida.='<div id="partido'.$num.'" >';  
                    $salida.='<div class="row filaTitulo" >';
                    $partido_anterior = $partido;
                    if (strtotime($fila->fecha) < strtotime($fecha_ahora))
                    {
                        
                        $fecha_final_partido = strtotime(" +120 minute",strtotime($fila->fecha ));
                        if ($fecha_final_partido  < strtotime($fecha_ahora))
                        {
                            if ($fila->signo != '')
                                $salida.='<div class="col-sd-10 col-md-10 col-xs-8" ><h4>'.$fila->LOCAL.' - <font color="#38A7BB">'.$fila->golesLocalResultado.'</font> - '.$fila->visitante.' <font color="#38A7BB">'.$fila->golesVisitanteResultado.'</font></h4></div>';
                            else
                                $salida.='<div class="col-sd-10 col-md-10 col-xs-8" ><h4>'.$fila->LOCAL.' - '.$fila->visitante.'</h4></div>';
                                $salida.='<div class="col-sd-2 col-md-2 col-xs-4 text-right"><button class="btn btn-sm btn-info" type="button">Finalizado</button></div>';
                        }
                        else
                        {
                            $salida.='<div class="col-sd-10 col-md-10 col-xs-8" ><h4>'.$fila->LOCAL.' - '.$fila->visitante.'</h4></div>';
                            $salida.='<div class="col-sd-2 col-md-2 col-xs-4 text-right"><button class="btn btn-sm btn-success" type="button">En juego</button></div>';
                            
                        }
                    }
                    else
                    {
                        $salida.='<div class="col-sd-10 col-md-10 col-xs-8" ><h4>'.$partido.'</h4></div>';
                        $salida.='<div class="col-sd-2 col-md-2 col-xs-4 text-right"><h4>'.fecha_hora($fila->fecha).'</h4></div>';
                    }
                    $back='filaApuesta1';
                    $resAnt=$fila->golesLocalApuesta."-".$fila->golesVisitanteApuesta;


// leyenda
$salida.='<div class="col-sd-12 col-md-12 col-xs-12" style="margin-bottom:5px">';

$salida.= '<span class="label label-info">Resultados Distintos: '.(count($leyenda[$partido]) - 3).'</span>';
$salida.= '&nbsp;&nbsp;<span class="label label-success">1: '.$leyenda[$partido]["1"].'</span>';
$salida.= '&nbsp;&nbsp;<span class="label label-success">X: '.$leyenda[$partido]["X"].'</span>';
$salida.= '&nbsp;&nbsp;<span class="label label-success">2: '.$leyenda[$partido]["2"].'</span>';
$salida.= "</div>";

                    $salida.="</div>";

                                            
                    
                }

                // Detalle de partidos
                if ($resAnt != ($fila->golesLocalApuesta."-".$fila->golesVisitanteApuesta) && $cambioPartido==0)
                {
                    if ($back == "filaApuesta2")
                        $back='filaApuesta1';
                    else
                        $back="filaApuesta2";
                }
                if ($cambioPartido==1)
                {
                    $cambioPartido=0;
                }
                $salida.='<div class="row '.$back.'" >';
                $resAnt=$fila->golesLocalApuesta."-".$fila->golesVisitanteApuesta;
                if (strtotime($fila->fecha) < strtotime($fecha_ahora) || $fila->cerrada == 1)
                    $salida.=' <div class="col-sd-6 col-md-6 col-xs-4" style="padding-right: 10px; padding-left: 10px;" >'.$fila->nombreUsuario.' <i class="fa fa-lock" aria-hidden="true"></i></div>';
                else
                    $salida.=' <div class="col-sd-6 col-md-6 col-xs-4"  style="padding-right: 10px; padding-left: 10px;">'.$fila->nombreUsuario.' </div>';
                
                if (strtotime($fila->fecha) < strtotime($fecha_ahora) && $fila->golesLocalResultado == $fila->golesLocalApuesta)
                    $salida.='<div class="col-sd-1 col-md-1 col-xs-1" style="padding-right: 10px; padding-left: 10px;" ><b><font color="#33A529">'.$fila->golesLocalApuesta.'</font></b></div>';
                else
                    $salida.='<div class="col-sd-1 col-md-1 col-xs-1" style="padding-right: 10px; padding-left: 10px;" >'.$fila->golesLocalApuesta.'</div>';

                if (strtotime($fila->fecha) < strtotime($fecha_ahora) && $fila->golesVisitanteResultado == $fila->golesVisitanteApuesta)
                    $salida.='<div class="col-sd-1 col-md-1 col-xs-1" style="padding-right: 10px; padding-left: 10px;" ><b><font color="#33A529">'.$fila->golesVisitanteApuesta.'</font></b></div>';
                else
                    $salida.='<div class="col-sd-1 col-md-1 col-xs-1" style="padding-right: 10px; padding-left: 10px;" >'.$fila->golesVisitanteApuesta.'</div>';

                // globos informativos
                $info ="&nbsp;";
                if (strtotime($fila->fecha) < strtotime($fecha_ahora) && $fila->signo != '')
                {
                    if ($fila->golesLocalResultado == $fila->golesLocalApuesta && $fila->golesVisitanteResultado == $fila->golesVisitanteApuesta)
                        $info.="<span class='label label-success'>".$fila->puntos_res."</span>&nbsp;";


                    if (($fila->golesLocalResultado == $fila->golesVisitanteResultado && $fila->golesLocalApuesta == $fila->golesVisitanteApuesta) ||
                        ($fila->golesLocalResultado > $fila->golesVisitanteResultado && $fila->golesLocalApuesta > $fila->golesVisitanteApuesta) ||
                        ($fila->golesLocalResultado < $fila->golesVisitanteResultado && $fila->golesLocalApuesta < $fila->golesVisitanteApuesta))
                        $info.="<span class='label label-info'>".$fila->puntos_signo."</span>&nbsp;";

                    if ($fila->golesLocalResultado == $fila->golesLocalApuesta || $fila->golesVisitanteResultado == $fila->golesVisitanteApuesta)
                        $info.="<span class='label label-warning'>".$fila->puntos_goles."</span>&nbsp;";

                    if ($fila->puntos_resta > 0)
                        $info.="<span class='label label-danger'>-".$fila->puntos_resta."</span>&nbsp;";
                    
                    if ($fila->puntos_dif_goles > 0)
                        $info.='<span class="label label-default">-'.$fila->puntos_dif_goles.'</span>';
                }
                $salida.='<div class="col-sd-3 col-md-3 col-xs-4" style="padding-right: 10px; padding-left: 10px;" >'.$info.'</div>';
                
                $salida.='<div class="col-sd-1 col-md-1 col-xs-2 resaltado text-right" style="padding-right: 10px; padding-left: 10px;" >'.$fila->puntos_total.' P</div>';

                $salida.="</div>";
                

                
            }

            $salida.="</div>";
            if ($this->session->userdata('tor_rol')==1)
            {
                $salida.='
                <div>
                    <h5><center><a id="btn-Convert-partido'.$num.'" href="#">Descargar Imagen</a></center></h5>
                    <h5><center><input id="btn-Preview-partido'.$num.'" type="button" value="Crear Imagen"/></center></h5>
                    <!-- <div id="previewImage'.$num.'"></div> -->
                </div>';
            }
        }

        return $salida;
    }
}
