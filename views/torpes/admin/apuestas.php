<?php $this->load->view('torpes/cabecera') ?>
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script>

    $(document).ready(function () {

        $("#idJornada").change(function () {

            $(location).attr('href', '<?= base_url('torpes/admin/apuestas/listado/') ?>/' + $('#idJornada').val());




        });




    });

</script>
<div class="row">

    <div class="col-md-9" id="blog-post">
        <div class="row">
            <div class="heading" style="text-align: center">
                <h2>Jornada <?= $numJornada ?></h2>
            </div>
            <?= form_open('torpes/admin/apuestas/nueva', 'method="post"') ?>
            <table class="table">
                <tbody>
                    <tr>
                        <td style="background:#fafafa"><center>Usuario</center></td>
                <td style="background:#fafafa" colspan="2"><center>Resultado</center></td>
                <td style="background:#fafafa"><center>Opciones</center></td>
                </tr>
                <?php
                $i = 0;
                foreach ($datos["jornadas_completas"] as $idJornada => $fila) {

                    $i = 0;
                    foreach ($fila as $idUsuario => $datos) {
                        if ($datos["cerrada"] == 1 || $jornadaCerrada == 1) {
//                            echo "Usuario=$idUsuario<br>";
//                            echo $datos["nombreUsuario"]."<br>";
//                            echo "<b>JORNADA CERRADA</b><br>";
                            if ($i == 0) {
                                echo "<tr>";
                                echo "<td colspan='4' style='background:#fafafa'><h4>" . $datos['equipoLocal'] . " - " . $datos['equipoVisitante'] . "</h4>";
                                echo "</tr>";
                            }
                            ++$i;
                            echo "<tr>";
                            echo "<td>" . $datos['nombreUsuario'] . "</td>";
                            echo "<td class='text-center'>" . $datos['golesLocal'] . "</td>";
                            echo "<td  class='text-center'>" . $datos['golesVisitante'] . "</td>";
                            echo '<td class="text-center">
                                <button type="button" class="btn btn-xs btn-template-main" data-toggle="modal" data-target=".bs-example-modal-sm" onClick="javascript:modificar('.$datos['idApuesta'].','.$datos['golesLocal'] .','.$datos['golesVisitante'] .')">Modificar</button>';
                            echo '&nbsp<a href="torpes/admin/apuestas/borrar/' . $datos['idApuesta'] . '" class="btn btn-xs btn-danger"  >Borrar</a></td>';
                            echo "</tr>";
                        }
                        /*                      else
                          {
                          echo "Usuario=$idUsuario<br>";
                          echo $datos["nombreUsuario"]."<br>";
                          echo "<b>JORNADA ABIERTA -> ignorar</b><br>";

                          }
                         */
                    }
                }
                echo "</tr>";
                ?>
                </tbody>
            </table>
            <div class="col-sm-12 col-xs-12 text-center">
                <button class="btn btn-template-main" type="submit"><i class="fa fa-envelope-o"></i>Nueva</button>

            </div>                            
            </form>

        </div>

    </div>
    <div class="col-md-3" id="blog-post">
        <div class="panel panel-default sidebar-menu">
            <br>
<?php $this->load->view('torpes/clasificacion_peq_jor'); ?>
<?php $this->load->view('torpes/clasificacion_peq'); ?>
<?php $this->load->view('torpes/ganancias_peq'); ?>

        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="myModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <?= form_open('torpes/admin/apuestas/modificar_resultado', 'method="post"') ?>
        <input type="hidden" id="idAp" name="idAp">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="text-uppercase">Modificar Resultado</h3>
      </div>
        
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
          <div class="form-group">
<label for="state">Goles Local</label>
<select id="golesL" name="golesL"  class="form-control">
        <option value="-1"></option>
    <option value="0">0</option>
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
    <option value="6">6</option>
    <option value="7">7</option>
    <option value="8">8</option>
    <option value="9">9</option>
    <option value="10">10</option>

    
</select>
</div>
          </div>
          <div class="col-md-6">
                    <div class="form-group">
<label for="state">Goles Visitante</label>
<select id="golesV" name="golesV"  class="form-control">
    <option value="-1"></option>
    <option value="0">0</option>
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
    <option value="6">6</option>
    <option value="7">7</option>
    <option value="8">8</option>
    <option value="9">9</option>
    <option value="10">10</option>
</select>
</div>

          </div>
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Grabar</button>
      </div>
    </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script>
    
       
    function modificar(id,golesL, golesV)
    {
        $('#idAp').val(id);
       $('#golesL').val(golesL);
       $('#golesV').val(golesV);
       $('#myModal').modal("show");
    }
</script>

<?php $this->load->view('torpes/pie'); ?>


