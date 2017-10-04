<?php $this->load->view('torpes/cabecera') ?>

<div class="row">

    <div class="col-md-9" id="blog-post">
        <div class="row">
            <div class="heading" style="text-align: center">
                <h2>Equipos</h2>
            </div>
            <?= form_open('torpes/admin/equipos/nuevo', 'method="post"') ?>
            <table class="table">
                <tbody>
                    <tr>
                        <td style="background:#fafafa" colspan=4><center>Equipo</center></td>
                <td style="background:#fafafa" colspan=1><center>&nbsp;</center></td>
                </tr>
                <?php

                foreach ($equipos->result() as $fila) {
                    echo '<tr>';
                    echo '<td>' . $fila->nombre. "</td>";
                        echo '<td><a href="'.site_url('torpes/admin/equipos/editar/'.$fila->idEquipo).'">Editar</a>
                            &nbsp;&nbsp;<a href="'.site_url('torpes/admin/equipos/borrar/'.$fila->idEquipo).'">Borrar</a></td>';
                        
                }
                echo "</tr>";
                ?>
                            </tbody>
            </table>
            <div class="col-sm-6 text-center">
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