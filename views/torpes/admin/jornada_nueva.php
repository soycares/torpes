<?php $this->load->view('torpes/cabecera') ?>


<!-- test datepicker -->

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  
        <script src="<?=base_url()?>/js/bootstrap-datetimepicker.min.js"></script>
        <link href="<?= base_url('') ?>/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
                
<script>
     
  $(function() {
    var availableTags = [
        <?php
        $i=0;
        foreach ($equipos->result() as $fila) {
            if ($i>0)
                echo ",";
            //echo '"'.$fila->nombre.'"';
            echo '{
        value: "'.$fila->idEquipo.'",
        label: "'.$fila->nombre.'"
      }';
            
            ++$i;
        }
        ?>
    ];
    
    <?php for ($i=1; $i<= $numPartidos;++$i){ ?>
    $( "#nombreLocal_<?=$i?>" ).autocomplete({
      minLength: 0,
      source: availableTags,
            focus: function( event, ui ) {
        $( "#nombreLocal_<?=$i?>" ).val( ui.item.label );
        $( "#idLocal_<?=$i?>" ).val( ui.item.value );
        return false;
      },
      select: function( event, ui ) {
        $( "#nombreLocal_<?=$i?>" ).val( ui.item.label );
        $( "#idLocal_<?=$i?>" ).val( ui.item.value );

        return false;
      }
    });
    
    $( "#nombreVisitante_<?=$i?>" ).autocomplete({
      minLength: 0,
      source: availableTags,
            focus: function( event, ui ) {
        $( "#nombreVisitante_<?=$i?>" ).val( ui.item.label );
        $( "#idVisitante_<?=$i?>" ).val( ui.item.value );
        return false;
      },
      select: function( event, ui ) {
        $( "#nombreVisitante_<?=$i?>" ).val( ui.item.label );
        $( "#idVisitante_<?=$i?>" ).val( ui.item.value );

        return false;
      }
    });

    <?php } ?>
  });
 
  

    $("#btnEnviar").on("click", function(){
    console.log("Enviando formulario...");
    return false;
    });

   $(document).ready(function(){
        $("#frmDatos").find(':select').each(function() {
         var elemento= this;
         alert("elemento.id="+ elemento.id + ", elemento.value=" + elemento.value); 
        });
       });
  </script>
 

<div class="row">

    <div class="col-md-9" id="blog-post">
        <div class="row">
            <div class="heading" style="text-align: center">
                <h2>Jornada <?= $numJornada ?></h2>
            </div>
            
            <?php
            if ($error != '')
                    echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
                    
            echo form_open('torpes/admin/jornada/nueva', 'method="post" id="frmDatos"');
            echo form_hidden('numJornada', $numJornada);
            echo form_hidden('numPartidos', $numPartidos);
            echo form_hidden('idTemporada', $this->session->userdata('tor_idTemporada'));
            ?>
            <table class="table">
                <tbody>
                    <tr>
                        <td style="background:#fafafa" colspan=2><center>Partido</center></td>
                <td style="background:#fafafa" colspan=1><center>Fecha</center></td>
                </tr>
                <?php
                for ($i = 1; $i <= $numPartidos;  ++$i) {

                    echo "<tr>";
                    //echo '<td>' . form_dropdown('idLocal_' . $i, $listaEquipos['listaEquipos'], -1) . '</td>';
                    echo '<td>';
                    echo '<input id="nombreLocal_'.$i.'" name="nombreLocal_'.$i.'"  style="width: 110px">';
                    echo '<input type="hidden" name="idLocal_'.$i.'" id="idLocal_'.$i.'" style="width: 110px">';
                    //. form_dropdown('idLocal_' . $i, $listaEquipos['listaEquipos'], -1) . 
                    echo '</td>';
                    
                    echo '<td>';
                    echo '<input id="nombreVisitante_'.$i.'" name="nombreVisitante_'.$i.'" style="width: 110px">';
                    echo '<input type="hidden" name="idVisitante_'.$i.'" id="idVisitante_'.$i.'">';
                    //. form_dropdown('idLocal_' . $i, $listaEquipos['listaEquipos'], -1) . 
                    echo '</td>';

                    
                    //echo '<td>' . form_dropdown('idVisitante_' . $i, $listaEquipos['listaEquipos'], -1) . '</td>';
                    $data = array(
                        'name' => 'fecha_' . $i,
                        'id' => 'fecha_' . $i
                        , 'placeholder' => 'DD/MM/AAAA HH:MM',
                        'class' => 'form_datetime','readonly' => true
                    );

                    //echo "<td>" . form_input($data) . "</td>";
echo '<td><input size="16" style="width: 110px" type="text" readonly class="form_datetime" name="fecha_'.$i.'" id="fecha_'.$i.'"></td>';
?>
          
<?php
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
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
    
            <div class="col-sm-12 text-left">
                <h5>Jornada Activa <?= form_dropdown('activa', array('0' => 'No', '1' => 'Si'), 0)?></h5>
            </div>
            <div class="col-sm-12 text-center">
                <button class="btn btn-template-main" type="submit" id="btnEnviar"><i class="fa fa-envelope-o"></i>Enviar</button>

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
</div>



<?php $this->load->view('torpes/pie'); ?>