<?php $this->load->view('torpes/cabecera') ?>
 
<div class="row">
    
    <div class="col-md-9" id="blog-post">
        <div class="row">
            <?php
            
            // Ver partidos en juego o finalizados de la jornada
            if ($clasificacion->num_rows() > 0) {
                ?>
                <div class="heading" style="text-align: center">
                    <h2>Clasificación General</h2>
                </div>
            <table class="table">
                <tbody>
                <?php
                    echo "<tr>";
                    echo '<td style="background:#fafafa"><b>Pos.</b></td>';
                    echo '<td style="background:#fafafa"><b>Usuario</b></td>';
                    echo '<td style="background:#fafafa"><b>PR</b></td>';
                    echo '<td style="background:#fafafa"><b>PS</b></td>';
                    echo '<td style="background:#fafafa"><b>PG</b></td>';
                    echo '<td style="background:#fafafa"><b>PN</b></td>';
                    echo '<td style="background:#fafafa"><b>TOTAL</b></td>';
                    
                    
                    echo "</tr>";
                    
                    $i=1;
                foreach ($clasificacion->result() as $fila) {
                    echo "<tr>";
                    echo '<td>'.$i."</td>";
                    echo '<td>'.$fila->nombreUsuario."</td>";
                    
                    echo '<td>'.$fila->puntos_res."</td>";
                    echo '<td>'.$fila->puntos_signo."</td>";
                    echo '<td>'.$fila->puntos_goles."</td>";
                    echo '<td>'.$fila->puntos_resta."</td>";
                    echo '<td style="color:#C91818"><strong><center>'.$fila->puntos_total."</center></strong></td>";
                    
                    
                    echo "</tr>";
                    ++$i;
                }
                ?>
                </tbody></table>
                <?php
            }
            ?>
                
<!--
<input id="timepicker7" type="text" name="timepicker7">
<br><br><br><br><br><br>
    -->        
        </div>
        
        
        
      
    </div>
    <div class="col-md-3" id="blog-post">
        <br>
        <?php $this->load->view('torpes/clasificacion_peq_jor'); ?>
        <?php $this->load->view('torpes/clasificacion_peq'); ?>
    </div>
</div>



<?php $this->load->view('torpes/pie'); ?>