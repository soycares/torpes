<?php

    $rs = $this->db->query("select numJornada from tor_temporadas a, tor_jornadas b where b.activa=1 and a.idTemporada=b.idTemporada and a.idTemporada = ".$this->session->userdata('tor_idTemporada'));
    

    if ($rs->num_rows() > 0)
    {
        $fila=$rs->row();
        $numJornada=$fila->numJornada;
    }


?>
<div class="panel panel-default sidebar-menu" id="html-content-holder-2" >
            <div class="panel-heading">
                <h3 class="panel-title">Clasificación</h3>
            </div>
            <?php
            if ($numJornada != "")
            {
            ?>
            <table class="table">
                <tbody>
                    <tr>
                        <td style="background:#fafafa"><center>Pos.</center></td>
                        <td style="background:#fafafa"><center>Nombre</center></td>
                        <td style="background:#fafafa"><center>Puntos</center></td>
                        <td style="background:#fafafa"><center>Dif.</center></td>
                    </tr>
                <?php
                 $jornada_ant="";

                if ($numJornada > 1)
                {
                    // Si no hay partidos finalizados a la fecha actual, en la clasificacion mostrar puntos de la jornada anterior
                    $fechaCierre=date ('Y-m-d H:i:s');
                    $fechaCierre = strtotime ( '+120 minute' , strtotime ( $fechaCierre  ) ) ;
                    $fechaCierre=date ('Y-m-d H:i:s',$fechaCierre);
                    // Si ningun partido de la jornada esta finalizado, coger la jornada anterior
                    // a.idJornada,d.numTemporada, a.numJornada, b.nombre as local, c.nombre as visitante, fecha
                     $rs = $this->db->query("SELECT count(1) as total 
                            FROM tor_temporadas d, tor_jornadas a
                            WHERE d.idTemporada=".$this->session->userdata('tor_idTemporada')."
                            and d.idTemporada=a.idTemporada
                            and a.activa = 1
                            and fecha <= '".$fechaCierre."'");
                    $fila = $rs->row();
                    $num_finalizados = $fila->total;
                    // Si no hay finalizados de la jornada activa, coger puntos y posicion de la anterior
                    //if ($num_finalizados == 0 || $num_finalizados == 4)
                    
                    if ($num_finalizados == 0 || $num_finalizados == 4)
                    {
                            $numJornada--;
                            if ($numJornada > 1)
                                $numJornada--;
                    } 
                    else
                        $numJornada--;
                    
                    $rs = $this->db->query("SELECT nombreUsuario, sum(puntos_total) as puntos_total FROM v_tor_consulta_nueva
                        WHERE numJornada <= ".($numJornada  )." 
                            AND idTemporada=".$this->session->userdata('tor_idTemporada')."
                        group by nombreUsuario
                        order by sum(puntos_total) desc");

                    $jornada_ant=array();
                    $i=1;
                    foreach ($rs->result() as $fila){
                        $jornada_ant[$fila->nombreUsuario] = array('puntos' => $fila->puntos_total, 'posicion' => $i);
                        ++$i;
                    }
                }
                $rs = $this->db->query("SELECT nombre, sum(puntos_total) as puntos_total 
                        FROM tor_apuestas a, tor_usuarios b, tor_temporadas c, tor_jornadas d
                        WHERE a.idUsuario=b.idUsuario
                        and c.idTemporada=".$this->session->userdata('tor_idTemporada')."
                        and c.idTemporada=d.idTemporada
                        and d.idJornada = a.idJornada
                        group by nombre
                        order by sum(puntos_total) desc");
                $i=0;

				$puntos_anterior = "";
                foreach ($rs->result() as $fila){
                    $i++;
                ?>
                <tr>
                    <td style="background:#ffffff">
                    
                        <?php 
                        if ($i == 1 && $this->session->userdata('tor_imp_general_1') > 0 )
                            echo '<span class="label label-success">'.$i.'</span>';
                        else 
                            if ($i == 2 && $this->session->userdata('tor_imp_general_2') > 0 )
                                echo '<span class="label label-success">'.$i.'</span>';
                            else
                                if ($i == 3 && $this->session->userdata('tor_imp_general_3') > 0 )
                                    echo '<span class="label label-success">'.$i.'</span>';
                                else
                                    if ($i == 4 && $this->session->userdata('tor_imp_general_4') > 0 )
                                        echo '<span class="label label-success">'.$i.'</span>';
                                    else
                                        echo $i;
                        ?>
                        <?php
                            if ($jornada_ant != "" && $i > $jornada_ant[$fila->nombre]['posicion'])
                                echo '&nbsp; <i class="fa fa-level-down fa-1" style="color:#E01F1F"
                                data-toggle="tooltip" data-placement="top" title="'. $jornada_ant[$fila->nombre]['posicion'].'"></i>';
                            else if ($jornada_ant != "" && $i < $jornada_ant[$fila->nombre]['posicion'])
                                echo '&nbsp; <i class="fa fa-level-up fa-1" style="color:#1B9B33"
                                data-toggle="tooltip" data-placement="top" title="'. $jornada_ant[$fila->nombre]['posicion'].'"></i>';        
                        ?>
                                           </td>
                    <td style="background:#ffffff"><?=$fila->nombre?></td>
                    <td style="background:#ffffff">
                        <?php 
                        
                        echo $fila->puntos_total."&nbsp";
                        if ($jornada_ant != "")
                            $dif_puntos = $fila->puntos_total - $jornada_ant[$fila->nombre]['puntos'];
                        else
                            $dif_puntos = $fila->puntos_total;
                        
                        if ($dif_puntos > 0)
                                echo '&nbsp;<span class="label label-success">+'.$dif_puntos."</span>";
                        else 
                                if ($dif_puntos < 0)
                                        echo '&nbsp;<span class="label label-danger">'.$dif_puntos."</span>";
                                else
                                     if ($dif_puntos == 0)
                                         echo '&nbsp;<span class="label label-success">'.$dif_puntos."</span>";

                        ?>
                    </td>
	<td>
	<?php
	if ($puntos_anterior == "")
		echo "&nbsp;";
	else
        {
            $pts=$fila->puntos_total - $puntos_anterior;
		echo '<span class="label label-info">'.$pts."</span>";
        }
	?>
	</td>
                </tr>
                <?php 
						$puntos_anterior = $fila->puntos_total;
				} ?>
                </tbody>
            </table>
            <?php } ?>
    
        </div>
 
<div class="col-sm-12 text-center">

        <span class="label label-success">Puntos jornada positivos</span>
        <span class="label label-danger">Puntos jornada negativos</span>

       
       
        <span class="label label-info">Diferencia con la posición anterior</span>
    
    </div>
<div class="col-sm-12 text-center">&nbsp;</div>


<?php 
    // Boton descargar solo para administradores
    if ($this->session->userdata("tor_rol") == 1 ) 
    { 
?>
<!--    <script src="<?= base_url('') ?>/js/jquery-1.11.0.min.js"></script>
    <script src="<?= base_url('') ?>/js/html2canvas.min.js"></script>
  -->  
    <!-- <script type="text/javascript" src="https://github.com/niklasvh/html2canvas/releases/download/0.4.1/html2canvas.js"></script> -->

<script>
$(document).ready(function () {

	var element = $("#html-content-holder-2"); // global variable
	var getCanvas; // global variable
	$("#btn-Convert-Html2Image-2").hide();
	
	    $("#btn-Preview-Image-2").on('click', function () {
         html2canvas(element, {
			 onrendered: function (canvas) {
					//$("#previewImage").append(canvas);
					getCanvas = canvas;
					$("#btn-Convert-Html2Image-2").show();
					$("#btn-Preview-Image-2").hide();
					
			 }
         });
    });
	$("#btn-Convert-Html2Image-2").on('click', function () {
		var imgageData = getCanvas.toDataURL("image/png");
		// Now browser starts downloading it instead of just showing it
		var newData = imgageData.replace(/^data:image\/png/, "data:application/octet-stream");
		$("#btn-Convert-Html2Image-2").attr("download", "Clasificacion General.png").attr("href", newData);
	});

}); 
</script>


    <div class="col-sm-12 col-lg-12 col-xs-12">
        <h5><center><a id="btn-Convert-Html2Image-2" href="#">Descargar Imagen</a></center></h5>
        <h5><center><input id="btn-Preview-Image-2" type="button" value="Crear Imagen"/></center></h5>
        <div id="previewImage"></div>
    </div>
<?php } ?>
