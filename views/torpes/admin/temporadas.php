<?php $this->load->view('torpes/cabecera') ?>
<div class="row">

    <div class="col-md-9 col-sm-9 hidden-xs" id="blog-post">
        <div class="row">
            <div class="heading" style="text-align: center">
                <h2>Temporadas</h2>
            </div>
            <?= form_open('torpes/admin/temporadas/nuevo', 'method="post"') ?>
            <table class="table">
                <tbody>
                    <tr>
                        <td style="background:#fafafa" colspan="4">
                            
                        </td>
                        <td  colspan="4" style="background:#fafafa;border-left:1px solid #DDD; border-right:1px solid #DDD">
                <center>Puntos</center>
                        </td>
                        <td style="background:#fafafa"  colspan="2">
                            
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#fafafa" colspan=1><center>Nombre</center></td>
                        <td style="background:#fafafa" colspan=1>Partidos</td>
                        <td style="background:#fafafa" colspan=1>Temp.</td>
                        <td style="background:#fafafa" colspan=1>Jor.</td>
                        <td style="background:#fafafa;border-left:1px solid #DDD;" colspan=1>Res.</td>
                        <td style="background:#fafafa" colspan=1>Signo</td>
                        <td style="background:#fafafa" colspan=1>Goles</td>
                        <td style="background:#fafafa;border-right:1px solid #DDD" colspan=1>Resta</td>
                        <td style="background:#fafafa" colspan=1>Activa</td>
                        <td style="background:#fafafa" colspan=1><center>&nbsp;</center></td>
                </tr>
                <?php

                foreach ($temporadas->result() as $fila) {
                    echo '<tr>';
                    echo '  <td>' . $fila->descripcion. "</td>";
                    echo '  <td style="text-align: center">' . $fila->numPartidos. "</td>";
                    echo '  <td style="text-align: center">' . $fila->numTemporada. "</td>";
                    echo '  <td style="text-align: center">' . $fila->numJornadas. "</td>";
                    echo '  <td style="text-align: center">' . $fila->num_puntos_res. "</td>";
                    echo '  <td style="text-align: center">' . $fila->num_puntos_signo. "</td>";
                    echo '  <td style="text-align: center">' . $fila->num_puntos_goles. "</td>";
                    echo '  <td style="text-align: center">' . $fila->num_puntos_resta. "</td>";
                    if ($fila->activa == 1)
                    echo '  <td style="text-align: center"><b>Si</b></td>';
                    else
                        echo '  <td style="text-align: center">No</td>';
                    
                    
                    echo '  <td><a href="'.site_url('torpes/admin/temporadas/editar/'.$fila->idTemporada).'"><i class="fa fa-pencil-square-o"></i></a>
                            &nbsp;&nbsp;<a href="'.site_url('torpes/admin/temporadas/borrar/'.$fila->idTemporada).'"><i class="fa fa-trash-o"></i></a>
                            &nbsp;&nbsp;<a href="'.site_url('torpes/admin/temporadas/usuarios/'.$fila->idTemporada).'"><i class="fa fa-user"></i></a></td>';
                    echo "</tr>";
                }

                ?>
                            </tbody>
            </table>
            <div class="col-sm-12 text-center">
                <button class="btn btn-template-main" type="submit"><i class="fa fa-envelope-o"></i>Nueva</button>

            </div>                            
            
            </form>

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


<div class="row">
<div class="col-xs-12 hidden-sm hidden-md" id="blog-post">
        <div class="row">
            <div class="heading" style="text-align: center">
                <h2>Temporadas</h2>
            </div>
            <?= form_open('torpes/admin/temporadas/nuevo', 'method="post"') ?>
            <table class="table" style=" margin-left: 13px;">
                <tbody>
                    
                    <tr>
                        <td style="background:#fafafa" colspan=1><center>Nombre</center></td>
                        
                        <td style="background:#fafafa" colspan=1>J.</td>
                       
                        <td style="background:#fafafa" colspan=1>Ac.</td>
                        <td style="background:#fafafa" colspan=1><center>&nbsp;</center></td>
                </tr>
                <?php

                foreach ($temporadas->result() as $fila) {
                    echo '<tr>';
                    echo '  <td>' . $fila->descripcion. "</td>";
                    
                    
                    echo '  <td style="text-align: center">' . $fila->numJornadas. "</td>";
                    if ($fila->activa == 1)
                    echo '  <td style="text-align: center"><b>Si</b></td>';
                    else
                        echo '  <td style="text-align: center">No</td>';
                    
                    
                    echo '  <td><a href="'.site_url('torpes/admin/temporadas/editar/'.$fila->idTemporada).'"><i class="fa fa-pencil-square-o"></i></a>
                            <a href="'.site_url('torpes/admin/temporadas/borrar/'.$fila->idTemporada).'"><i class="fa fa-trash-o"></i></a>
                            <a href="'.site_url('torpes/admin/temporadas/usuarios/'.$fila->idTemporada).'"><i class="fa fa-user"></i></a></td>';
                    echo "</tr>";
                }

                ?>
                            </tbody>
            </table>
            <div class="col-sm-12 text-center">
                <button class="btn btn-template-main" type="submit"><i class="fa fa-envelope-o"></i>Nueva</button>

            </div>                            
            
            </form>

        </div>
</div>



<?php $this->load->view('torpes/pie'); ?>
