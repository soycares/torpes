<!-- TEST -->
<?php $this->load->view('torpes/cabecera') ?>

<div class="row">

    <div class="col-md-9" id="blog-post">
        <div class="row">
            <div class="heading" style="text-align: center">
                <h2>Jornada <?=$numJornada?></h2>
            </div>

            <?php
            $jornadaCerrada = jornada_cerrada();

            if (!$jornadaCerrada ) {
                $rs=$this->db->query("SELECT DISTINCT idUsuario,nombreUsuario
                FROM v_tor_consulta_nueva
                WHERE numJornada =".$numJornada." AND idTemporada=".$this->session->userdata('tor_idTemporada'));

                $i=0;
                if ($rs->num_rows() == count($this->session->userdata("tor_lista_usuarios")))
                {
                    ?>
                    <div class="alert alert-info" role="alert">Todos los usuarios que han realizado sus apuestas</div>
                    <?php
                }
                else
				{
					// Tiempo hasta cerrar jornada
					?>
					<!-- INICIO -->

					<div class="row">
						<div class="col-sm-12 col-md-12 col-xs-12 text-center">
							<link rel="stylesheet" href="<?=base_url()?>/css/jquery.countdown.css">
							<script src="<?=base_url()?>/js/jquery-1.11.0.min.js"></script>
							<script src="<?=base_url()?>/js/jquery.plugin.min.js"></script>
							<script src="<?=base_url()?>/js/jquery.countdown.min.js"></script>
							<script src="<?=base_url()?>/js/jquery.countdown-es.js"></script>
							<script>
							<?php
								$fecha_arr = date_parse($fechaCierre);
								
							?>
							$(function () {
								var austDay = new Date();
								austDay = new Date(<?=$fecha_arr["year"]?>, <?=($fecha_arr["month"] - 1)?>, <?=($fecha_arr["day"] - 1)?>,<?=$fecha_arr["hour"]?>,<?=$fecha_arr["minute"]?>,0); 
								/*austDay = new Date(2017, 8, 23,11,3,20);*/
								$('#defaultCountdown').countdown({until: austDay, 
								/*format: 'dhmS',compact: true, description: '',*/
								layout: '<b>{dn}</b> {dl}, <b>{hn}</b> {hl}, <b>{mn}</b> {ml} y <b>{sn}</b> {sl}',
								expiryText: '<div class="over"><center>Jornada Cerrada</center></div>'});
								/*$('#year').text(austDay.getFullYear());*/
							});
							</script>
							<div class="alert alert-info" role="alert" >
								Cierre de la jornada en 
								<div id="defaultCountdown" style="display:inline-block"></div>
							</div>
						</div>
					</div>

					<!-- FIN -->
					<?php
					if ($rs->num_rows() > 0) {

				?>
				<div class="alert alert-info" role="alert">Usuarios que han realizado su apuesta:
				<?php
					
					$usuariosPendientes = $this->session->userdata("tor_lista_usuarios");
					
					foreach ($rs->result() as $usuarios)
					{
						if ($usuarios->idUsuario == $this->session->tor_idUsuario)
							$usuarios->nombreUsuario = "<strong>".$usuarios->nombreUsuario."</strong>";
						if ($i == 0)
							echo $usuarios->nombreUsuario." ";
						else
							echo ", ".$usuarios->nombreUsuario." ";
						$i++;

						unset($usuariosPendientes[$usuarios->idUsuario]);
					}

				?>

				</div>
				<?php
				} else {
	?>
	 <div class="alert alert-warning" role="alert">Ningun usuario ha realizado su apuesta</div>
	<?php
				}
			}

                // Si la jornada no esta cerrada y algun usuario ha hecho apuesta, poner los que no han apostado
               
                if ($i > 0)
                {
                    $i=0;
                    $nombres = "";
                    foreach ($usuariosPendientes as $id => $nom)
                    {
                        
                        if ($i == 0)
                            $nombres = $nom." ";
                        else
                            $nombres.= ", ".$nom." ";
                        $i++;
                    }

                    if ($i > 0)
                    {
                        echo '<div class="alert alert-danger" role="alert">Usuarios pendientes de apostar: '.$nombres;
                        echo "</div>";
                    }

                }


            }


            $rs=$this->db->query("SELECT distinct nombre as nombreUsuario FROM tor_jornadas a, tor_apuestas b, tor_usuarios c
                                    where numJornada =".$numJornada."
                                    and a.idJornada = b.idJornada
                                    and a.idTemporada =".$this->session->userdata("tor_idTemporada")."
                                    and b.cerrada = 1 and
                                    b.idUsuario = c.idUsuario");
            if ($rs->num_rows() > 0 && !$jornadaCerrada){
                ?>
            <div class="alert alert-warning" role="alert">Usuarios que han cerrado su apuesta:
            <?php
            $i=0;
            foreach ($rs->result() as $usuarios)
            {
                if ($i == 0)
                    echo $usuarios->nombreUsuario." ";
                else
                    echo ", ".$usuarios->nombreUsuario." ";
                $i++;
            }

            ?>

            </div>
            <?php
            }


            if ($jornadaCerrada) {
            ?>
            <div class="alert alert-info" role="alert">Todos los usuarios han hecho sus apuestas.</div>
            <?php
            $fechaCierre = strtotime ( '+13 minute' , strtotime ( date('Y-m-d H:i:s')) ) ;
            } ?>

            <?= form_open('torpes/inicio/apuesta', 'method="post"') ?>
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

                        $cerrada=$datos[$fila->idJornada]["cerrada"];
                        if ($jornadaCerrada || $cerrada)
                            echo '<td><strong>' . $datos[$fila->idJornada]["golesLocal"] . '</strong></td>';
                        else
                            echo '<td>' . form_dropdown('golesLocal_' . $fila->idJornada, $goles, $datos[$fila->idJornada]["golesLocal"]) . '</td>';
                    } else {

                        $cerrada = 0;
                        if ($jornadaCerrada  || $cerrada)
                            echo '<td><strong>-</strong></td>';
                        else
                            echo '<td>' . form_dropdown('golesLocal_' . $fila->idJornada, $goles, -1) . '</td>';
                    }
                    echo '<td>' . $fila->visitante . "</td>";
                    if (is_array($datos)) {
                        if ($jornadaCerrada || $cerrada)
                            echo '<td><strong>' . $datos[$fila->idJornada]["golesVisitante"] . '</strong></td>';
                        else
                            echo '<td>' . form_dropdown('golesVisitante_' . $fila->idJornada, $goles, $datos[$fila->idJornada]["golesVisitante"]) . '</td>';
                    } else {
                        if ($jornadaCerrada || $cerrada)
                            echo '<td><strong>-</strong></td>';
                        else
                            echo '<td>' . form_dropdown('golesVisitante_' . $fila->idJornada, $goles, -1) . '</td>';
                    }
                    if (!$jornadaCerrada || $cerrada)
                        echo '<td><center>' . fecha_hora($fila->fecha) . '</center></td>';
                    else
                        echo '<td><center>Apuesta cerrada</center></td>';
                }
                echo "</tr>";

                ?>
                            </tbody>
            </table>
            <?php if (!$jornadaCerrada && !$cerrada) {
                if (is_array($datos))
                    $col=6;
                else
                    $col=12;?>
            <div class="col-sm-<?=$col?> text-center  col-xs-6">
                <button class="btn btn-template-main" type="submit"><i class="fa fa-envelope-o"></i>Enviar</button>

            </div>
            <?php if ($cerrada == 0 && is_array($datos)) { ?>
            <div class="col-sm-6 col-xs-6 text-center">
                <a href="<?=base_url('torpes/inicio/cerrar')?>" class="btn btn-template-main" > <i class="fa fa-lock" aria-hidden="true"></i> Cerrar</a>

            </div>
            <?php
            }

            } ?>
            </form>

        </div>


        <div class="row">
            <?php
            if ($this->session->userdata('tor_rol') == 0) {

            // Ver partidos en juego o finalizados de la jornada
            if ($partidos_jornada->num_rows() > 0 &&   $jornadaCerrada) {
                ?>
                <div class="heading" style="text-align: center">
                    <h2>Partidos Jornada  <?=$numJornada?></h2>
                </div>
            <table class="table">
                <tbody>
                <?php
                

                $partido_anterior="";
                
              
                
                // Calcular leyenda 
                foreach ($partidos_jornada->result() as $fila) {
                    $partido = $fila->LOCAL." - ".$fila->visitante;
                    
                    if ($partido != $partido_anterior)
                    {
                        $leyenda[$partido]["1"] = 0;
                        $leyenda[$partido]["X"] = 0;
                        $leyenda[$partido]["2"] = 0;
                    }
                    $leyenda[$partido][$fila->golesLocalApuesta."-".$fila->golesVisitanteApuesta] = 0;

                    if ($fila->golesLocalApuesta == $fila->golesVisitanteApuesta)
                        $leyenda[$partido]["X"] += 1;
                    if ($fila->golesLocalApuesta > $fila->golesVisitanteApuesta)
                    $leyenda[$partido]["1"] += 1;
                    if ($fila->golesLocalApuesta < $fila->golesVisitanteApuesta)
                    $leyenda[$partido]["2"] += 1;
                    $partido_anterior = $partido;
                }

                $partido_anterior="";
                foreach ($partidos_jornada->result() as $fila) {
                    $partido = $fila->LOCAL." - ".$fila->visitante;

                    
                    if ($partido != $partido_anterior)
                    {
                        
                        echo "<tr>";
                        if (strtotime($fila->fecha) < strtotime($fecha_ahora))
                        {
                            
                            $fecha_final_partido = strtotime(" +120 minute",strtotime($fila->fecha ));
                            if ($fecha_final_partido  < strtotime($fecha_ahora))
                            {
                                if ($fila->signo != '')
                                    echo '<td colspan="4" style="background:#fafafa"><h4>'.$fila->LOCAL." <font color=\"#38A7BB\">".$fila->golesLocalResultado."</font> - ".$fila->visitante." <font color=\"#38A7BB\">".$fila->golesVisitanteResultado."</font></h4></td>";
                                else
                                    echo '<td colspan="4" style="background:#fafafa"><h4>'.$fila->LOCAL." <font color=\"#38A7BB\"></font> - ".$fila->visitante." <font color=\"#38A7BB\"></font></h4></td>";
                                echo '<td colspan="2" style="background:#fafafa" class="text-right">
                            
                                        <button class="btn btn-sm btn-info" type="button">Finalizado</button></td>';   
                            }
                            else
                            {
                                echo '<td colspan="4" style="background:#fafafa"><h4>'.$fila->LOCAL." <font color=\"#38A7BB\"></font> - ".$fila->visitante." <font color=\"#38A7BB\"></font></h4></td>";
                                echo '<td colspan="2" style="background:#fafafa" class="text-right">
                            
                                        <button class="btn btn-sm btn-success" type="button">En juego</button></td>';   
                                
                            }
                        }
                        else
                        {
                            echo '<td colspan="4" style="background:#fafafa"><h4>'.$partido."</h4></td>";
                            echo '<td colspan="2" style="background:#fafafa" class="text-right"><h4>'.fecha_hora($fila->fecha)."</h4></td>";
                        }
                        echo "</tr>";

                        // leyenda
                        echo "<tr style='border-top:none'>";
                        echo '<td colspan="6" style="background:#fafafa;border-top: none;padding-top: 0px;">';
                        echo '<span class="label label-info">Resultados Distintos: '.(count($leyenda[$partido]) - 3).'</span>';
                        echo '&nbsp;&nbsp;<span class="label label-success">1: '.$leyenda[$partido]["1"].'</span>';
                        echo '&nbsp;&nbsp;<span class="label label-success">X: '.$leyenda[$partido]["X"].'</span>';
                        echo '&nbsp;&nbsp;<span class="label label-success">2: '.$leyenda[$partido]["2"].'</span>';
                        echo "</tr>";


                        $back='';
                        $resAnt=$fila->golesLocalApuesta."-".$fila->golesVisitanteApuesta;
                        $partido_anterior = $partido;
                    }

                    if ($resAnt != $fila->golesLocalApuesta."-".$fila->golesVisitanteApuesta)
                        if ($back == "")
                            $back=' style="background:#E8F2F4"';
                        else
                        $back="";

                    $resAnt=$fila->golesLocalApuesta."-".$fila->golesVisitanteApuesta;
                    echo "<tr $back>";

                    echo '<td>'.$fila->nombreUsuario."</td>";
                    if (strtotime($fila->fecha) < strtotime($fecha_ahora) && $fila->golesLocalResultado == $fila->golesLocalApuesta)
                        echo '<td><b><font color="#33A529">'.$fila->golesLocalApuesta."</font></b></td>";
                    else
                        echo '<td>'.$fila->golesLocalApuesta."</td>";

                    if (strtotime($fila->fecha) < strtotime($fecha_ahora) && $fila->golesVisitanteResultado == $fila->golesVisitanteApuesta)
                        echo '<td><b><font color="#33A529">'.$fila->golesVisitanteApuesta."</font></b></td>";
                    else
                        echo '<td>'.$fila->golesVisitanteApuesta."</td>";

                    // globos informativos
                    $info ="&nbsp;";
                    if (strtotime($fila->fecha) < strtotime($fecha_ahora) && $fila->signo != '')
                    {
                        if ($fila->golesLocalResultado == $fila->golesLocalApuesta && $fila->golesVisitanteResultado == $fila->golesVisitanteApuesta)
                            $info.="<span class='label label-success'>".$fila->puntos_res."</span>&nbsp;";


                        if (($fila->golesLocalResultado == $fila->golesVisitanteResultado && $fila->golesLocalApuesta == $fila->golesVisitanteApuesta) ||
                            ($fila->golesLocalResultado > $fila->golesVisitanteResultado && $fila->golesLocalApuesta > $fila->golesVisitanteApuesta) ||
                            ($fila->golesLocalResultado < $fila->golesVisitanteResultado && $fila->golesLocalApuesta < $fila->golesVisitanteApuesta))
                            $info.="<span class='label label-info'>".$fila->puntos_signo."</span>&nbsp;";

                        if ($fila->golesLocalResultado == $fila->golesLocalApuesta || $fila->golesVisitanteResultado == $fila->golesVisitanteApuesta)
                            $info.="<span class='label label-warning'>".$fila->puntos_goles."</span>&nbsp;";

                        if ($fila->puntos_resta > 0)
                            $info.="<span class='label label-danger'>-".$fila->puntos_resta."</span>&nbsp;";
                        
                        if ($fila->puntos_dif_goles > 0)
                            $info.='<span class="label label-default">-'.$fila->puntos_dif_goles.'</span>';
                    }
                    echo '<td class="text-right"> '.$info.'</td>';
                    if ($fila->signo != '')
                        echo '<td class="text-right"><b>'.$fila->puntos_total." P</b></td>";
                    else
                        echo '<td class="text-right"></td>';
                    echo "</tr>";
                }
                ?>
                </tbody></table>
            <br><br><br>
            <div class="col-sm-12 text-center">
                &nbsp;
                <span class='label label-success'>Puntos Resultado</span>&nbsp;&nbsp;
                <span class='label label-info'>Puntos Signo</span>&nbsp;&nbsp;
                <span class='label label-warning'>Puntos Goles</span>&nbsp;&nbsp;
                <span class='label label-danger'>Puntos Resta</span>&nbsp;&nbsp;
                <span class='label label-default'>Puntos Diferencia Goles</span>&nbsp;&nbsp;

            </div>
                <?php
            } else {
                // Ver jornadas cerradas
                $cerrada = 0;

                foreach ($partidos_jornada->result() as $fila) {
                    if ($fila->idUsuario != $this->session->userdata('tor_idUsuario'))
                        continue;
                    if ($fila->cerrada == 1)
                        $cerrada = 1;
                }
            if ($cerrada == 1) {
                ?>
                <div class="heading" style="text-align: center">
                    <h2>Partidos Jornada <?=$numJornada?></h2>
                </div>
            <table class="table">
                <tbody>
                <?php

                $partido_anterior="";
                // Calcular leyenda 
                foreach ($partidos_jornada->result() as $fila) {
                    $partido = $fila->LOCAL." - ".$fila->visitante;
                    
                    if ($partido != $partido_anterior)
                    {
                        $leyenda[$partido]["1"] = 0;
                        $leyenda[$partido]["X"] = 0;
                        $leyenda[$partido]["2"] = 0;
                    }
                    $leyenda[$partido][$fila->golesLocalApuesta."-".$fila->golesVisitanteApuesta] = 0;

                    if ($fila->golesLocalApuesta == $fila->golesVisitanteApuesta)
                        $leyenda[$partido]["X"] += 1;
                    if ($fila->golesLocalApuesta > $fila->golesVisitanteApuesta)
                    $leyenda[$partido]["1"] += 1;
                    if ($fila->golesLocalApuesta < $fila->golesVisitanteApuesta)
                    $leyenda[$partido]["2"] += 1;
                    $partido_anterior = $partido;
                }

                
                $partido_anterior="";
                 $resAnt="";
                foreach ($partidos_jornada->result() as $fila) {
//                    if ($fila->cerrada == 0)
//                        continue;
                    $partido=$fila->LOCAL." - ".$fila->visitante;
                    if ($partido != $partido_anterior)
                    {
                        $partido_anterior = $partido;
                        echo "<tr>";
                        if (strtotime($fila->fecha) < strtotime($fecha_ahora))
                            echo '<td colspan="4" style="background:#fafafa"><h4>'.$fila->LOCAL." <font color=\"#38A7BB\">".$fila->golesLocalResultado."</font> - ".$fila->visitante." <font color=\"#38A7BB\">".$fila->golesVisitanteResultado."</font></h4></td>";
                        else
                            echo '<td colspan="4" style="background:#fafafa"><h4>'.$partido."</h4></td>";
                        echo '<td colspan="2" style="background:#fafafa" class="text-right"><h4>'.fecha_hora($fila->fecha)."</h4></td>";
                        echo "</tr>";
                       $back='';
                        $resAnt=$fila->golesLocalApuesta."-".$fila->golesVisitanteApuesta;

                        // leyenda
                        echo "<tr style='border-top:none'>";
                        echo '<td colspan="6" style="background:#fafafa;border-top: none;padding-top: 0px;">';
                        echo '<span class="label label-info">Resultados Distintos: '.(count($leyenda[$partido]) - 3).'</span>';
                        echo '&nbsp;&nbsp;<span class="label label-success">1: '.$leyenda[$partido]["1"].'</span>';
                        echo '&nbsp;&nbsp;<span class="label label-success">X: '.$leyenda[$partido]["X"].'</span>';
                        echo '&nbsp;&nbsp;<span class="label label-success">2: '.$leyenda[$partido]["2"].'</span>';
                        echo "</tr>";
                    }



                   //echo $resAnt." != ".$fila->golesLocalApuesta."-".$fila->golesVisitanteApuesta;
                    if ($resAnt != $fila->golesLocalApuesta."-".$fila->golesVisitanteApuesta)
                            if ($back == "")
                            $back=' style="background:#E8F2F4"';
                    else
                        $back="";
                    $resAnt=$fila->golesLocalApuesta."-".$fila->golesVisitanteApuesta;
                    echo "<tr $back>";


                    if ($fila->cerrada == 1)
                        echo '<td>'.$fila->nombreUsuario.' <i class="fa fa-lock" aria-hidden="true"></i> </td>';
                    else
                        echo '<td>'.$fila->nombreUsuario."</td>";



                    if (strtotime($fila->fecha) < strtotime($fecha_ahora) && $fila->golesLocalResultado == $fila->golesLocalApuesta)
                        echo '<td><b><font color="#33A529">'.$fila->golesLocalApuesta."</font></b></td>";
                    else
                        echo '<td>'.$fila->golesLocalApuesta."</td>";

                    if (strtotime($fila->fecha) < strtotime($fecha_ahora) && $fila->golesVisitanteResultado == $fila->golesVisitanteApuesta)
                        echo '<td><b><font color="#33A529">'.$fila->golesVisitanteApuesta."</font></b></td>";
                    else
                        echo '<td>'.$fila->golesVisitanteApuesta."</td>";

                    // globos informativos
                    $info ="&nbsp;";
                    if (strtotime($fila->fecha) < strtotime($fecha_ahora))
                    {
                        if ($fila->golesLocalResultado == $fila->golesLocalApuesta && $fila->golesVisitanteResultado == $fila->golesVisitanteApuesta)
                            $info.="<span class='label label-success'>".$fila->puntos_res."</span>&nbsp;";


                        if (($fila->golesLocalResultado == $fila->golesVisitanteResultado && $fila->golesLocalApuesta == $fila->golesVisitanteApuesta) ||
                            ($fila->golesLocalResultado > $fila->golesVisitanteResultado && $fila->golesLocalApuesta > $fila->golesVisitanteApuesta) ||
                            ($fila->golesLocalResultado < $fila->golesVisitanteResultado && $fila->golesLocalApuesta < $fila->golesVisitanteApuesta))
                            $info.="<span class='label label-info'>".$fila->puntos_signo."</span>&nbsp;";

                        if ($fila->golesLocalResultado == $fila->golesLocalApuesta || $fila->golesVisitanteResultado == $fila->golesVisitanteApuesta)
                            $info.="<span class='label label-warning'>".$fila->puntos_goles."</span>&nbsp;";

                        if ($fila->puntos_resta > 0)
                            $info.="<span class='label label-danger'>".$fila->puntos_resta."</span>&nbsp;";
                    }
echo '<td class="text-right"> '.$info.'</td>';
    echo '<td class="text-right"><b>'.$fila->puntos_total." P</b></td>";
                    echo "</tr>";
                }
                ?>
                </tbody></table>
            <br>



            
            <div class="col-sm-12 text-center">
                &nbsp;
                <span class='label label-success'>Puntos Resultado</span>&nbsp;&nbsp;
                <span class='label label-info'>Puntos Signo</span>&nbsp;&nbsp;
                <span class='label label-warning'>Puntos Goles</span>&nbsp;&nbsp;
                <span class='label label-danger'>Puntos Resta</span>&nbsp;&nbsp;
                 <span class='label label-default'>Puntos Diferencia Goles</span>

            </div>
            <div class="col-sm-12 text-center">&nbsp;</div>
            
            <?php
            }
            }





        }   //rol = 0
            // Varsion 2 de la tabla
            ?>
            <style>
                .filaTitulo{
                    border-bottom: 1px solid #ccc;
                    border-top: 1px solid #ccc;
                    margin-left:1px;
                    margin-right:1px;
                    margin-top: 10px;
                    padding-bottom:2px;
                    padding-top:5px;
                    background:#fafafa;
                }
                .filaApuesta1{
                    border-bottom: 1px solid #ccc;
                    margin-left:1px;
                    margin-right:1px;
                    
                    padding-bottom:2px;
                    padding-top:5px;
                    /*background:#fafafa;*/
                }
                .filaApuesta1 .resaltado{
                    font-weight: 900;
                    font-size: 18px;
                }
                .filaApuesta2{
                    background:#E8F2F4;
                    border-bottom: 1px solid #ccc;
                    margin-left:1px;
                    margin-right:1px;
                    
                    padding-bottom:2px;
                    padding-top:5px;
                }
                .filaApuesta2 .resaltado{
                    font-weight: 900;
                    font-size: 18px;
                }
            </style>

<script src="<?= base_url('') ?>/js/jquery-1.11.0.min.js"></script>
<script src="<?= base_url('') ?>/js/html2canvas.min.js"></script>
<script>
$(document).ready(function () {
    
        var element1 = $("#partido1"); // global variable
        var element2 = $("#partido2"); // global variable
        var element3 = $("#partido3"); // global variable
        var element4 = $("#partido4"); // global variable
        var getCanvas1; // global variable
        var getCanvas2; // global variable
        var getCanvas3; // global variable
        var getCanvas4; // global variable
        $("#btn-Convert-partido1").hide();
        $("#btn-Convert-partido2").hide();
        $("#btn-Convert-partido3").hide();
        $("#btn-Convert-partido4").hide();
        
        $("#btn-Preview-partido1").on("click", function () {
            html2canvas(element1, {
                onrendered: function (canvas) {
                    //$("#previewImage").append(canvas);
                    getCanvas1 = canvas;
                    $("#btn-Convert-partido1").show();
                    $("#btn-Preview-partido1").hide();
                    $("#btn-Convert-partido1").focus();
                    
                }
            });
        });
        $("#btn-Convert-partido1").on("click", function () {
            var imgageData = getCanvas1.toDataURL("image/png");
            // Now browser starts downloading it instead of just showing it
            var newData = imgageData.replace(/^data:image\/png/, "data:application/octet-stream");
            $("#btn-Convert-partido1").attr("download", "Apuestas1_Jornada<?=$numJornada?>.png").attr("href", newData);
        });
    
        $("#btn-Preview-partido2").on("click", function () {
            html2canvas(element2, {
                onrendered: function (canvas) {
                    //$("#previewImage").append(canvas);
                    getCanvas2 = canvas;
                    $("#btn-Convert-partido2").show();
                    $("#btn-Preview-partido2").hide();
                    $("#btn-Convert-partido2").focus();
                    
                }
            });
        });
        $("#btn-Convert-partido2").on("click", function () {
            var imgageData = getCanvas2.toDataURL("image/png");
            // Now browser starts downloading it instead of just showing it
            var newData = imgageData.replace(/^data:image\/png/, "data:application/octet-stream");
            $("#btn-Convert-partido2").attr("download", "Apuestas2_Jornada<?=$numJornada?>.png").attr("href", newData);
        });
        $("#btn-Preview-partido3").on("click", function () {
            html2canvas(element3, {
                onrendered: function (canvas) {
                    //$("#previewImage").append(canvas);
                    getCanvas3 = canvas;
                    $("#btn-Convert-partido3").show();
                    $("#btn-Preview-partido3").hide();
                    $("#btn-Convert-partido3").focus();
                    
                }
            });
        });
        $("#btn-Convert-partido3").on("click", function () {
            var imgageData = getCanvas3.toDataURL("image/png");
            // Now browser starts downloading it instead of just showing it
            var newData = imgageData.replace(/^data:image\/png/, "data:application/octet-stream");
            $("#btn-Convert-partido3").attr("download", "Apuestas3_Jornada<?=$numJornada?>.png").attr("href", newData);
        });
        $("#btn-Preview-partido4").on("click", function () {
            html2canvas(element4, {
                onrendered: function (canvas) {
                    //$("#previewImage").append(canvas);
                    getCanvas4 = canvas;
                    $("#btn-Convert-partido4").show();
                    $("#btn-Preview-partido4").hide();
                    $("#btn-Convert-partido4").focus();
                    
                }
            });
        });
        $("#btn-Convert-partido4").on("click", function () {
            var imgageData = getCanvas4.toDataURL("image/png");
            // Now browser starts downloading it instead of just showing it
            var newData = imgageData.replace(/^data:image\/png/, "data:application/octet-stream");
            $("#btn-Convert-partido4").attr("download", "Apuestas4_Jornada<?=$numJornada?>.png").attr("href", newData);
        });



    }); 
    </script>

            
            <?php 
            if ($this->session->userdata("tor_rol") == 1) { 
                echo $apuestas;
            ?>
            <div class="col-sm-12 text-center">
                &nbsp;
                <span class='label label-success'>Puntos Resultado</span>&nbsp;&nbsp;
                <span class='label label-info'>Puntos Signo</span>&nbsp;&nbsp;
                <span class='label label-warning'>Puntos Goles</span>&nbsp;&nbsp;
                <span class='label label-danger'>Puntos Resta</span>&nbsp;&nbsp;
                 <span class='label label-default'>Puntos Diferencia Goles</span>

            </div>

            <div class="row" >&nbsp;</div>
            <div class="row" >&nbsp;</div>
       
            <?php
            }
            ?>


        </div>
     </div>
    <div class="col-md-3" id="blog-post">
        <br>


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



<?php $this->load->view('torpes/pie'); ?>
