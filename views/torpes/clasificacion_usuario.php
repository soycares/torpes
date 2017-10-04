<?php $this->load->view('torpes/cabecera') ?>
 
<div class="row">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  <script>
 
  $( document ).ready(function() {

          $("#idUsuario").change(function() {
 
  $(location).attr('href','<?=site_url('torpes/jornada/clasificacion_usuario/')?>/'+$('#idUsuario').val()); 
 
      
       
       
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
                    <h2>Puntos Usuario <?=$nombreUsuario?></h2>
                </div>
            <?php
                echo "<h4>Usuario ".form_dropdown('idUsuario', $listaUsuarios, $idUsuario,'id="idUsuario"') . '</h4><br><br>';
            ?>
            <table class="table">
                <tbody>
                <?php
                    echo "<tr>";
                    echo '<td style="background:#fafafa"><b>Jornada</b></td>';
                    
                    
                    echo '<td style="background:#fafafa"><b>PR</b></td>';
                    echo '<td style="background:#fafafa"><b>PS</b></td>';
                    echo '<td style="background:#fafafa"><b>PG</b></td>';
                    echo '<td style="background:#fafafa"><b>PN</b></td>';
                    echo '<td style="background:#fafafa"><b>Total</b></td>';
                    echo '<td style="background:#fafafa"><b>Acumulado</b></td>';
                    
                    echo "</tr>";
                    
                foreach ($partidos_resumen->result() as $fila) {
                    echo "<tr>";
                    echo '<td>'.$fila->numJornada."</td>";
                    
                    echo '<td>'.$fila->puntos_res."</td>";
                    echo '<td>'.$fila->puntos_signo."</td>";
                    echo '<td>'.$fila->puntos_goles."</td>";
                    echo '<td>'.$fila->puntos_resta."</td>";
                    echo '<td style="color:#C91818"><strong><center>'.$fila->puntos_total."</center></strong></td>";
                    echo '<td style="color:#C91818"><strong><center>'.$fila->puntos_acumulados."</center></strong></td>";
                    
                    
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
        
        
        <div class="row">
            
        </div>
      
    </div>
    <div class="col-md-3" id="blog-post">
        <br>
        <?php $this->load->view('torpes/clasificacion_peq_jor'); ?>
        <?php $this->load->view('torpes/clasificacion_peq'); ?>
    </div>
</div>



<?php $this->load->view('torpes/pie'); ?>