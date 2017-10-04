<?php
$rs = $this->db->query("select nombreUsuario,
                      sum(if(golesLocalApuesta=golesLocalResultado,1,0)) as totGolesLocal,
                      sum(if(golesVisitanteApuesta=golesVisitanteResultado,1,0)) as totGolesVisitante,
                      (sum(if(golesLocalApuesta=golesLocalResultado,1,0)) +
                      sum(if(golesVisitanteApuesta=golesVisitanteResultado,1,0))) as  total,
                      count(1)*2 as totalJornadas
                      from v_tor_consulta_nueva
                      where TemporadaActiva=1
                      and jornadaActiva=0
                      group by nombreUsuario
                      order by (sum(if(golesLocalApuesta=golesLocalResultado,1,0)) +
                      sum(if(golesVisitanteApuesta=golesVisitanteResultado,1,0))) desc");

if ($rs->num_rows() == 0)
  return;


$rs2 = $this->db->query("select nombreUsuario,count(1) as total,
sum(if (equipoLocal='F.C. Barcelona' or equipoVisitante='F.C. Barcelona',1,0)) as barcelona,
sum(if (equipoLocal='Sporting de Gijón' or equipoVisitante='Sporting de Gijón',1,0)) as gijon,
sum(if (equipoLocal='Real Madrid' or equipoVisitante='Real Madrid',1,0)) as madrid,
sum(if (equipoLocal='Real Oviedo' or equipoVisitante='Real Oviedo',1,0)) as oviedo
                      from v_tor_consulta_nueva
                      where TemporadaActiva=1

                      and puntos_res>0
                      group by nombreUsuario
                      ");

$datos=array();
foreach($rs2->result() as $fila2)
{
    
    $datos[$fila2->nombreUsuario]=array('F.C. Barcelona' => $fila2->barcelona,
    'Sporting de Gijón' => $fila2->gijon,
    'Real Oviedo' => $fila2->oviedo,
    'Real Madrid' => $fila2->madrid,
    'Otros' => $fila2->total - ($fila2->barcelona + $fila2->gijon + $fila2->oviedo + $fila2->madrid));
}

$rs3 = $this->db->query("select nombreUsuario,count(1) as total,
sum(if (equipoLocal='F.C. Barcelona' or equipoVisitante='F.C. Barcelona',1,0)) as barcelona,
sum(if (equipoLocal='Sporting de Gijón' or equipoVisitante='Sporting de Gijón',1,0)) as gijon,
sum(if (equipoLocal='Real Madrid' or equipoVisitante='Real Madrid',1,0)) as madrid,
sum(if (equipoLocal='Real Oviedo' or equipoVisitante='Real Oviedo',1,0)) as oviedo
                      from v_tor_consulta_nueva
                      where TemporadaActiva=1
                      and puntos_goles>0
                      group by nombreUsuario");

$datos2=array();
foreach($rs3->result() as $fila2)
{
    
    $datos2[$fila2->nombreUsuario]=array('F.C. Barcelona' => $fila2->barcelona,
    'Sporting de Gijón' => $fila2->gijon,
    'Real Oviedo' => $fila2->oviedo,
    'Real Madrid' => $fila2->madrid,
    'Otros' => $fila2->total - ($fila2->barcelona + $fila2->gijon + $fila2->oviedo + $fila2->madrid));
}



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
                <td style="background:#fafafa"><center>Visitante</center></td>
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

                    <td style="background:#ffffff">
                    <?php
                    $res=$datos[$fila->nombreUsuario];
                    $texto="";
                    foreach($res as $nombre => $tot)
                    {
                        if ($texto != "")
                            $texto.="<br>";
                        $texto.=$nombre.": ".$tot;
                    } 
                    ?>
                    <span data-toggle="tooltip" data-placement="top" title="" data-html="true" 
                    data-original-title="<?=$texto?>">
                    
                    <?=$fila->nombreUsuario?>
                    
                    </span>


                    </td>
                    <td style="background:#ffffff" class="text-center">
                    <?php
                    $res=$datos2[$fila->nombreUsuario];
                    $texto="";
                    foreach($res as $nombre => $tot)
                    {
                        if ($texto != "")
                            $texto.="<br>";
                        $texto.=$nombre.": ".$tot;
                    } 
                    ?>
                    <span data-toggle="tooltip" data-placement="top" title="" data-html="true" 
                    data-original-title="<?=$texto?>">

                        <?=$fila->totGolesLocal?>
                        </span>
                        
                         (<?=round($fila->totGolesLocal * 100 / $fila->totalJornadas,0)?>%)</td>
                    <td style="background:#ffffff" class="text-center"><?=$fila->totGolesVisitante?> (<?=round($fila->totGolesVisitante * 100 / $fila->totalJornadas,0)?>%)</td>
                    <td style="background:#ffffff" class="text-center"><?=$fila->total?> (<?=round($fila->total * 100 / $fila->totalJornadas,0)?>%)</td>
                </tr>
                <?php } ?>
                </tbody>
            </table>

        </div>
