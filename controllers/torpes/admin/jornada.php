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

        }

        //$datos=array();
        $rs = $this->db->query("SELECT d.idTemporada, a.idJornada,d.numTemporada, a.numJornada, b.nombre as local, c.nombre as visitante, fecha, golesLocal, golesVisitante , signo
            FROM tor_temporadas d, tor_jornadas a, tor_equipos b, tor_equipos c
            WHERE d.idTemporada=".$this->session->userdata('tor_idTemporada')."
            and d.idTemporada=a.idTemporada
            and a.activa = 1
            and a.idLocal = b.idEquipo
            and a.idVisitante = c.idEquipo
            ");
        // buscar jornada no activa si no hay activas
        if ($rs->num_rows() == 0)
        {
            $rs = $this->db->query("SELECT d.idTemporada, a.idJornada,d.numTemporada, a.numJornada, b.nombre as local, c.nombre as visitante, fecha, golesLocal, golesVisitante , signo
                FROM tor_temporadas d, tor_jornadas a, tor_equipos b, tor_equipos c
                WHERE d.idTemporada=".$this->session->userdata('tor_idTemporada')."
                and d.idTemporada=a.idTemporada
                and a.activa = 0
                and a.idLocal = b.idEquipo
                and a.idVisitante = c.idEquipo
                order by a.idJornada desc
                ");
        }
        
        if ($rs->num_rows() == 0)
        {
            $idTemporada = $this->session->userdata('tor_idTemporada');
            $numJornada = 1;
        }
        else
        {
            $fila = $rs->row();

            $idTemporada = $fila->idTemporada;
            $numJornada = $fila->numJornada;
        }
        $datos = array('partidos' => $rs, 'numJornada' => $numJornada, 'idTemporada' => $idTemporada);
        $this->load->view('torpes/admin/jornada', $datos);
        //$this->session->unset_userdata('tor_usuario');
        return;
    }

    function resultado() {
        if (!$this->session->userdata('tor_usuario')) {
            $datos = array();
            $this->load->view('torpes/login', $datos);
            return;
        }

        //var_dump($_POST);
        $aux = array();
        foreach ($_POST as $dato => $valor) {
            if ($dato == "numJornada")
                continue;
            //  echo "$dato - $valor<br>";
            $a = explode("_", $dato);
            $campo = $a[0];
            $id_jornada = $a[1];
            $aux[$id_jornada][$campo] = $valor;
        }

        $grabado=0;
        foreach ($aux as $idJornada => $datos) {
            // no  si no hay datos metidos
            if ($datos["golesLocal"] == -1 || $datos["golesVisitante"] == -1)
                $datos["signo"] = "";
            else if ($datos["golesLocal"] > $datos["golesVisitante"])
                $datos["signo"] = "1";
            else if ($datos["golesLocal"] == $datos["golesVisitante"])
                $datos["signo"] = "X";
            else
                $datos["signo"] = "2";


            $data = array();

            foreach ($datos as $campo => $valor) {
                $data[$campo] = $valor;
            }

            $this->db->where('idJornada', $idJornada);
            $this->db->update('tor_jornadas', $data);
            $grabado=1;
            //log_message('error', 'SQL: ' . $this->db->last_query());
            //echo $this->db->last_query()."<br>";
        }

        // Calcular puntos despues de grabar
        if ($grabado == 1)
        {
            $this->puntos($this->session->userdata('tor_idTemporada'),$this->input->post("numJornada"), 1);

        }

        redirect('torpes/admin/jornada');
    }

    function puntos($idTemporada, $numJornada = "", $origen = "") {
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

        // Borrar apuestas con penalizaciones
        foreach ($jornadas as $key => $value) {
            $this->db->where('idJornada', $value['idJornada']);
            $this->db->where('penalizacion', 1);
            $this->db->delete('tor_apuestas');
           
        }

        // borrar datos de tabla clasificacion
        if ($idTemporada >= 5)
        {
            $this->db->where('numJornada', $numJornada);
            $this->db->delete('tor_clasificacion');
        }


        $rs = $this->db->query("SELECT b.idApuesta, a.idJornada, a.golesLocal AS golesLocalResultado, a.golesVisitante AS golesVisitanteResultado, b.golesLocal AS golesLocalApuesta, b.golesVisitante AS golesVisitanteApuesta, b.idUsuario, c.nombre, signo
            FROM tor_jornadas a, tor_apuestas b, tor_usuarios c
            WHERE idTemporada=" . $idTemporada . " and numJornada=" . $numJornada . " and signo in ('1','X','2') AND a.idJornada = b.idJornada
                  AND c.idUsuario = b.idUsuario");

        //log_message('error', '[admin/jornada/puntos/' . $idTemporada . '/' . $numJornada . '] Calcular puntos ------------------------------------------------------');

        $datos_puntos = $this->Torpes_model->temporada_puntos($idTemporada);

        $datos_clasificacion = array();
        foreach ($rs->result() as $fila) {
            // echo "----------------------------------------------------------------<br>";

            //log_message('error', '[admin/jornada/puntos/' . $idTemporada . '/' . $numJornada . ']  Puntos: ' . $fila->nombre . " - Resultado: " . $fila->golesLocalResultado . " - " . $fila->golesVisitanteResultado . " :: Apuesta: " . $fila->golesLocalApuesta . " - " . $fila->golesVisitanteApuesta);
            $puntos_signo = 0;
            $puntos_resultado = 0;
            $puntos_goles = 0;
            $puntos_resta = 0;
            $puntos_total = 0;
            $puntos_dif_goles = 0;
            
            if ($fila->golesLocalApuesta == $fila->golesVisitanteApuesta)
                $signo_apuesta = "X";
            else if ($fila->golesLocalApuesta > $fila->golesVisitanteApuesta)
                $signo_apuesta = "1";
            else
                $signo_apuesta = "2";
            
            // signo resultado
             if ($fila->golesLocalResultado == $fila->golesVisitanteResultado)
                $signo_resultado = "X";
            else if ($fila->golesLocalResultado > $fila->golesVisitanteResultado)
                $signo_resultado = "1";
            else
                $signo_resultado = "2";

            if ($fila->golesLocalResultado == $fila->golesLocalApuesta && $fila->golesVisitanteResultado == $fila->golesVisitanteApuesta) {
                $puntos_resultado = $datos_puntos['num_puntos_res'];
                //      echo "  Puntos resultado: 2<br>";
            }
            if ($fila->signo == $signo_apuesta) {
                $puntos_signo = $datos_puntos['num_puntos_signo'];
                //        echo "  Puntos signo: 5<br>";
            }
            if ($fila->golesLocalResultado == $fila->golesLocalApuesta) {
                $puntos_goles+=$fila->golesLocalResultado +  $datos_puntos['num_puntos_goles'];
                //          echo "  Puntos Goles local: " . ($fila->golesLocalResultado + 1) . "<br>";
            } else {
                $puntos_resta+= abs($fila->golesLocalResultado - $fila->golesLocalApuesta);
                //            echo "  Puntos Resta local: " . abs($fila->golesLocalResultado - $fila->golesLocalApuesta) . "<br>";
            }

            if ($fila->golesVisitanteResultado == $fila->golesVisitanteApuesta) {
                $puntos_goles+=$fila->golesVisitanteResultado + +  $datos_puntos['num_puntos_goles'];
                //              echo "  Puntos Goles local: " . ($fila->golesVisitanteResultado + 1) . "<br>";
            } else {
                $puntos_resta+= abs($fila->golesVisitanteResultado - $fila->golesVisitanteApuesta);
//                echo "  Puntos Resta local: " . abs($fila->golesVisitanteResultado - $fila->golesVisitanteApuesta) . "<br>";
            }
            // Calcular resta por diferencia de goles entre resultado y apuesta
            $puntos_resta_dif_goles = 0;
            if ($datos_puntos ['diferencia_puntos'] != 0)
            {
                $difGolesResultado=($fila->golesVisitanteResultado - $fila->golesLocalResultado);
                $difGolesApuesta=($fila->golesVisitanteApuesta - $fila->golesLocalApuesta);
//echo "<br>Diferencia puntos<br>$difGolesResultado -$difGolesApuesta<br>";                
                if ($difGolesResultado != $difGolesApuesta)
                {
                    // Signo resultado
//echo "Hay diferencia de goles<br>";
                    if ($signo_apuesta == $signo_resultado || $signo_apuesta == "X" || $signo_resultado == "X")
                       $puntos_resta_dif_goles = abs($difGolesResultado - $difGolesApuesta);
					else
                    {
                        if ($signo_resultado == 2)
                            $puntos_resta_dif_goles = abs($difGolesResultado + $difGolesApuesta);
                        else
                            $puntos_resta_dif_goles = abs($difGolesResultado + $difGolesApuesta);
                                
                    }
					$puntos_resta_dif_goles = abs ($difGolesResultado
						-$difGolesApuesta);
					
				
					
						
						
//echo "<b>Puntos dif: $puntos_resta_dif_goles<br></b>";
                }
                else
                    $puntos_resta_dif_goles = 0;
/*                
                if ($difGolesResultado == $difGolesApuesta){
                    $puntos_resta_dif_goles = 0;
                }
                else
                {
                    $puntos_resta_dif_goles = abs($fila->golesVisitanteResultado - $fila->golesVisitanteApuesta) +
                            abs($fila->golesLocalResultado - $fila->golesLocalApuesta);

                }
                */
                
            }
            
            


            $puntos_total = $puntos_signo + $puntos_resultado + $puntos_goles - $puntos_resta - $puntos_resta_dif_goles;
            
            // Puntos extras de tramo
            $puntos_dobles_tramo = 0;
            if ($numJornada % $this->session->userdata("tor_num_tramos_jornadas") == 0)
                $puntos_dobles_tramo = $puntos_total;
            

            //log_message('error', '[admin/jornada/puntos/' . $idTemporada . '/' . $numJornada . '] Puntos: ' . $puntos_total . " ($puntos_resultado + $puntos_signo + $puntos_goles - $puntos_resta - $puntos_resta_dif_goles)");
            $data = array('puntos_res' => $puntos_resultado,
                'puntos_signo' => $puntos_signo,
                'puntos_goles' => $puntos_goles,
                'puntos_resta' => $puntos_resta,
                'puntos_total' => $puntos_total,
                'penalizacion' => 0,
                'puntos_dif_goles' => $puntos_resta_dif_goles,
                'puntos_extra' => $puntos_dobles_tramo);

            $this->db->where('idApuesta', $fila->idApuesta);
            $this->db->update('tor_apuestas', $data);
//            log_message('error', 'SQL: '.$this->db->last_query());
  //          echo $this->db->last_query() . "<br>";
            $datos_clasificacion[$fila->idUsuario][$fila->idJornada] = array('puntos' => $puntos_total, 'extra' => $puntos_dobles_tramo); ;

        }
       // log_message('error', '[admin/jornada/puntos/' . $idTemporada . '/' . $numJornada . '] Calcular penalizaciones -------------------------------------------------');
        

        // penalizaciones
        $datos = array();
        $rs = $this->db->query("select a.idUsuario, nombre from tor_usuarios a,
                                tor_usuarios_temporada b,
                                tor_temporadas c
                                where 
                                b.idTemporada = ".$this->session->userdata('tor_idTemporada')." and
                                c.idTemporada = b.idTemporada and
                                a.idUsuario = b.idUsuario and
                                not exists (select 1 from tor_temporadas d, tor_apuestas b, tor_jornadas c
                                                  where d.idTemporada=".$this->session->userdata('tor_idTemporada')." and d.idTemporada=c.idTemporada and b.idJornada=c.idJornada and a.idUsuario=b.idUsuario and numJornada = " . $numJornada . ")");

        foreach ($rs->result() as $fila) {
			$datos = array();
            $idUsuario = $fila->idUsuario;

            //log_message('error', '[admin/jornada/puntos/' . $idTemporada . '/' . $numJornada . '] Usuario (' . $fila->idUsuario . ") " . $fila->nombre . " sin apuestas.");

            $rs2 = $this->db->query("select idJornada from tor_jornadas a , tor_temporadas b where b.idTemporada=".$this->session->userdata('tor_idTemporada')." and a.idTemporada = b.idTemporada and a.numJornada=" . $numJornada . " and not exists (select 1 from tor_apuestas b where a.idJornada=b.idJornada and b.idUsuario=" . $fila->idUsuario . ")");
            //echo $this->db->last_query()."<br>";
            foreach ($rs2->result() as $fila2) {
                $datos[$fila->idUsuario][] = $fila2->idJornada;
                //log_message('error', '[admin/jornada/puntos/' . $idTemporada . '/' . $numJornada . '] idJornada ' . $fila2->idJornada . " inexistente");
            }
            //echo "Jornadas con penalizacion: <pre>";
            //print_r($datos);
            //echo "</pre>";

            $rs3 = $this->db->query("select a.idJornada,min(puntos_total) as puntos_total
                                    from tor_apuestas a, tor_jornadas b, tor_temporadas c
                                    where c.idTemporada=".$this->session->userdata('tor_idTemporada')." and c.idTemporada=b.idTemporada and b.numJornada=" . $numJornada . " and a.idJornada=b.idJornada group by a.idJornada");
                                    //echo $this->db->last_query()."<br>";
            $puntos = array();
            $puntos_dobles_tramo = array();

            foreach ($rs3->result() as $fila3) {
                if ($fila3->puntos_total > 0)
                    $fila3->puntos_total = 0;

                $puntos[$fila3->idJornada] = $fila3->puntos_total;

                // Puntos extras de tramo
                if ($numJornada % $this->session->userdata("tor_num_tramos_jornadas") == 0)
                    $puntos_dobles_tramo[$fila3->idJornada] = $fila3->puntos_total;
                else
                    $puntos_dobles_tramo[$fila3->idJornada] = 0;

                //log_message('error', '[admin/jornada/puntos/' . $idTemporada . '/' . $numJornada . '] Puntos minimos en jornada ' . $fila3->idJornada . ": " . $fila3->puntos_total);
            }

//            echo "Puntos: <br><pre>";
//            print_r($puntos);
//            echo "</pre>";

            foreach ($datos as $idUsu => $value) {
                //              var_dump($value);
                foreach ($value as $id => $jor) {
                    $datos_insert = array('idUsuario' => $idUsu,
                        'idJornada' => $jor,
                        'golesLocal' => 0,
                        'golesVisitante' => 0,
                        'puntos_res' => 0,
                        'puntos_signo' => 0,
                        'puntos_goles' => 0,
                        'puntos_resta' => 0,
                        'puntos_total' => $puntos[$jor],
                        'puntos_extra' => $puntos_dobles_tramo[$jor],
                        'penalizacion' => 1);
                    $this->db->insert('tor_apuestas', $datos_insert);
//                    echo "<pre>";
//                    print_r($datos_insert);
//                    echo "</pre>";
//                    log_message('error', '[admin/jornada/puntos/' . $idTemporada . '/' . $numJornada . '] Insertando penalizacion usaurio ' . $idUsu . ", jornada  " . $jor . ". Puntos " . $puntos[$jor]);
//                    log_message('error', '[admin/jornada/puntos/' . $idTemporada . '/' . $numJornada . '] ' . $this->db->last_query());

                    $datos_clasificacion[$idUsu][$jor] = array('puntos' => $puntos[$jor], 'extra' => $puntos_dobles_tramo[$jor]);
                }
            }
        }

        // Calcular datos de clasificacion


        // Comprobar si hay datos de clasificacion general
        $hayGeneral = 0;
        $rsClasificacion = $this->db->query("select * from tor_clasificacion where idTemporada=".$idTemporada." and idJornada=0 and numJornada=0");
        if ($rsClasificacion->num_rows() == 1)
        {
            $hayGeneral = 1;
        }

        $numPremios = 0;
        $varlorPremios = array();
        if ($this->session->userdata('tor_imp_jornada_1') > 0 && 
            $this->session->userdata('tor_imp_jornada_2') > 0 &&
            $this->session->userdata('tor_imp_jornada_3') > 0 &&
            $this->session->userdata('tor_imp_jornada_4') > 0)
            {
            $numPremios = 4;
            $varlorPremios[] =  $this->session->userdata('tor_imp_jornada_1');
            $varlorPremios[] =  $this->session->userdata('tor_imp_jornada_2');
            $varlorPremios[] =  $this->session->userdata('tor_imp_jornada_3');
            $varlorPremios[] =  $this->session->userdata('tor_imp_jornada_4');
            }
        else
            if ($this->session->userdata('tor_imp_jornada_1') > 0 && 
                $this->session->userdata('tor_imp_jornada_2') > 0 &&
                $this->session->userdata('tor_imp_jornada_3') > 0 &&
                $this->session->userdata('tor_imp_jornada_4') == 0)
                {
                $numPremios = 3;
                $varlorPremios[] =  $this->session->userdata('tor_imp_jornada_1');
                $varlorPremios[] =  $this->session->userdata('tor_imp_jornada_2');
                $varlorPremios[] =  $this->session->userdata('tor_imp_jornada_3');
                }
            else
                if ($this->session->userdata('tor_imp_jornada_1') > 0 && 
                    $this->session->userdata('tor_imp_jornada_2') > 0 &&
                    $this->session->userdata('tor_imp_jornada_3') == 0 &&
                    $this->session->userdata('tor_imp_jornada_4') == 0)
                    {
                        $numPremios = 2;
                        $varlorPremios[] =  $this->session->userdata('tor_imp_jornada_1');
                        $varlorPremios[] =  $this->session->userdata('tor_imp_jornada_2');
                    }
                else
                {
                    $numPremios = 1;
                    $varlorPremios[] =  $this->session->userdata('tor_imp_jornada_1');
                }        

                $clasificacion2=array();
                
        foreach ($datos_clasificacion as $idUsuario => $datosJornada)
        {
            $totPuntosUsuario = 0;
            $totPuntosExtraUsuario = 0;
            $numPartidosFinalizados = 0;
            foreach ($datosJornada as $idJornada => $datospuntos)
            {
                $numPartidosFinalizados++;
                $a= $datospuntos;
                $totPuntosUsuario = $totPuntosUsuario + $datospuntos['puntos'];
                $totPuntosExtraUsuario = $totPuntosExtraUsuario + $datospuntos['extra'];

                $datos_insert_partido = array('idTemporada' => $idTemporada,
                                    'idJornada' => $idJornada,
                                    'idUsuario' => $idUsuario,
                                    'numJornada' => $numJornada,
                                    'puntos' => $datospuntos['puntos'],
                                    'posicion' => 0,
                                    'ganancia' => 0,
                                    'tipoGanancia' => 0);
                $this->db->insert('tor_clasificacion', $datos_insert_partido);
            }
            $datos_insert_general= array('idTemporada' => $idTemporada,
                                    'idJornada' => 0,
                                    'idUsuario' => $idUsuario,
                                    'numJornada' => $numJornada,
                                    'puntos' => $totPuntosUsuario,
                                    'puntosExtra' => $totPuntosExtraUsuario,
                                    'posicion' => 0,
                                    'ganancia' => 0,
                                    'tipoGanancia' => 0);
            $this->db->insert('tor_clasificacion', $datos_insert_general);
            
            
            
            $clasificacion[$totPuntosUsuario] = array('idUsuario' => $idUsuario, 'idClasificacion' => $this->db->insert_id());
            $clasificacion2[$totPuntosUsuario][] = $this->db->insert_id();

        }

        krsort($clasificacion2);
        //var_dump($clasificacion2);

        // Si se han calculado puntos para los 4 partidos, calcular tambien las ganacias
        $hayGeneral = 0;
        if ($numPartidosFinalizados === 4)
        {
            $rsClasificacion = $this->db->query("select * from tor_clasificacion where idTemporada=".$idTemporada." and idJornada=0 and numJornada=0");
            if ($rsClasificacion->num_rows() == 1)
            {
                $hayGeneral = 1;
            }
        }

        // calcular posicion en los registros de totales por jornada
        $rs = $this->db->query("select idClasificacion, idTemporada, idJornada,idUsuario,numJornada,puntos,posicion,ganancia,tipoGanancia 
                                from tor_clasificacion 
                                where idTemporada = ".$idTemporada." and numJornada=".$numJornada." and idJornada = 0 order by puntos desc");
        $pos = 1;
        $posAnt = 0;
        foreach ($rs->result() as $fila)
        {
            $this->db->set("posicion", $pos);
            $this->db->where("idClasificacion", $fila->idClasificacion);
            $this->db->update("tor_clasificacion");
            $pos++;
        }


        if ($numPartidosFinalizados === 4){
            /*$pos = 1;
            foreach($clasificacion as $puntos => $datosPos)
            {
                // Procesar solo las posiciones con premio
                if ($pos > $numPremios)
                    continue;

                if (count($datosPos) == 1)
                {
                    if ($pos < $numPremios)
                    {
                        $this->db->set('ganancia', $varlorPremios[$pos-1]);
                        $this->db->where('idClasificacion',$datosPos->idClasificacion);
                        $this->db->update('tor_clasificacion');

                    }
                    else
                    {
                        
                    }
                }
                else
                {
             
                }
                $pos++;
            }
            */
            $pos = 1;
            
            foreach($clasificacion2 as $puntos => $datosPos)
            {
                // Procesar solo las posiciones con premio
                if ($pos > $numPremios)
                    continue;

                if (count($datosPos) == 1)
                {
                    if ($pos <= $numPremios)
                    {
                        $this->db->set('ganancia', $varlorPremios[$pos-1]);
                        $this->db->where('idClasificacion',$datosPos[0]);
                        $this->db->update('tor_clasificacion');

                    }
                    else
                    {
                      
                    }
                }
                else
                {
                     // Si no estamos en la ultima posicion, quitar el ultimo premio para repartir
                     if ($numPremios == $pos)
                     $premioPos = $varlorPremios[$pos-1]; 
                 else
                     $premioPos = $varlorPremios[$pos-1] + $varlorPremios[$numPremios - 1];     
                 
                 $premioPos =round($premioPos/count($datosPos), 2);

                 foreach($datosPos as $idClasificacion)
                 {
                     
                     $this->db->set('ganancia', $premioPos);
                     $this->db->where('idClasificacion',$idClasificacion);
                     $this->db->update('tor_clasificacion');
                     $pos++;
                 } 
                 $pos--;
                }
                $pos++;
            }
        }

        // Calcular clasificacion general
        $this->db->where('idTemporada', $idTemporada);
        $this->db->where('idJornada', 0);
        $this->db->where('numJornada', 0);
        $this->db->delete('tor_clasificacion');

        $rs=$this->db->query("select idUsuario, sum(puntos) as puntos, sum(ganancia) as ganancia
                from tor_clasificacion 
                where idTemporada = ".$idTemporada." and idJornada=0 and numJornada>0 
                group by idUsuario
                order by sum(puntos) desc");
		
        // Calcular posiciones
        $pos=0;
        foreach($rs->result() as $fila)
        {
            $pos++;
            $data = array('idTemporada' => $idTemporada,
                          'idJornada' => 0,
                          'idUsuario' => $fila->idUsuario,
                          'numJornada' => 0,
                          'puntosExtra' => 0,
                          'posicion' => $pos,
                          'ganancia' => $fila->ganancia,
                          'tipoGanancia' => 0);
            $this->db->insert('tor_clasificacion', $data);
        }
		
		
		//cares
		
		// Calcular premios por finalizacion de tramo siempre que los partidos hayan finalizado
		if ($numJornada % $this->session->userdata('tor_num_tramos_jornadas') == 0 && $numPartidosFinalizados === 4)
		{
			$numJornadaIni=$numJornada - $this->session->userdata('tor_num_tramos_jornadas') + 1;
			$numJornadaFin=$numJornada;
			//$this->session->set_userdata('tor_num_tramos_jornadas', $fila->num_tramos_jornadas);
			$imp_tramo_1 = $this->session->userdata('tor_imp_tramo_1');
			$imp_tramo_2 = $this->session->userdata('tor_imp_tramo_2');
			$imp_tramo_3 = $this->session->userdata('tor_imp_tramo_3');
			$imp_tramo_4 = $this->session->userdata('tor_imp_tramo_4');
			$numTramo = $numJornada / $this->session->userdata('tor_num_tramos_jornadas');
			
			
			$ganancia_leyenda = $this->db->query("select idUsuario,numJornada, posicion, ganancia, tipoGanancia from 
												tor_clasificacion 
												where   idTemporada=".$this->session->userdata('tor_idTemporada')." and idJornada=0 and 
												numJornada >= ".$numJornadaIni." and numJornada <=".$numJornadaFin." and ganancia > 0 order by numJornada");
			
			
			
		}
		
		
		
        
         if ($origen == "")
            redirect('torpes/admin/jornada');
        else
            return;
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
            /*echo "<pre>";
            print_r($_POST);
            echo "</pre>";
            echo "<pre>";
            print_r($aux);
            echo "</pre>";
            */
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
                    //echo "ID=" . $aux[$pos]['idLocal'] . "<br>";
                    //echo "INSERTAR EQUIPO LOCAL<br>";
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
                    //echo "ID=" . $aux[$pos]['idLocal'] . "<br>";
                    //echo "INSERTAR EQUIPO VISITANTE<br>";
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
                        $fecha = explode("-", $fecha_arr[0]);

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
                    if ($datos["nombreLocal"] == "")
                        continue;
                    $fecha_arr = explode(' ', $fecha);
                    $fecha = explode("-", $fecha_arr[0]);


                    $hora = explode(":", $fecha_arr[1]);

                    if ($fecha[0] > 100)
                        $fecha_grabar = $fecha[0] . "-" . $fecha[1] . "-" . $fecha[2] . " " . $hora[0] . ":" . $hora[1] . ":00";
                    else
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
                    //echo $this->db->last_query() . "<br>";
                }
                redirect("torpes/admin/jornada");
            }
        }
        // equipos
        $eq = $this->db->query('select idEquipo,nombre from tor_equipos order by nombre');

        //$this->load->model('Torpes_model');
        //$listaEquipos = $this->Torpes_model->lista_equipos();

        $datos_salida['error'] = $texto_error;
        //$datos_salida['listaEquipos'] = $listaEquipos;
        $datos_salida['idTemporada'] = $idTemporada;
        $datos_salida['numJornada'] = $numJornada;
        $datos_salida['numPartidos'] = $numPartidos;
        $datos_salida['equipos'] = $eq;

        $this->load->view('torpes/admin/jornada_nueva', $datos_salida);
    }

    public function modificar($idTemporada = "", $numJornada = "") {

        
        if (!$this->session->userdata('tor_usuario')) {
            $datos = array();
            $this->load->view('torpes/login', $datos);
            return;
        }

        //Buscar ultima jornada
        $idTemporada = $this->session->userdata('tor_idTemporada');
        $texto_error = "";

        if ($this->input->post('numJornada') == "") {
            $rs = $this->db->query("select idJornada, idLocal,b.nombre as nombreLocal, idVisitante, c.nombre as nombreVisitante, fecha, d.activa 
                    from tor_equipos b, tor_equipos c, tor_jornadas d
                    where d.idTemporada=" . $idTemporada . " and numJornada=" . $numJornada . " and d.idLocal=b.idEquipo and d.idVisitante=c.idEquipo");

            //echo "Jornada=$numJornada<br>";
            $rs2 = $this->db->query("select numPartidos from tor_temporadas where idTemporada=" . $idTemporada);
            $fila = $rs2->row();
            $numPartidos = $fila->numPartidos;

            // equipos
            $eq = $this->db->query('select idEquipo,nombre from tor_equipos order by nombre');

            //$this->load->model('Torpes_model');
            //$listaEquipos = $this->Torpes_model->lista_equipos();

            $datos_salida['error'] = $texto_error;
            //$datos_salida['listaEquipos'] = $listaEquipos;
            $datos_salida['idTemporada'] = $idTemporada;
            $datos_salida['numJornada'] = $numJornada;
            $datos_salida['numPartidos'] = $numPartidos;
            $datos_salida['equipos'] = $eq;
            $datos_salida['partidos'] = $rs;
            

            $this->load->view('torpes/admin/jornada_modificar', $datos_salida);
return;

            //echo "Partidos=$numPartidos<br>";
        } else {
            // GRABAR DATOS

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
                   // echo "ID=" . $aux[$pos]['idLocal'] . "<br>";
                   // echo "INSERTAR EQUIPO LOCAL<br>";
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
                }

                if ($datos['idLocal'] == $datos['idVisitante'] && $datos['idVisitante'] != "")
                    $error[] = "Partido " . $pos . " con equipos iguales";
                if ($datos['fecha'] == "" && $datos['idVisitante'] != "")
                    $error[] = "Partido " . $pos . " sin fecha";

                // comprobar formato fecha
                $fecha = $datos['fecha'];
                $fecha_arr = explode(' ', $fecha);

                // validar errores solo si estan todos los datos metidos
                if ($datos['idVisitante'] != "")
                {
                    if (count($fecha_arr) != 2)
                        $error[] = "Partido " . $pos . " tiene el formato de la fecha incorrecta";
                    else {

                        $fecha = explode("-", $fecha_arr[0]);
                        if (count($fecha) != 3 && count($fecha) != 2)
                            $error[] = "Partido " . $pos . " tiene el formato de la fecha incorrecta";
                        else {
                            $hora = explode(":", $fecha_arr[1]);

                            if (count($hora) != 2 && count($hora) != 3)
                                $error[] = "Partido " . $pos . " tiene el formato de la hora incorrecta 2";
                        }
                    }
                }
            }

            $texto_error = "";
            if (count($error) > 0) {
                foreach ($error as $key => $value) {
                    $texto_error.="<p>" . $value . "</p>";
                }
            } else {
                //echo "NO hay error<br>";
                // Borrar activas previas

                foreach ($aux as $pos => $datos) {
                    if ($datos['idVisitante'] == "")
                        continue;

                    $fecha = $datos['fecha'];
                    $fecha_arr = explode(' ', $fecha);
                    $fecha = explode("-", $fecha_arr[0]);


                    $hora = explode(":", $fecha_arr[1]);

                    // Corregir horas incorrectas iphone
                    if ($hora[1] >= "00" && $hora[1] <= "14")
                        $hora[1] = "00";
                    else if ($hora[1] >= "15" && $hora[1] <= "29")
                        $hora[1] = "15";
                    else if ($hora[1] >= "30" && $hora[1] <= "44")
                        $hora[1] = "30";
                    else 
                        $hora[1] = "45";

                    $fecha_grabar = $fecha[0] . "-" . $fecha[1] . "-" . $fecha[2] . " " . $hora[0] . ":" . $hora[1] . ":00";

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
                    // si no existe jornada, crearla
                    if ($datos['idJornada'] == "")
                    {
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
                    }
                    else
                    {
                        $this->db->where('idJornada', $datos['idJornada']);
                        $this->db->set('idLocal', $datos['idLocal']);
                        $this->db->set('idVisitante', $datos['idVisitante']);
                        $this->db->set('activa', $activa);
                        $this->db->set('fecha', $fecha_grabar);

                        //var_dump($datos_insert );
                        $this->db->update('tor_jornadas', $datos_insert);
                        //echo $this->db->get_compiled_update('tor_apuestas')."<br>";
                    }
                }
            }
            redirect('torpes/admin/jornada');
        }

        // equipos
        $eq = $this->db->query('select idEquipo,nombre from tor_equipos order by nombre');

        //$this->load->model('Torpes_model');
        //$listaEquipos = $this->Torpes_model->lista_equipos();

        $datos_salida['error'] = $texto_error;
        //$datos_salida['listaEquipos'] = $listaEquipos;
        $datos_salida['idTemporada'] = $idTemporada;
        $datos_salida['numJornada'] = $numJornada;
        $datos_salida['numPartidos'] = $numPartidos;
        $datos_salida['equipos'] = $eq;



        $this->load->view('torpes/admin/jornada_modificar', $datos_salida);
    }


    function simular($numJornada = "", $puntos_signo="", $puntos_resultado="", $puntos_goles="",$puntos_resta="") {
        if (!$this->session->userdata('tor_usuario')) {
            $datos = array();
            $this->load->view('torpes/login', $datos);
            return;
        }

        if ($numJornada == "" || $puntos_signo =="" || $puntos_resultado=="" || $puntos_goles == "" || $puntos_resta=="")
        {
            echo "<strong>Forma de uso: </strong><br><br>";
            echo "www.oviedin.com/torpes/admin/jornada/simular/NUMERO_JORNADA/PUNTOS_SIGNO/PUNTOS_RESULTADO/PUNTOS_GOLES_ACERTADOS/PUNTOS_GOLES_FALLADOS";
            die();
        }
        $idTemporada=1;

  //      $this->load->model('Torpes_model');
        echo "<b>Numero Jornada: ".$numJornada."</b><br>";
    //    $datos_jornadas = $this->Torpes_model->jornada_activa($numJornada);

//        echo "<pre>";
 //       echo print_r($datos_jornadas);
  //      echo "</pre>";

//        $jornadas = $datos_jornadas['jornadas'];


        $PS = $puntos_signo;
        $PR = $puntos_resultado;
        $PG = $puntos_goles;
        $PF = $puntos_resta;


        $rs = $this->db->query("SELECT b.idApuesta, a.idJornada, d.nombre as nombreLocal, e.nombre as nombreVisitante, a.golesLocal AS golesLocalResultado, a.golesVisitante AS golesVisitanteResultado, b.golesLocal AS golesLocalApuesta, b.golesVisitante AS golesVisitanteApuesta, b.idUsuario, c.nombre, signo,
puntos_res, puntos_signo, puntos_goles, puntos_resta, puntos_total
FROM tor_jornadas a, tor_apuestas b, tor_usuarios c, tor_equipos d, tor_equipos e
            WHERE idTemporada=" . $idTemporada . " and numJornada=" . $numJornada . " and signo in ('1','X','2') AND a.idJornada = b.idJornada
AND c.idUsuario = b.idUsuario and a.idLocal = d.idEquipo
and a.idVisitante = e.idEquipo");
        echo "<br><b>Calcular puntos </b>------------------------------------------------------<br><br>";
        $partido_ant="";
        echo "<table border='1'>";
        echo "<tr>";
        echo "<td>Jugador</td>";
        echo "<td>Apuesta</td>";
        echo "<td>PR</td>";
        echo "<td>PS</td>";
        echo "<td>PG</td>";
        echo "<td>PN</td>";
        echo "<td>Puntos</td>";
        echo "<td>PR</td>";
        echo "<td>PS</td>";
        echo "<td>PG</td>";
        echo "<td>PN</td>";
        echo "<td>Puntos</td>";
        echo "<td>Dif.</td>";
        echo "</tr>";
        $puntos_nuevo = array('Carlos E.' => 0,
'Julio' => 0,
'Nacho' => 0,
'Roman' => 0,
'Fer' => 0,
'Juan Andrés' => 0,
'Carlos L.' => 0,
'Iñaki' => 0,
'Miguel' => 0,
'Doni' => 0,
'Angel' => 0);

        $puntos_anterior = array('Carlos E.' => 0,
'Julio' => 0,
'Nacho' => 0,
'Roman' => 0,
'Fer' => 0,
'Juan Andrés' => 0,
'Carlos L.' => 0,
'Iñaki' => 0,
'Miguel' => 0,
'Doni' => 0,
'Angel' => 0);

        foreach ($rs->result() as $fila) {

            if ($partido_ant != $fila->idJornada)
            {
                echo "<tr><td colspan='12'>";
                $partido_ant = $fila->idJornada;
                echo $fila->nombreLocal." ".$fila->golesLocalResultado." - ".$fila->nombreVisitante." ".$fila->golesVisitanteResultado."</td></tr>";
            }
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

            if ($fila->golesLocalResultado == $fila->golesVisitanteResultado)
                $signo_resultado = "X";
            else if ($fila->golesLocalResultado > $fila->golesVisitanteResultado)
                $signo_resultado= "1";
            else
                $signo_resultado= "2";


            if ($fila->golesLocalResultado == $fila->golesLocalApuesta && $fila->golesVisitanteResultado == $fila->golesVisitanteApuesta) {
                $puntos_resultado = $PR;
                //      echo "  Puntos resultado: 2<br>";
            }
            if ($signo_resultado == $signo_apuesta) {
                $puntos_signo = $PS;
                //        echo "  Puntos signo: 5<br>";
            }
            if ($fila->golesLocalResultado == $fila->golesLocalApuesta) {
                $puntos_goles+=($fila->golesLocalResultado + 1) * $PG;
                //          echo "  Puntos Goles local: " . ($fila->golesLocalResultado + 1) . "<br>";
            } else {
                $puntos_resta+= abs($fila->golesLocalResultado - $fila->golesLocalApuesta) * $PF;
                //            echo "  Puntos Resta local: " . abs($fila->golesLocalResultado - $fila->golesLocalApuesta) . "<br>";
            }

            if ($fila->golesVisitanteResultado == $fila->golesVisitanteApuesta) {
                $puntos_goles+=($fila->golesVisitanteResultado + 1) * $PG;
                //              echo "  Puntos Goles local: " . ($fila->golesVisitanteResultado + 1) . "<br>";
            } else {
                $puntos_resta+= abs($fila->golesVisitanteResultado - $fila->golesVisitanteApuesta) * $PF;
//                echo "  Puntos Resta local: " . abs($fila->golesVisitanteResultado - $fila->golesVisitanteApuesta) . "<br>";
            }
            $puntos_total = $puntos_signo + $puntos_resultado + $puntos_goles - $puntos_resta;

            echo "<tr>";
            echo '<td>' . $fila->nombre."</td>";
            //echo '<td>' . $fila->golesLocalResultado . " - " . $fila->golesVisitanteResultado . "</td>";
            echo "<td>" . $fila->golesLocalApuesta . " - " . $fila->golesVisitanteApuesta."</td>";


            echo "<td>". $puntos_resultado . "</td><td>". $puntos_signo . "</td><td>". $puntos_goles . "</td><td>". $puntos_resta. "</td><td><b>".$puntos_total."</b></td>";
            echo "<td>". $fila->puntos_res. "</td><td>". $fila->puntos_signo . "</td><td>". $fila->puntos_goles . "</td><td>". $fila->puntos_resta. "</td><td><b>".$fila->puntos_total."</b></td>";
            echo "<td><font color='blue'>".($puntos_total - $fila->puntos_total )."</font></td>";
            echo "</tr>";

            $puntos_anterior[$fila->nombre] += $fila->puntos_total;
            $puntos_nuevo[$fila->nombre] += $puntos_total;

        }
        echo "</table>";
        echo "<br><br>";
        echo "<table>";
        echo "<tr>";
        echo "<td>Jugador</td>";
        echo "<td>Puntos Antes</td>";
        echo "</tr>";
        $i=0;
        arsort($puntos_anterior );
        foreach ($puntos_anterior as $key => $value)
        {
            ++$i;
            echo "<tr><td>".$i.". ".$key."</td><td>".$value."</td></tr>";
        }

        echo "</table>";

        echo "<br><br>";
        echo "<table>";
        echo "<tr>";
        echo "<td>Jugador</td>";
        echo "<td>Puntos Nuevos</td>";
        echo "</tr>";
        arsort($puntos_nuevo );
        $i=0;
        foreach ($puntos_nuevo as $key => $value)
        {
            ++$i;
            echo "<tr><td>".$i.". ".$key."</td><td>".$value."</td></tr>";
        }

        echo "</table>";

    }

}
