<?php $this->load->view('torpes/cabecera') ?>
 
<div class="row">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  <script>
 
  $( document ).ready(function() {

          $("#idUsuario").change(function() {
 
  $(location).attr('href','<?=site_url('torpes/estadisticas/')?>/'+$('#idUsuario').val()); 
 
      
       
       
});



  });
 
  </script>
  
    <div class="col-md-9" id="blog-post">
        <div class="row">
            <?php
            
            // Ver estadisticas por usuario
            if ($estadisticas_resumen->num_rows() > 0) {
                ?>
                <div class="heading" style="text-align: center">
                    <h2>Estadisticas Resumen</h2>
                </div>
            <?php
                echo "<h4>Usuario ".form_dropdown('idUsuario', $listaUsuarios, $idUsuario,'id="idUsuario"') . '</h4><br><br>';
            ?>
            <table class="table">
                <tbody>
                <?php
                    echo "<tr>";
                    echo '<td style="background:#fafafa"><b>Usuario</b></td>';
                    
                    
                    echo '<td style="background:#fafafa"><b>Tot.</b></td>';
                    echo '<td style="background:#fafafa"><b>Res.</b></td>';
                    echo '<td style="background:#fafafa"><b>Sig.</b></td>';
                    echo '<td style="background:#fafafa"><b>GL</b></td>';
                    echo '<td style="background:#fafafa"><b>GV</b></td>';
                    //echo '<td style="background:#fafafa"><b>Acumulado</b></td>';
                    echo "</tr>";
                    
                foreach ($estadisticas_resumen->result() as $fila) {
                    echo "<tr>";
                    echo '<td>'.$fila->nombreUsuario."</td>";
                    echo '<td style="color:#C91818"><strong><center>'.$fila->numPartidos."</center></td>";
                    echo '<td><center>'.$fila->aciertosResultado." <font color='#38A7BB'>(".round($fila->aciertosResultado / $fila->numPartidos * 100,0)."%)</font></center></td>";
                    echo '<td><center>'.$fila->aciertosSigno." <font color='#38A7BB'>(".round($fila->aciertosSigno / $fila->numPartidos * 100,0)."%)</font></center></td>";
                    echo '<td><center>'.$fila->aciertosGolesLocal." <font color='#38A7BB'>(".round($fila->aciertosGolesLocal / $fila->numPartidos * 100,0)."%)</font></center></td>";
                    echo '<td><center>'.$fila->aciertosGolesVisitante." <font color='#38A7BB'>(".round($fila->aciertosGolesVisitante / $fila->numPartidos * 100,0)."%)</font></center></td>";
                    //echo '<td style="color:#C91818"><strong><center>'.$fila->puntos_acumulados."</center></strong></td>";
                    echo "</tr>";
                }
                ?>
                </tbody></table>
                <?php
            }
            ?>
            <div class="col-sm-12 text-center">
<div class="alert alert-info" role="alert"><b>Tot.</b>: Total partidos acertados - 
<b>Res.</b>: Partidos acertados - 
<b>Sig.</b>: Partidos acertados - 
<b>GL</b>: Partidos con Goles del equipo local acertados -
<b>GV</b>: Partidos con Goles del equipo visitante acertados
</div>
</div>
            
            <?php
            // Ver estadisticas por usuario y equipo
            if ($estadisticas_equipo_usuario->num_rows() > 0) {
                ?>
                <div class="heading" style="text-align: center">
                    <h2>Estadisticas por equipo y usuario</h2>
                </div>
            <?php
                echo "<h4>Usuario ".form_dropdown('idUsuario', $listaUsuarios, $idUsuario,'id="idUsuario"') . '</h4><br><br>';
            ?>
            <table class="table">
                <tbody>
                <?php
                echo "<tr>";
                echo '<td style="background:#fafafa"><b>Usuario</b></td>';

 echo '<td style="background:#fafafa"><b>Tot.</b></td>';
                    echo '<td style="background:#fafafa"><b>Res.</b></td>';
                    echo '<td style="background:#fafafa"><b>Sig.</b></td>';
                    echo '<td style="background:#fafafa"><b>GL</b></td>';
                    echo '<td style="background:#fafafa"><b>GV</b></td>';
                //echo '<td style="background:#fafafa"><b>Acumulado</b></td>';
                    
                echo "</tr>";
                $equipo_anterior= "";
                foreach ($estadisticas_equipo_usuario->result() as $fila) {
                    if ($equipo_anterior != $fila->equipo)
                    {
                        echo "<tr >";
                        echo '<td colspan="6" style="background:#38A7BB;color:white"><b>'.$fila->equipo."</b></td>";
                        echo "<tr>";
                        $equipo_anterior = $fila->equipo;
                    }
                    echo "<tr>";
                    echo '<td>'.$fila->nombreUsuario."</td>";
                     
                             
              
                    echo '<td style="color:#C91818"><strong><center>'.$fila->numPartidos."</center></td>";
                    echo '<td><center>'.$fila->aciertosResultado." <font color='#38A7BB'>(".round($fila->aciertosResultado / $fila->numPartidos * 100,0)."%)</font></center></td>";
                    echo '<td><center>'.$fila->aciertosSigno." <font color='#38A7BB'>(".round($fila->aciertosSigno / $fila->numPartidos * 100,0)."%)</font></center></td>";
                    echo '<td><center>'.$fila->aciertosGolesLocal." <font color='#38A7BB'>(".round($fila->aciertosGolesLocal / $fila->numPartidos * 100,0)."%)</font></center></td>";
                    echo '<td><center>'.$fila->aciertosGolesVisitante." <font color='#38A7BB'>(".round($fila->aciertosGolesVisitante / $fila->numPartidos * 100,0)."%)</font></center></td>";
                    //echo '<td style="color:#C91818"><strong><center>'.$fila->puntos_acumulados."</center></strong></td>";
                    
                    
                    echo "</tr>";
                }
                ?>
                </tbody></table>
                <?php
            }
            
            ?>
        </div>
        
        
        <div class="row">
                        <div class="col-sm-12 text-center">
<div class="alert alert-info" role="alert"><b>Tot.</b>: Total partidos acertados - 
<b>Res.</b>: Partidos acertados - 
<b>Sig.</b>: Partidos acertados - 
<b>GL</b>: Partidos con Goles del equipo local acertados -
<b>GV</b>: Partidos con Goles del equipo visitante acertados
</div>
</div>
        </div>
      
    </div>
    <div class="col-md-3" id="blog-post">
        <br>
<?php $this->load->view('torpes/clasificacion_peq_jor'); ?>
        <?php $this->load->view('torpes/clasificacion_peq'); ?>
        <?php $this->load->view('torpes/clasificacion_peq_potras'); ?>     
        <?php $this->load->view('torpes/ganancias_peq'); ?>    </div>
</div>



<?php $this->load->view('torpes/pie'); ?>