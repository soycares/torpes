<?php $this->load->view('torpes/cabecera') ?>

<?php 

?>
<div class="row">

    <div class="col-md-9" id="blog-post">
        <div class="row">
            <div class="heading" style="text-align: center">
                <h2>Jornada <?=$numJornada?></h2>
            </div>
            
           
            
            <?= form_open('torpes/admin/apuestas/nuevo', 'method="post"') ?>
            <table class="table">
                <tbody>
                    <tr>
                        <td style="background:#fafafa" colspan=4><center>Partido</center></td>
                <td style="background:#fafafa" colspan=1><center>Fecha</center></td>
                </tr>
                <?php
                $i = 0;

                $goles = array(-1 => '-', 0 => 0, 1 => 1, 2 => 2, 3 => 3,
                    4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10);

                $fecha_ahora = date('Y-m-d H:i:s');

                
                $j=0;
                foreach ($partidos->result() as $fila) {
                    ++$i;

                    $idUsuario = $this->session->userdata("tor_idUsuario");
                    $datos = datos_apuesta($fila->idJornada, $this->session->userdata("tor_idUsuario"));
                    echo '<tr>';
                    echo '<td>' . $fila->local . "</td>";



                    if (is_array($datos)) {
                        
                        
                            echo '<td>' . form_dropdown('golesLocal_' . $fila->idJornada, $goles, $datos[$fila->idJornada]["golesLocal"]) . '</td>';
                    } else {
                        
                       
                            echo '<td>' . form_dropdown('golesLocal_' . $fila->idJornada, $goles, -1) . '</td>';
                    }
                    echo '<td>' . $fila->visitante . "</td>";
                    if (is_array($datos)) {
                       
                            echo '<td>' . form_dropdown('golesVisitante_' . $fila->idJornada, $goles, $datos[$fila->idJornada]["golesVisitante"]) . '</td>';
                    } else {
                      
                            echo '<td>' . form_dropdown('golesVisitante_' . $fila->idJornada, $goles, -1) . '</td>';
                    }
                    
                        echo '<td>' . fecha_hora($fila->fecha) . '</td>';
                   
                }
                echo "</tr>";
                
                ?>
                            </tbody>
            </table>
     
               
            <div class="col-sm-12 text-center">
                <button class="btn btn-template-main" type="submit"><i class="fa fa-envelope-o"></i>Enviar</button>

            </div>     
           
            </form>

        </div>
        

     
   
    </div>
    <div class="col-md-3" id="blog-post">
        <br>
        <?php $this->load->view('torpes/clasificacion_peq_jor'); ?>
        <?php $this->load->view('torpes/clasificacion_peq'); ?>
        <?php $this->load->view('torpes/clasificacion_peq_potras'); ?>
        <?php $this->load->view('torpes/ganancias_peq'); ?>
    </div>
</div>



<?php $this->load->view('torpes/pie'); ?>