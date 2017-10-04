
<?php $this->load->view('torpes/cabecera') ?>


<!-- test datepicker -->

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

        <script src="<?=base_url()?>/js/bootstrap-datetimepicker.min.js"></script>
        <link href="<?= base_url('') ?>/css/bootstrap-datetimepicker.min.css" rel="stylesheet">



    <script type="text/javascript">
        $("document").ready(function(){

          $('#imp_general_1').on('change', function (){
            if ($.isNumeric($('#imp_general_1').val())) {
              $('#imp_general_1').css('border-color','#ccc');
              validar("General 1");
            }
            else {
                $('#imp_general_1').css('border-color','red');
            }
          }
          );

          $('#imp_general_2').on('change', function (){
            if ($.isNumeric($('#imp_general_2').val())) {
              $('#imp_general_2').css('border-color','#ccc');
              validar("General 2");
            }
            else {
                $('#imp_general_2').css('border-color','red');
            }
          }
          );

          $('#imp_general_3').on('change', function (){
            if ($.isNumeric($('#imp_general_1').val())) {
              $('#imp_general_3').css('border-color','#ccc');
              validar("General 3");
            }
            else {
                $('#imp_general_3').css('border-color','red');
            }
          }
          );

          $('#imp_tramo_1').on('change', function (){
            if ($.isNumeric($('#imp_tramo_1').val())) {
              $('#imp_tramo_1').css('border-color','#ccc');
              validar("Tramo 1");
            }
            else {
                $('#imp_tramo_1').css('border-color','red');
            }
          }
          );

          $('#imp_tramo_2').on('change', function (){
            if ($.isNumeric($('#imp_tramo_2').val())) {
              $('#imp_tramo_2').css('border-color','#ccc');
              validar("Tramo 2");
            }
            else {
                $('#imp_tramo_2').css('border-color','red');
            }
          }
          );
          $('#imp_tramo_3').on('change', function (){
            if ($.isNumeric($('#imp_tramo_3').val())) {
              $('#imp_tramo_3').css('border-color','#ccc');
              validar("Tramo 3");
            }
            else {
                $('#imp_tramo_3').css('border-color','red');
            }
          }
          );

          $('#imp_jornada_1').on('change', function (){
            if ($.isNumeric($('#imp_jornada_1').val())) {
              $('#imp_jornada_1').css('border-color','#ccc');
              validar("Jornada 1");
            }
            else {
                $('#imp_jornada_1').css('border-color','red');
            }
          }
          );

          $('#imp_jornada_2').on('change', function (){
            if ($.isNumeric($('#imp_jornada_2').val())) {
              $('#imp_jornada_2').css('border-color','#ccc');
              validar("Jornada 2");
            }
            else {
                $('#imp_jornada_2').css('border-color','red');
            }
          }
          );

          $('#imp_jornada_3').on('change', function (){
            if ($.isNumeric($('#imp_jornada_3').val())) {
              $('#imp_jornada_3').css('border-color','#ccc');
              validar("Jornada 3");
            }
            else {
                $('#imp_jornada_3').css('border-color','red');
            }
          }
          );
        });

        function validar(cad)
        {
          console.log("[validar] --------------")
          if ($.isNumeric($('#imp_general_1').val()) && $.isNumeric($('#imp_general_2').val())  && $.isNumeric($('#imp_general_3').val())
             && $.isNumeric($('#imp_tramo_1').val()) && $.isNumeric($('#imp_tramo_2').val()) && $.isNumeric($('#imp_tramo_3').val())
              && $.isNumeric($('#imp_general_1').val()) && $.isNumeric($('#imp_general_2').val()) && $.isNumeric($('#imp_general_3').val()))
              {

          var total = parseInt($('#imp_general_1').val()) + parseInt($('#imp_general_2').val()) +parseInt($('#imp_general_3').val()) +
            parseInt($('#imp_tramo_1').val()) + parseInt($('#imp_tramo_2').val()) + parseInt($('#imp_tramo_3').val()) +
            parseInt($('#imp_jornada_1').val()) + parseInt($('#imp_jornada_2').val()) + parseInt($('#imp_jornada_3').val());


            console.log("[validar] Total premios por jornada: "+total);
            console.log("[validar] € Jornada: "+$('#imp_jornada').val());
            var a = parseInt(total) / parseFloat($('#imp_jornada').val()) ;

            console.log("[validar] Usuarios Necesarios: "+ a);

            console.log("[validar] --------------")
}
else {
  console.log("Datos no numericos");
}
        }
    </script>


<div class="row">

    <div class="col-md-9" id="blog-post">
        <div class="row">
            <div class="heading" style="text-align: center">
                <h2>Editar Temporada</h2>
            </div>

            <?php
            if ($error != '')
                    echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';

            echo form_open('torpes/admin/temporadas/grabar', 'method="post"');
            echo form_hidden('idTemporada', $idTemporada);
            ?>



            <div class="col-xs-6 col-md-6 col-sm-6">
                <label for="billing_firstname">Nombre</label>
                <input id="descripcion" class="form-control required" type="text" name="descripcion" value="<?=$descripcion?>">
            </div>
            <div class="col-xs-6 col-md-6 col-sm-6">
                <label for="billing_firstname">Temporada</label>
                <input id="numTemporada" class="form-control required" type="text" name="numTemporada" value="<?=$numTemporada?>">
            </div>
            <div class="col-xs-6 col-md-6 col-sm-6">
                <?php
                $sel1 = "";
                $sel0="";
                    if ($activa == 1)
                        $sel1 = " selected";
                    else
                        $sel0=" selected";

                ?>
                <label for="billing_firstname">Temporada Activa</label>
                <select id="activa" class="form-control required" name="activa">
                    <option value="1" <?=$sel1?>>Si</option>
                    <option value="0" <?=$sel0?>>No</option>
                </select>
            </div>
            <div class="col-xs-6 col-md-6 col-sm-6">
                <label for="billing_firstname">Nº Jornadas</label>
                <input id="numJornadas" class="form-control required" type="text" name="numJornadas" value="<?=$numJornadas?>">
            </div>


            <div class="col-xs-6 col-md-6 col-sm-6">
                <label for="billing_firstname">Nº Partidos</label>
                <input id="numPartidos" class="form-control required" type="number" name="numPartidos" value="<?=$numPartidos?>">
            </div>
            <div class="col-xs-6 col-md-6 col-sm-6">
                <label for="billing_firstname">€ por Jornada</label>
                <input id="imp_jornada" class="form-control required" type="number" name="imp_jornada" value="<?=$imp_jornada?>">
            </div>

            <div class="col-md-12 col-sm-12">&nbsp;</div>
            <div class="col-md-12 col-sm-12">
              <div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                        <h3 class="panel-title">Puntos</h3>
                    </div>
                  </div>
            </div>

            <div class="col-xs-6 col-md-6 col-sm-6">
                <label for="billing_firstname">Puntos Resultado</label>
                <input id="num_puntos_res" class="form-control required" type="number" name="num_puntos_res" value="<?=$num_puntos_res?>">
            </div>
            <div class="col-xs-6 col-md-6 col-sm-6">
                <label for="billing_firstname">Puntos Signo</label>
                <input id="num_puntos_signo" class="form-control required" type="number" name="num_puntos_signo" value="<?=$num_puntos_signo?>">
            </div>
            <div class="col-xs-6 col-md-6 col-sm-6">
                <label for="billing_firstname">Puntos Goles</label>
                <input id="num_puntos_goles" class="form-control required" type="number" name="num_puntos_goles" value="<?=$num_puntos_goles?>">
            </div>
            <div class="col-xs-6 col-md-6 col-sm-6">
                <label for="billing_firstname">Puntos Resta</label>
                <input id="num_puntos_resta" class="form-control required" type="number" name="num_puntos_resta" value="<?=$num_puntos_resta?>">
            </div>
            <div class="col-md-12 col-sm-12">&nbsp;</div>
            <div class="col-md-12 col-sm-12">
                <label for="billing_firstname">Diferencia de Puntos</label>
                <select id="diferencia_puntos" class="form-control required" name="diferencia_puntos">
                <?php 
                    $sel0="";
                    $sel1="";
                    if ($diferencia_puntos == 1) 
                        $se1=" selected";
                    else
                        $sel0 = " selected";
                    ?>
                    <option value="1" <?=$sel1?>>Si </option>
                    <option value="0" <?=$sel0?>>No</option>
                </select>
                
                
                
            </div>
            <div class="col-md-12 col-sm-12">&nbsp;</div>
            <div class="col-md-12 col-sm-12">
              <div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                        <h3 class="panel-title">Tramos</h3>
                    </div>
                  </div>
            </div>

            <div class="col-md-4 col-sm-4">
                <label for="billing_firstname">Nº Tramos</label>
                <input id="num_tramos" class="form-control required" type="number" name="num_tramos" value="<?=$num_tramos?>">
            </div>
            <div class="col-md-4 col-sm-4">
                <label for="billing_firstname">Jornadas por Tramo</label>
                <input id="num_tramos_jornadas" class="form-control required" type="number" name="num_tramos_jornadas" value="<?=$num_tramos_jornadas?>">
            </div>
            <div class="col-md-4 col-sm-4">
				 
                <label for="billing_firstname">Puntos Dobles Ultima Jornada</label>
                <select id="puntos_dobles_tramo" class="form-control required" name="puntos_dobles_tramo">
                    <?php 
                    $sel0="";
                    $sel1="";
                    if ($puntos_dobles_tramo == 1) 
                        $se1=" selected";
                    else
                        $sel0 = " selected";
                    ?>
                    <option value="1" <?=$sel1?> >Si</option>
                    <option value="0" <?=$sel0?>>No</option>
                </select>
                
                
            </div>
            <div class="col-md-12 col-sm-12">&nbsp;</div>
			<div class="col-md-12 col-sm-12">
              <div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                        <h3 class="panel-title">Máxima Puntuación</h3>
                    </div>
                  </div>
            </div>

            <div class="col-md-6 col-sm-6">
				 <?php
                $sel1 = "";
                $sel0="";
                    if ($maxima_puntuacion == 1)
                        $sel1 = " selected";
                    else
                        $sel0=" selected";

                ?>
                <label for="billing_firstname">Máxima Puntuación</label>
                <select id="maxima_puntuacion" class="form-control required" name="maxima_puntuacion">
                    <option value="1" <?=$sel1?>>Si</option>
                    <option value="0" <?=$sel0?>>No</option>
                </select>
                
                
            </div>
            <div class="col-md-6 col-sm-6">
                <label for="billing_firstname">€ Máxima Puntuación</label>
                <input id="num_tramos_jornadas" class="form-control required" type="number" name="imp_maxima_puntuacion" value="<?=$imp_maxima_puntuacion?>">
            </div>

            
            <div class="col-md-12 col-sm-12">&nbsp;</div>
            <div class="col-md-12 col-sm-12">
              <div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                        <h3 class="panel-title">Aciertos Equipos</h3>
                    </div>
                  </div>
            </div>

            <div class="col-md-6 col-sm-6 ">
                <label for="billing_firstname">Aciertos Equipos</label>
                <select id="aciertos_equipos" class="form-control required" name="aciertos_equipos">
                    <?php
                    
                    $sel1 = "";
                    $sel0="";
                    if ($aciertos_equipos == 1)
                        $sel1 = " selected";
                    else
                        $sel0=" selected";
                    ?>
                    <option value="1" <?=$sel1?>>Si</option>
                    <option value="0" <?=$sel0?>>No</option>
                </select>
            </div>
            <div class="col-md-2 col-sm-2 ">
                <label for="billing_firstname">€ Primero</label>
                <input id="imp_aciertos_equipos_1" class="form-control required" type="number" name="imp_aciertos_equipos_1" value="<?=$imp_aciertos_equipos_1?>">
            </div>
            <div class="col-md-2 col-sm-2 ">
                <label for="billing_firstname">€ Segundo</label>
                <input id="imp_aciertos_equipos_2" class="form-control required" type="number" name="imp_aciertos_equipos_2" value="<?=$imp_aciertos_equipos_2?>">
            </div>
            <div class="col-md-2 col-sm-2 ">
                <label for="billing_firstname">€ Tercero</label>
                <input id="imp_aciertos_equipos_3" class="form-control required" type="number" name="imp_aciertos_equipos_3" value="<?=$imp_aciertos_equipos_3?>">
            </div>


            <div class="col-md-12 col-sm-12">&nbsp;</div>
            <div class="col-md-12 col-sm-12">
              <div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                        <h3 class="panel-title">Reparto General</h3>
                    </div>
                  </div>
            </div>

            <div class="col-md-3 col-sm-3">
                <label for="billing_firstname">€ Primero</label>
                <input id="imp_general_1" class="form-control required" type="number" name="imp_general_1" value="<?=$imp_general_1?>">
            </div>
            <div class="col-md-3 col-sm-3">
                <label for="billing_firstname">€ Segundo</label>
                <input id="imp_general_2" class="form-control required" type="number" name="imp_general_2" value="<?=$imp_general_2?>">
            </div>
            <div class="col-md-3 col-sm-3">
                <label for="billing_firstname">€ Tercero</label>
                <input id="imp_general_3" class="form-control required" type="number" name="imp_general_3" value="<?=$imp_general_3?>">
            </div>
            <div class="col-md-3 col-sm-3">
                <label for="billing_firstname">€ Cuarto</label>
                <input id="imp_general_4" class="form-control required" type="number" name="imp_general_4"  value="<?=$imp_general_4?>">
            </div>

            <div class="col-md-12 col-sm-12">&nbsp;</div>
            <div class="col-md-12 col-sm-12">
              <div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                        <h3 class="panel-title">Reparto Tramos</h3>
                    </div>
                  </div>
            </div>

            <div class="col-md-3 col-sm-3">
                <label for="billing_firstname">€ Primero</label>
                <input id="imp_tramo_1" class="form-control required" type="number" name="imp_tramo_1" value="<?=$imp_tramo_1?>">
            </div>
            <div class="col-md-3 col-sm-3">
                <label for="billing_firstname">€ Segundo</label>
                <input id="imp_tramo_2" class="form-control required" type="number" name="imp_tramo_2" value="<?=$imp_tramo_2?>">
            </div>
            <div class="col-md-3 col-sm-3">
                <label for="billing_firstname">€ Tercero</label>
                <input id="imp_tramo_3" class="form-control required" type="number" name="imp_tramo_3" value="<?=$imp_tramo_3?>">
            </div>
            <div class="col-md-3 col-sm-3">
                <label for="billing_firstname">€ Cuarto</label>
                <input id="imp_tramo_4" class="form-control required" type="number" name="imp_tramo_4" value="<?=$imp_tramo_4?>">
            </div>

            <div class="col-md-12 col-sm-12">&nbsp;</div>
            <div class="col-md-12 col-sm-12">
              <div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                        <h3 class="panel-title">Reparto Jornada</h3>
                    </div>
                  </div>
            </div>

            <div class="col-md-3 col-sm-3">
                <label for="billing_firstname">€ Primero</label>
                <input id="imp_jornada_1" class="form-control required" type="text" name="imp_jornada_1" value="<?=$imp_jornada_1?>">
            </div>
            <div class="col-md-3 col-sm-3">
                <label for="billing_firstname">€ Segundo</label>
                <input id="imp_jornada_2" class="form-control required" type="text" name="imp_jornada_2" value="<?=$imp_jornada_2?>">
            </div>
            <div class="col-md-3 col-sm-3">
                <label for="billing_firstname">€ Tercero</label>
                <input id="imp_jornada_3" class="form-control required" type="text" name="imp_jornada_3" value="<?=$imp_jornada_3?>">
            </div>
            <div class="col-md-3 col-sm-3">
                <label for="billing_firstname">€ Cuarto</label>
                <input id="imp_jornada_4" class="form-control required" type="text" name="imp_jornada_4" value="<?=$imp_jornada_4?>">
            </div>
<div class="col-md-12 col-sm-12">&nbsp;</div>
<div class="col-md-12 col-sm-12">
    <div id="msg_resumen">
        Usuarios necesarios:
    </div>
</div>

            <div class="col-sm-12 text-center" style="margin-top: 15px">
                <button class="btn btn-template-main" type="submit"><i class="fa fa-envelope-o"></i>Enviar</button>

            </div>
            </form>
<?php
die();





$fecha_ahora = date('Y-m-d H:i:s');

foreach ($partidos->result() as $fila) {
    ++$i;
    $datos = datos_apuesta($fila->idJornada, $this->session->userdata("tor_idUsuario"));
    echo '<tr>';
    echo '<td>' . $fila->local . "</td>";



    if (strtotime($fila->fecha) > strtotime($fecha_ahora))
        echo '<td><strong>' . $fila->golesLocal . '</strong></td>';
    else {
        if ($fila->signo == "")
            echo '<td>' . form_dropdown('golesLocal_' . $fila->idJornada, $goles, -1) . '</td>';
        else
            echo '<td>' . form_dropdown('golesLocal_' . $fila->idJornada, $goles, $fila->golesLocal) . '</td>';
    }
    echo '<td>' . $fila->visitante . "</td>";

    if (strtotime($fila->fecha) > strtotime($fecha_ahora))
        echo '<td><strong>' . $fila->golesVisitante . '</strong></td>';
    else {
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

            <div class="col-sm-4 text-center">
                <button class="btn btn-template-main" type="submit"><i class="fa fa-envelope-o"></i>Enviar</button>

            </div>
            <div class="col-sm-4 text-center">
                <!-- <button class="btn btn-template-main" type="submit"><i class="fa fa-envelope-o"></i>Calcular</button> -->
                <a href="<?= site_url() . "/torpes/admin/jornada/puntos/" . $idTemporada . "/" . $numJornada ?>" class="btn btn-template-main">Calcular</a>

            </div>
            <div class="col-sm-4 text-center">
                <!-- <button class="btn btn-template-main" type="submit"><i class="fa fa-envelope-o"></i>Calcular</button> -->
                <a href="<?= site_url() . "/torpes/admin/jornada/nueva/" . $idTemporada ?>" class="btn btn-template-main">Nueva</a>

            </div>

            </form>

        </div>

    </div>
    <div class="col-md-3" id="blog-post">
        <div class="panel panel-default sidebar-menu">
            <div class="panel-heading">
                <h3 class="panel-title">Clasificación</h3>
            </div>

            <table class="table">
                <tbody>
                    <tr>
                        <td style="background:#fafafa"><center>Pos.</center></td>
                <td style="background:#fafafa"><center>Nombre</center></td>
                <td style="background:#fafafa"><center>Puntos</center></td>

                </tr>
                <tr>
                    <td style="background:#ffffff"><center>1</center></td>
                <td style="background:#ffffff">Nacho</td>
                <td style="background:#ffffff">103</td>

                </tr>
                <tr>
                    <td style="background:#ffffff"><center>2</center></td>
                <td style="background:#ffffff">Julio</td>
                <td style="background:#ffffff">97</td>

                </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>



<?php $this->load->view('torpes/pie'); ?>
