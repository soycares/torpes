
<?php $this->load->view('torpes/cabecera') ?>


<!-- test datepicker -->

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  
        <script src="<?=base_url()?>/js/bootstrap-datetimepicker.min.js"></script>
        <link href="<?= base_url('') ?>/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
                
<!--<input size="16" type="text" readonly class="form_datetime1">
     
    <script type="text/javascript">
        $(".form_datetime").datetimepicker(
                {
                    format: 'yyyy-mm-dd hh:ii',
            autoclose:true,
             minuteStep: 15,
             todayHighlight:true,
             language:'es',
             startDate: '2015-10-12 12:00:00',
             weekStart: 1
                   
        });
    </script>            
    -->

<div class="row">

    <div class="col-md-9" id="blog-post">
        <div class="row">
            <div class="heading" style="text-align: center">
                <h2>Editar Usuario</h2>
            </div>
            
            <?php
            if ($error != '')
                    echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
                    
            echo form_open('torpes/admin/usuarios/grabar', 'method="post"');
            
            ?>

            
            
            <div class="col-md-6 col-sm-6">
                <label for="billing_firstname">Usuario</label>
                <input id="usuario" class="form-control required" type="text" name="usuario" >
            </div>
            <div class="col-md-6 col-sm-6">
                <label for="billing_firstname">Contraseña</label>
                <input id="password" class="form-control required" type="text" name="password" >
            </div>
            
            <div class="col-md-6 col-sm-6">
                <label for="billing_firstname">Nombre</label>
                <input id="nombre" class="form-control required" type="text" name="nombre" >
            </div>
            <div class="col-md-6 col-sm-6">
                <label for="billing_firstname">Email</label>
                <input id="email" class="form-control required" type="email" name="email" >
            </div>

            
            <div class="col-md-6 col-sm-6">
                <label for="billing_firstname">Usuario Activo</label>
                <select id="activo" class="form-control required" name="activo">
                    <option value="1" >Si</option>
                    <option value="0" >No</option>
                </select>
            </div>
            <div class="col-md-6 col-sm-6">
                <label for="billing_firstname">Rol del Usuario</label>
                <select id="rol" class="form-control required" name="rol">
                    <option value="1" >Administrador</option>
                    <option value="0" >Usuario</option>
                </select>
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
