
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
            echo form_hidden('idUsuario', $idUsuario);
            ?>

            
            
            <div class="col-md-6 col-sm-6">
                <label for="billing_firstname">Usuario</label>
                <input id="usuario" class="form-control required" type="text" name="usuario" value="<?=$usuario?>">
            </div>
            <div class="col-md-6 col-sm-6">
                <label for="billing_firstname">Contraseña</label>
                <input id="password" class="form-control required" type="password" name="password" value="<?=$password?>">
            </div>
            
            <div class="col-md-6 col-sm-6">
                <label for="billing_firstname">Nombre</label>
                <input id="nombre" class="form-control required" type="text" name="nombre" value="<?=$nombre?>">
            </div>
            <div class="col-md-6 col-sm-6">
                <label for="billing_firstname">Email</label>
                <input id="email" class="form-control required" type="email" name="email" value="<?=$email?>">
            </div>

            
            <div class="col-md-6 col-sm-6">
                <?php
                $sel1 = "";
                $sel0="";
                    if ($activo == 1)
                        $sel1 = " selected";
                    else
                        $sel0=" selected";
                    
                ?>
                <label for="billing_firstname">Usuario Activo</label>
                <select id="activo" class="form-control required" name="activo">
                    <option value="1" <?=$sel1?>>Si</option>
                    <option value="0" <?=$sel0?>>No</option>
                </select>
            </div>
            <div class="col-md-6 col-sm-6">
                <?php
                $sel1 = "";
                $sel0="";
                    if ($rol == 1)
                        $sel1 = " selected";
                    else
                        $sel0=" selected";
                    
                ?>
                <label for="billing_firstname">Rol del Usuario</label>
                <select id="rol" class="form-control required" name="rol">
                    <option value="1" <?=$sel1?>>Administrador</option>
                    <option value="0" <?=$sel0?>>Usuario</option>
                </select>
            </div>
           
            <div class="col-sm-12 text-center" style="margin-top: 15px">
                <button class="btn btn-template-main" type="submit"><i class="fa fa-envelope-o"></i>Enviar</button>

            </div> 
            </form>

            </tbody>
            </table>
            
            
            

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
