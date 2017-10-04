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
                    
            echo form_open('torpes/admin/jornada/modificar', 'method="post" id="frmDatos"');
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
                $num_partidos_enviados = $partidos->num_rows();
                $jornada_activa = 0;

                for ($i = 1; $i <= $numPartidos;  ++$i) {
                    if ($i > $num_partidos_enviados)
                    {
                        $valor1="";
                        $valor2="";
                        $id1="";
                        $id2="";
                        $fecha="";
                        $idJornada="";
                    }
                    else
                    {
                        $fila = $partidos->unbuffered_row();
                        $valor1=$fila->nombreLocal;
                        $valor2=$fila->nombreVisitante;
                        $id1=$fila->idLocal;
                        $id2=$fila->idVisitante;
                        $fecha=$fila->fecha;
                        $idJornada=$fila->idJornada;
                        if ($jornada_activa == 0 && $fila->activa == 1)
                            $jornada_activa =1;
                    }
                    echo "<tr>";
                    //echo '<td>' . form_dropdown('idLocal_' . $i, $listaEquipos['listaEquipos'], -1) . '</td>';
                    echo '<td>';
                    echo '<input id="nombreLocal_'.$i.'" name="nombreLocal_'.$i.'"  style="width: 110px" value="'.$valor1.'">';
                    echo '<input type="hidden" name="idLocal_'.$i.'" id="idLocal_'.$i.'" style="width: 110px" value="'.$id1.'">';
                    echo '<input type="hidden" name="idJornada_'.$i.'" id="idJornada_'.$i.'" value="'.$idJornada.'">'."\n";
                 
                    echo '</td>';
                    
                    echo '<td>';
                    echo '<input id="nombreVisitante_'.$i.'" name="nombreVisitante_'.$i.'" style="width: 110px" value="'.$valor2.'">';
                    echo '<input type="hidden" name="idVisitante_'.$i.'" id="idVisitante_'.$i.'"  value="'.$id2.'">';
                 
                    echo '</td>';
                 
                    $data = array(
                        'name' => 'fecha_' . $i,
                        'id' => 'fecha_' . $i
                        , 'placeholder' => 'DD/MM/AAAA HH:MM',
                        'class' => 'form_datetime','readonly' => true
                    );

                    //echo "<td>" . form_input($data) . "</td>";
                    echo '<td><input size="16" style="width: 110px" type="text" readonly class="form_datetime" name="fecha_'.$i.'" id="fecha_'.$i.'" value="'.$fecha.'"></td>'."\n";                    
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
                <h5>Jornada Activa  <?= form_dropdown('activa', array('0' => 'No', '1' => 'Si'), $jornada_activa)?></h5>
            </div>
            <div class="col-sm-12 text-center">
                <button class="btn btn-template-main" type="submit" id="btnEnviar"><i class="fa fa-envelope-o"></i>Enviar</button>

            </div> 
            </form>




<?php 
