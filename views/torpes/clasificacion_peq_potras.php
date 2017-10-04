<?php
// No mostrar la clasificacion
return;

    $rs = $this->db->query("select numJornada from tor_temporadas a, tor_jornadas b where a.idTemporada=".$this->session->userdata('tor_idTemporada')." and a.idTemporada=b.idTemporada and b.activa=1");
    if ($rs->num_rows() > 0)
    {
    $fila=$rs->row();
    $numJornada=$fila->numJornada;
    }


?>
<div class="panel panel-default sidebar-menu">
            <div class="panel-heading">
                <h3 class="panel-title">Clasificación Potras</h3>
            </div>

            <table class="table">
                <tbody>
                    <tr>
                        <td style="background:#fafafa"><center>Pos.</center></td>
                <td style="background:#fafafa"><center>Nombre</center></td>
                <td style="background:#fafafa"><center>Puntos</center></td>

                </tr>
                <?php
                if ($numJornada > 1)
                {
                    
                    $rs = $this->db->query("SELECT nombreUsuario, sum(puntos_total_potra) as puntos_total,sum(puntos_total) as puntos_total_usuario  FROM v_tor_consulta
                        WHERE numJornada < ".($numJornada -1 )." 
                        group by nombreUsuario
                        order by sum(puntos_total_potra) desc");
                    $jornada_ant=array();
                    $i=1;
                    foreach ($rs->result() as $fila){
                        $jornada_ant[$fila->nombreUsuario] = array('puntos' => $fila->puntos_total, 'posicion' => $i);
                        ++$i;
                    }
                }
                $rs = $this->db->query("SELECT nombre,sum(puntos_total)-sum(puntos_total_potra) as total, sum(puntos_total_potra) as puntos_total,sum(puntos_total) as puntos_total_usuario FROM tor_apuestas a, tor_usuarios b, tor_temporadas c, tor_jornadas d                         WHERE a.idUsuario=b.idUsuario 
                        and c.idTemporada=".$this->session->userdata('tor_idTemporada')."
                        and c.idTemporada=d.idTemporada
                        and d.idJornada = a.idJornada
                        group by nombre
                        order by sum(puntos_total)-sum(puntos_total_potra) desc");
                $i=0;
                if ($rs->num_rows() > 0)
    {
                foreach ($rs->result() as $fila){
                       $i++;
                   }

                ?>
                <tr>
                    <td style="background:#ffffff"><center><?=$i?></center></td>
                    <td style="background:#ffffff"><?=$fila->nombre?></td>
                    <td style="background:#ffffff">
                        <?php 
                        
                        if ($fila->puntos_total - $fila->puntos_total_usuario > 0)
                            echo "<font color='green'><b>".($fila->total)."</b></font>&nbsp;(".$fila->puntos_total.")";
                        else                            
                            echo "<font color='red'><b>".($fila->total)."</b></font>&nbsp;(".$fila->puntos_total.")";
/*                        if ($i < $jornada_ant[$fila->nombre]['posicion'])
                            echo '<i class="fa fa-level-down fa-1" style="color:#E01F1F"></i>';
                        else if ($i < $jornada_ant[$fila->nombre]['posicion'])
                            echo '<i class="fa fa-level-up fa-1" style="color:#1B9B33"></i>';
  */                      
                            
                        ?>
                    </td>

                </tr>
                <?php } ?>
                </tbody>
            </table>

        </div>