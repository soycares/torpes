<?php $this->load->view('torpes/cabecera') ?>

<div class="row">

    <div class="col-md-9" id="blog-post">
        <div class="row">
            <div class="heading" style="text-align: center">
                <h2>Usuarios</h2>
            </div>
            <?= form_open('torpes/admin/usuarios/nuevo', 'method="post"') ?>
            <table class="table">
                <tbody>
  
                    <tr>
                        <td style="background:#fafafa" colspan=1><center>Usuario</center></td>
                        


<td style="background:#fafafa" colspan=1>Nombre</td>
<td style="background:#fafafa" colspan=1>Email</td>
<td style="background:#fafafa" colspan=1>Rol</td>
<td style="background:#fafafa" colspan=1>Activo</td>
<td style="background:#fafafa" colspan=1><center>&nbsp;</center></td>
                </tr>
                <?php

                foreach ($usuarios->result() as $fila) {
                    echo '<tr>';
                    echo '  <td>' . $fila->usuario. "</td>";
                    
                    echo '  <td style="text-align: center">' . $fila->nombre. "</td>";
                    echo '  <td style="text-align: center">' . $fila->email. "</td>";

                    if ($fila->rol == 1)
                    echo '  <td style="text-align: center"><b>Administrador</b></td>';
                    else
                        echo '  <td style="text-align: center">Usuario</td>';

                    if ($fila->activo == 1)
                    echo '  <td style="text-align: center"><b>Si</b></td>';
                    else
                        echo '  <td style="text-align: center">No</td>';
                    
                    
                    echo '  <td><a href="'.site_url('torpes/admin/usuarios/editar/'.$fila->idUsuario).'"><i class="fa fa-pencil-square-o"></i></a>
                            &nbsp;&nbsp;<a href="'.site_url('torpes/admin/usuarios/borrar/'.$fila->idUsuario).'"><i class="fa fa-trash-o"></i></a></td>';
                    echo "</tr>";
                }

                ?>
                            </tbody>
            </table>
            <div class="col-sm-12 text-center">
                <button class="btn btn-template-main" type="submit"><i class="fa fa-envelope-o"></i>Nuevo</button>

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