<?php $this->load->view('torpes/cabecera') ?>

<div class="row">

    <div class="col-md-12" id="blog-post">
        <div class="row">
            <div class="heading" style="text-align: center">
                <h2>Usuarios <?= $descripcion ?></h2>
            </div>

            <div class="col-md-6" id="blog-post">
                <div class="panel panel-default sidebar-menu">
                    <div class="panel-heading">
                        <h3 class="panel-title">Usuario Incluidos</h3>
                    </div>

                    <table class="table">
                        <tbody>

                            <tr>
                                <td style="background:#fafafa" colspan=1><center>Usuario </center></td>
                        <td style="background:#fafafa" colspan=1><center>Nombre</center></td>
                        <td style="background:#fafafa" colspan=1><center>&nbsp;</center></td>
                        </tr>
                        <?php
                        foreach ($usuarios_si->result() as $fila) {
                            echo '<tr>';
                            echo '  <td>' . $fila->usuario . "</td>";
                            echo '  <td style="text-align: left">' . $fila->nombre . "</td>";

                            echo '  <td><a href="' . site_url('torpes/admin/temporadas/quitar/' . $idTemporada . '/' . $fila->idUsuario) . '"><i class="fa fa-trash"></i></a></td>';
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>

                </div>
            </div>


            <div class="col-md-6" id="blog-post">
                <div class="panel panel-default sidebar-menu">
                    <div class="panel-heading">
                        <h3 class="panel-title">Usuario No Incluidos</h3>
                    </div>

                    <table class="table">
                        <tbody>

                            <tr>
                                <td style="background:#fafafa" colspan=1><center>Usuario </center></td>
                        <td style="background:#fafafa" colspan=1><center>Nombre</center></td>
                        <td style="background:#fafafa" colspan=1><center>&nbsp;</center></td>
                        </tr>
<?php
foreach ($usuarios_no->result() as $fila) {
    echo '<tr>';
    echo '  <td>' . $fila->usuario . "</td>";
    echo '  <td style="text-align: left">' . $fila->nombre . "</td>";

    echo '  <td><a href="' . site_url('torpes/admin/temporadas/incluir/' . $idTemporada . '/' . $fila->idUsuario) . '"><i class="fa fa-plus"></i></a></td>';
    echo "</tr>";
}
?>
                        </tbody>
                    </table>

                </div>
            </div>



			<div class="col-sm-12 text-center">
<!--                <a href="<?=site_url("torpes/admin/temporadas/invitar/".$idTemporada)?>" class="btn btn-template-main"><i class="fa fa-envelope-o"></i>Invitar</a> -->

				<button type="button" class="btn btn-lg btn-template-main" data-toggle="modal" data-target=".bs-example-modal-sm" onClick="javascript:invitar()"><i class="fa fa-envelope-o"></i>Invitar</button>

            </div>


        </div>

    </div>
    <!--
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
  -->
</div>


<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="myModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <?= form_open('torpes/admin/temporadas/enviar', 'method="post"') ?>
        <input type="hidden" id="idTemporada" name="idTemporada" value="<?=$idTemporada?>">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="text-uppercase">Invitar Usuario</h3>
      </div>

      <div class="modal-body">
        <div class="row">

          <div class="col-md-12">
			<div class="form-group">
<label for="state">Email</label>
<input type="email" id="email" name="email"  class="form-control">

</div>

          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Enviar</button>
      </div>
    </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
    function invitar()
    {
       $('#myModal').modal("show");
    }
</script>

<?php $this->load->view('torpes/pie'); ?>
