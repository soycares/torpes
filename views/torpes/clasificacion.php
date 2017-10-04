<?php $this->load->view('torpes/cabecera') ?>


 
<div class="row">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  <script>
 
  $( document ).ready(function() {

          $("#idJornada").change(function() {
 
  $(location).attr('href','<?=site_url('torpes/jornada/clasificacion/')?>/'+$('#idJornada').val()); 
 
      
       
       
});

          $("#idJornada2").change(function() {
 
  $(location).attr('href','<?=site_url('torpes/jornada/clasificacion/')?>/'+$('#idJornada2').val()); 
 
      
       
       
});






  });
  </script>
  
    <div class="col-md-9" id="blog-post">
        <div class="row">
            <?php
            
            // Ver partidos en juego o finalizados de la jornada
            if ($partidos_resumen->num_rows() > 0) {
                ?>
                <div class="heading" style="text-align: center">
                    <h2>Puntos por Jornada <?=$numJornada?></h2>
                </div>
            <?php
            
            

                echo "<h4>Jornada ".form_dropdown('idJornada2', $jornadas, $numJornada,'id="idJornada2"') . '</h4><br><br>';
            ?>
            <table class="table">
                <tbody>
                <?php
                    echo "<tr>";
                    echo '<td style="background:#fafafa"><b>Usuario</b></td>';
                    
                    
                    echo '<td style="background:#fafafa" class="text-center"><b>PR</b></td>';
                    echo '<td style="background:#fafafa" class="text-center"><b>PS</b></td>';
                    echo '<td style="background:#fafafa" class="text-center"><b>PG</b></td>';
                    echo '<td style="background:#fafafa" class="text-center"><b>PN</b></td>';
                    echo '<td style="background:#fafafa" class="text-center"><b>PD</b></td>';
                    echo '<td style="background:#fafafa" class="text-center"><b>TOTAL</b></td>';
                    
                    
                    echo "</tr>";
                    
                $partido_anterior="";
                foreach ($partidos_resumen->result() as $fila) {
                    
                    $partido=$fila->nombreUsuario;

                    echo "<tr>";
                    echo '<td>'.$fila->nombreUsuario."</td>";
                    
                    echo '<td class="text-center">'.$fila->puntos_res."</td>";
                    echo '<td class="text-center">'.$fila->puntos_signo."</td>";
                    echo '<td class="text-center">'.$fila->puntos_goles."</td>";
                    echo '<td class="text-center">'.$fila->puntos_resta."</td>";
                    echo '<td class="text-center">'.$fila->puntos_dif_goles."</td>";
                    echo '<td style="color:#C91818"><strong><center>'.$fila->puntos_total."</center></strong></td>";
                    
                    
                    echo "</tr>";
                }
                ?>
                </tbody></table>
                <?php
            }
            ?>
                



            <div class="col-sm-12 text-center">
                &nbsp;
                <div class="alert alert-info" role="alert"><b>PR</b>: Puntos resultado acertado - <b>PS</b>: Puntos signo acertado - <b>PG</b>: Puntos goles acertados 
                    - <b>PD</b>: Puntos que restan diferencia de goles</div>

            </div>
        </div>
        
        
        <div class="row">
            <?php
            // Ver partidos en juego o finalizados de la jornada
            if ($partidos->num_rows() > 0) {
                ?>
                <div class="heading" style="text-align: center">
                    <h2>Detalle de Puntos por Jornada <?=$numJornada?></h2>
                </div>
            <?php
           
                echo "<h4>Jornada ".form_dropdown('idJornada', $jornadas, $numJornada,'id="idJornada"') . '</h4><br><br>';
            ?>
            <table class="table">
                <tbody>
                <?php
                    echo "<tr class='text-center'>";
                    echo '<td style="background:#fafafa"><b>Partido</b></td>';
                    echo '<td style="background:#fafafa"><b>Ap</b></td>';
                    echo '<td style="background:#fafafa"><b>Res</b></td>';
                    
                    
                    echo '<td style="background:#fafafa"><b>PR</b></td>';
                    echo '<td style="background:#fafafa"><b>PS</b></td>';
                    echo '<td style="background:#fafafa"><b>PG</b></td>';
                    echo '<td style="background:#fafafa"><b>PN</b></td>';
                    echo '<td style="background:#fafafa"><b>PD</b></td>';
                    echo '<td style="background:#fafafa"><b>TOTAL</b></td>';
                    
                    
                    echo "</tr>";
                    $fecha_ahora = date('Y-m-d H:i:s');
                $partido_anterior="";
                $puntos =0;
                $puntos_arr = array();
                foreach ($partidos->result() as $fila) {
                    
                    $partido=$fila->nombreUsuario;
   

                    if ($partido_anterior == "")
                        $partido_anterior = $partido;
                    
                    if ($partido != $partido_anterior ){
                        //echo $partido_anterior." - ".$puntos."<br>";;
                        $puntos_arr[$partido_anterior]=$puntos;
                        
                        $partido_anterior = $partido;
                        
                        
                        
                        $puntos =0;
                        
                    }
                    $puntos += $fila->puntos_total;
                    
                }
$puntos_arr[$partido_anterior]=$puntos;

                $partido_anterior="";
                
                foreach ($partidos->result() as $fila) {  

                 

if (strtotime($fila->fecha ) > strtotime($fecha_ahora)) 
continue;

  
                    $partido=$fila->nombreUsuario;
                    if ($partido != $partido_anterior)
                    {
                        if ($partido_anterior == "")
                            $partido_anterior = $partido;
                        
                        $partido_anterior = $partido;
                        echo "<tr>";
                        echo '<td colspan="8" style="background:#fafafa"><h4>'.$partido."</h4></td>";
                        echo '<td style="background:#fafafa" class="text-center"><h4>'.$puntos_arr[$partido]."</h4></td>";
                        echo "</tr>";
                        
                    }
                    echo "<tr>";
                    if ($fila->penalizacion == 1)
                        echo '<td>'.$fila->local." - ".$fila->visitante."&nbsp;&nbsp; <span class='label label-danger'>Penalizado</span></td>";
                    else
                        echo '<td>'.$fila->local." - ".$fila->visitante."</td>";
                    echo '<td class="text-center">'.$fila->golesLocalApuesta." - ".$fila->golesVisitanteApuesta."</td>";
                    
                    echo '<td class="text-center">'.$fila->golesLocalResultado." - ".$fila->golesVisitanteResultado."</td>";
                    
                    echo '<td class="text-center">'.$fila->puntos_res."</td>";
                    echo '<td class="text-center">'.$fila->puntos_signo."</td>";
                    echo '<td class="text-center">'.$fila->puntos_goles."</td>";
                    echo '<td class="text-center">'.$fila->puntos_resta."</td>";
                    echo '<td class="text-center">'.$fila->puntos_dif_goles."</td>";
                    echo '<td style="color:#C91818"><strong><center>'.$fila->puntos_total."</center></strong></td>";
                    
                    
                    echo "</tr>";
                }
                ?>
                </tbody></table>
                <?php
            }
            ?>
                



            <div class="col-sm-12 text-center">
                &nbsp;

            </div>
        </div>
      
    </div>
    <div class="col-md-3" id="blog-post">
        <br>
        <?php $this->load->view('torpes/clasificacion_peq_jor'); ?>
        <?php $this->load->view('torpes/clasificacion_peq'); ?>
    </div>
</div>




<?php $this->load->view('torpes/pie'); ?>