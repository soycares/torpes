<?php $this->load->view('torpes/cabecera') ?>

<div class="row">

    <div class="col-md-9" id="blog-post">
        <div class="row">
            <div class="heading" style="text-align: center">
                <h2>Jornada <?=$numJornada?></h2>
            </div>
            <?= form_open('torpes/admin/jornada/resultado', 'method="post"') ?>
            <input type="hidden" id="numJornada" name="numJornada" value="<?=$numJornada?>">
            <table class="table">
                <tbody>
                    <tr>
                        <td style="background:#fafafa" colspan=4><center>Partido</center></td>
                <td style="background:#fafafa" colspan=1><center>Fecha</center></td>
                </tr>
                <?php
                $i = 0;

                $goles = array(-1 => '-', 0 => 0, 1 => 1, 2 => 2, 3 => 3,
                    4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10
                    , 11 => 11                    , 12 => 12, 13 => 13, 14 => 14);

                $fecha_ahora = date('Y-m-d H:i:s');

                foreach ($partidos->result() as $fila) {
                    ++$i;
                    $idUsuario = $this->session->userdata("tor_idUsuario");
                    $datos = datos_apuesta($fila->idJornada, $this->session->userdata("tor_idUsuario"));
                    echo '<tr>';
                    echo '<td>' . $fila->local . "</td>";


    
                        if (strtotime($fila->fecha) > strtotime($fecha_ahora))
                            echo '<td><strong>' . $fila->golesLocal . '</strong></td>';
                        else
                        {
                            if ($fila->signo == "")
                                echo '<td>' . form_dropdown('golesLocal_' . $fila->idJornada, $goles, -1) . '</td>';
                            else
                                echo '<td>' . form_dropdown('golesLocal_' . $fila->idJornada, $goles, $fila->golesLocal) . '</td>';
                        }
                    echo '<td>' . $fila->visitante . "</td>";

                        if (strtotime($fila->fecha) > strtotime($fecha_ahora))
                            echo '<td><strong>' . $fila->golesVisitante . '</strong></td>';
                        else
                        {
                            if ($fila->signo == "")
                                echo '<td>' . form_dropdown('golesVisitante_' . $fila->idJornada, $goles, -1) . '</td>';
                            else
                                echo '<td>' . form_dropdown('golesVisitante_' . $fila->idJornada, $goles, $fila->golesVisitante) . '</td>';
                        }

                        if (strtotime($fila->fecha) > strtotime($fecha_ahora))
                        echo '<td>' . $fila->fecha . '</td>';
                    else
                        echo '<td>Finalizado</td>';
                }
                echo "</tr>";
                ?>
                            </tbody>
            </table>
            <div class="col-sm-3 col-xs-6 text-center">
                <button class="btn btn-template-main" type="submit"><i class="fa fa-envelope-o"></i>Enviar</button>

            </div>                            
            <div class="col-sm-3 col-xs-6 text-center">
                <!-- <button class="btn btn-template-main" type="submit"><i class="fa fa-envelope-o"></i>Calcular</button> -->
                <a href="<?=site_url()."/torpes/admin/jornada/puntos/".$idTemporada."/".$numJornada?>" class="btn btn-template-main">Calcular</a>

            </div>                            
            <div class="col-sm-3  col-xs-6 text-center">
                <!-- <button class="btn btn-template-main" type="submit"><i class="fa fa-envelope-o"></i>Calcular</button> -->
                <a href="<?=site_url()."/torpes/admin/jornada/nueva/".$idTemporada?>" class="btn btn-template-main">Nueva</a>

            </div>                            
            <div class="col-sm-3  col-xs-6 text-center">
                <!-- <button class="btn btn-template-main" type="submit"><i class="fa fa-envelope-o"></i>Calcular</button> -->
                <a href="<?=site_url()."/torpes/admin/jornada/modificar/".$idTemporada."/".$numJornada?>" class="btn btn-template-main">Modificar</a>
            </div>                         
            </form>

        </div>
        
    </div>
    <div class="col-md-3" id="blog-post">
        <div class="panel panel-default sidebar-menu">

            <?php $this->load->view('torpes/clasificacion_peq_jor'); ?>
            <?php $this->load->view('torpes/clasificacion_peq'); ?>
            <?php $this->load->view('torpes/clasificacion_peq_tramos'); ?>

            <?php //$this->load->view('torpes/clasificacion_peq_potras'); ?>
            
            <?php $this->load->view('torpes/maxima_puntuacion_peq'); ?>
            <?php $this->load->view('torpes/ganancias_peq'); ?>
            
            <?php $this->load->view('torpes/aciertos_equipos_peq'); ?>        
            <?php $this->load->view('torpes/resumen_peq'); ?>


        

        </div>
    </div>
</div>



<?php $this->load->view('torpes/pie'); ?>