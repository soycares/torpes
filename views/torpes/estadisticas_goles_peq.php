<?php
$rs = $this->db->query("select nombreUsuario,
                      sum(if(golesLocalApuesta=golesLocalResultado,1,0)) as totGolesLocal,
                      sum(if(golesVisitanteApuesta=golesVisitanteResultado,1,0)) as totGolesVisitante,
                      (sum(if(golesLocalApuesta=golesLocalResultado,1,0)) +
                      sum(if(golesVisitanteApuesta=golesVisitanteResultado,1,0))) as  total,
                      count(1)*2 as totalJornadas
                      from v_tor_consulta_nueva
                      where TemporadaActiva=0
                      and jornadaActiva=0
                      group by nombreUsuario
                      order by (sum(if(golesLocalApuesta=golesLocalResultado,1,0)) +
                      sum(if(golesVisitanteApuesta=golesVisitanteResultado,1,0))) desc");

if ($rs->num_rows() == 0)
  return;


 ?>
        <div class="panel panel-default sidebar-menu">
            <div class="panel-heading">
                <h3 class="panel-title">ESTADISTICAS GOLES</h3>
            </div>

            <table class="table">
                <tbody>
                    <tr>
                        <td style="background:#fafafa">Nombre</td>
                <td style="background:#fafafa"><center>Local</center></td>
                <td style="background:#fafafa"><center>Vis.</center></td>
                <td style="background:#fafafa"><center>Total</center></td>
                </tr>
                <?php

                        // Ganancias
        //$this->load->model('Torpes_model');
        //$datos = $this->Torpes_model->ganancias_usuario();

        //if ($datos == null)
        //return;
                $i=0;
                foreach ($rs->result() as $fila){
                    $i++;

                ?>
                <tr>

                    <td style="background:#ffffff"><?=$fila->nombreUsuario?></td>
                    <td style="background:#ffffff" class="text-center"><?=$fila->totGolesLocal?> </td>
                    <td style="background:#ffffff" class="text-center"><?=$fila->totGolesVisitante?> </td>
                    <td style="background:#ffffff" class="text-center"><?=$fila->total?> (<?=round($fila->total * 100 / $fila->totalJornadas,0)?>%)</td>
                </tr>
                <?php } ?>
                </tbody>
            </table>

        </div>
