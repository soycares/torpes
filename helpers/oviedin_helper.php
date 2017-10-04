<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function tipo_img($txt)
{ 
    
     $pos_ini = 0;
                                    for ($i=0; $i < substr_count($txt,"<img "); ++$i)
                                    {
                                        // localizar <img y >
                                  
    $pos_img_ini = strpos($txt,'<img');
    $pos_img_fin = strpos($txt,'>',$pos_img_ini);
    $pos_img_src_ini = strpos($txt,'src="',$pos_img_ini);
    $pos_img_src_fin = strpos($txt,'"',$pos_img_src_ini+5);
    echo "pos_img_ini=$pos_img_ini<br>";
    echo "pos_img_fin=$pos_img_fin<br>";
    echo "pos_img_src_ini=$pos_img_src_ini<br>";
    echo "pos_img_src_fin=$pos_img_src_fin<br>";
    echo "<br>".substr($txt,$pos_img_src_ini + 5, 1);
    echo "<br>".substr($txt,$pos_img_src_ini + 5, $pos_img_src_fin - $pos_img_src_ini - 5);


    $var = getimagesize("http://www.oviedin.com/".substr($txt,$pos_img_src_ini + 5, $pos_img_src_fin - $pos_img_src_ini - 5));

    if ($var[0] > 300)
    {
       return 1;
    }
    else
    {
        return 0;              
    }
                                    }
     
                                    
}

function datos_apuesta($jornada, $idUsuario)
{
    $CI =& get_instance();
    $datos = "";
    
    $rs = $CI->db->query("SELECT * FROM tor_apuestas where idJornada=".$jornada." and idUsuario=".$idUsuario);
    if ($rs->num_rows() > 0)
    {
        foreach ($rs->result() as $fila)
        {
            $datos[$fila->idJornada] = array("golesLocal" => $fila->golesLocal,
                "golesVisitante" => $fila->golesVisitante,
                'cerrada' => $fila->cerrada);
        }
    }
    return $datos;
}

function fecha_hora($fecha)
{
    $fecha_arr = explode(" ",$fecha);
    
    if (count($fecha_arr) != 2)
        return $fecha;
    // fecha
    $fecha_2_arr = explode("-",$fecha_arr[0]);
    if (count($fecha_2_arr) != 3)
        return $fecha;
    
    $fecha = $fecha_2_arr[2]."/".$fecha_2_arr[1];
    
    // hora
    $hora_arr = explode(":",$fecha_arr[1]);
    if (count($hora_arr) != 3)
        return $fecha;
    
    if ($hora_arr[0] == 0 || $hora_arr[0] == "00")
        return $fecha;
    
    $fecha .= " ".$hora_arr[0].":".$hora_arr[1];

    return $fecha;
    
}


// Devuelve true si la jornada activa está cerrada
function jornada_cerrada()
{ 
    $CI =& get_instance();
    // fecha de cierre es la menor de las fechas de la jornada
    $rs_3 = $CI->db->query("SELECT min(fecha) as fecha, numJornada
        FROM tor_temporadas d, tor_jornadas a
        WHERE d.idTemporada=".$CI->session->userdata('tor_idTemporada')." 
        and d.idTemporada=a.idTemporada
        and a.activa = 1
        ");
    
    $fila = $rs_3->row();
    $fechaCierre = $fila->fecha;
    $numJornada = $fila->numJornada;
    if ($numJornada == "" || $numJornada == null)
        $numJornada=1;
    // contar usuarios activos
    $rs_3 = $CI->db->query("SELECT count(1) as total
        FROM tor_usuarios
        WHERE activo = 1");
    $fila = $rs_3->row();
    $numUsuarios=$fila->total;
    
    // Contar jugadores que han hecho la apuesta
    $rs_3 = $CI->db->query("select distinct nombreUsuario from v_tor_consulta_nueva where numJornada=".$numJornada." and idTemporada=".$CI->session->userdata('tor_idTemporada'));
     
    
            
    if ($rs_3->num_rows() == $numUsuarios ||
        strtotime($fechaCierre) < strtotime(date('Y-m-d H:i:s')))
        return 1;
    else
        return 0;
}


// Dibuja un check (si/no) para la pagina de configuracion
function crear_check($valor_dato, $nombre_dato, $etiqueta)
{
    $check = "";
    if ($valor_dato == 1)
        $check = 'checked=""';

    echo '<div class="form-group">';
    echo '  <label class="col-md-3 control-label" for="profileFirstName">'.$etiqueta.'</label>';
    echo '  <div class="col-md-8">';
    echo '      <label class="switch switch-info"> ';
    echo '          <input type="checkbox" '.$check.' name="'.$nombre_dato.'">';
    echo '          <span class="switch-label" data-on="SI" data-off="NO"></span>';
    echo '      </label>';
    echo '  </div>';
    echo '</div>';
}

// Dibuja un check (si/no) para la pagina de configuracion
function crear_check_input($valor_dato, $nombre_dato, $etiqueta, $valor_dato_2, $nombre_dato_2)
{
    $check = "";
    if ($valor_dato == 1)
        $check = 'checked=""';

    echo '<div class="form-group">';
    echo '  <label class="col-md-3 control-label" for="profileFirstName">'.$etiqueta.'</label>';
    echo '  <div class="col-md-2">';
    echo '      <label class="switch switch-info"> ';
    echo '          <input type="checkbox" '.$check.' name="'.$nombre_dato.'">';
    echo '          <span class="switch-label" data-on="SI" data-off="NO"></span>';
    echo '      </label>';
    echo '  </div>';
    echo '  <div class="col-md-2">';
    echo '          <input type="text" value="'.$valor_dato_2.'" min="1" max="25" name="'.$nombre_dato_2.'" class="form-control stepper">';
    echo '  </div>';
    echo '  <div class="col-md-4">';
    echo '          ';
    echo '  </div>';
    echo '</div>';
}