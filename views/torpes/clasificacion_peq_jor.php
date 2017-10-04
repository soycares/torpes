<?php
if (!isset($numJornada) || $numJornada == 1)
{
    $rs = $this->db->query("select numJornada,fecha 
							from tor_temporadas a, tor_jornadas b 
							where a.activa=1 and a.idTemporada=b.idTemporada and b.idTemporada=".$this->session->userdata('tor_idTemporada')." order by fecha ");
    if ($rs->num_rows() > 0)
    {
        $fila=$rs->row();
        $numJornada=$fila->numJornada;
        ;
    }
    else
        return;

}

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
        if ($num_finalizados == 0)
                $numJornada--;
    }
    else
    {
		$fechaCierre = $fila->fecha;
	}
?>




        <div class="panel panel-default sidebar-menu" id="html-content-holder">
            <div class="panel-heading">
                <h3 class="panel-title">Jornada <?=$numJornada?></h3>
            </div>
            <?php
            $fecha_ahora = date('Y-m-d H:i:s');
            if ($numJornada == 1 &&  $fechaCierre  >= strtotime($fecha_ahora))
            {
                echo "No hay jornadas";
            }
            else
            {
            ?>
            <table class="table">
                <tbody>
                    <tr>
                        <td style="background:#fafafa"><center>Pos.</center></td>
                <td style="background:#fafafa"><center>Nombre</center></td>
                <td style="background:#fafafa"><center>Puntos</center></td>

                </tr>
                <?php

                $rs = $this->db->query("SELECT nombreUsuario, sum(puntos_total) as puntos_total FROM v_tor_consulta_nueva
WHERE numJornada=".($numJornada)."
    and idTemporada=".$this->session->userdata('tor_idTemporada')."
group by nombreUsuario
order by sum(puntos_total) desc");
                $i=0;
                foreach ($rs->result() as $fila){
                    $i++;

                ?>
                <tr>
                    <td style="background:#ffffff"><center>
                        <?php 
                        if ($i == 1 && $this->session->userdata('tor_imp_jornada_1') > 0 )
                            echo '<span class="label label-success">'.$i.'</span>';
                        else 
                            if ($i == 2 && $this->session->userdata('tor_imp_jornada_2') > 0 )
                                echo '<span class="label label-success">'.$i.'</span>';
                            else
                                if ($i == 3 && $this->session->userdata('tor_imp_jornada_3') > 0 )
                                    echo '<span class="label label-success">'.$i.'</span>';
                                else
                                    if ($i == 4 && $this->session->userdata('tor_imp_jornada_4') > 0 )
                                        echo '<span class="label label-success">'.$i.'</span>';
                                else
                                    echo $i;
                        ?>
                    </center></td>
                    <td style="background:#ffffff"><?=$fila->nombreUsuario?></td>
                    <td style="background:#ffffff"><?=$fila->puntos_total?></td>

                </tr>
                <?php } ?>
                </tbody>
            </table>
            <?php } ?>

        </div>


<?php 
    // Boton descargar solo para administradores
    if ($this->session->userdata("tor_rol") == 1 ) 
    { 
?>
    <script src="<?= base_url('') ?>/js/jquery-1.11.0.min.js"></script>
    <script src="<?= base_url('') ?>/js/html2canvas.min.js"></script>
    
    <!-- <script type="text/javascript" src="https://github.com/niklasvh/html2canvas/releases/download/0.4.1/html2canvas.js"></script> -->

<script>
$(document).ready(function () {

	var element = $("#html-content-holder"); // global variable
	var getCanvas; // global variable
	$("#btn-Convert-Html2Image").hide();
	
	    $("#btn-Preview-Image").on('click', function () {
         html2canvas(element, {
			 onrendered: function (canvas) {
					//$("#previewImage").append(canvas);
					getCanvas = canvas;
					$("#btn-Convert-Html2Image").show();
					$("#btn-Preview-Image").hide();
					
			 }
         });
    });
	$("#btn-Convert-Html2Image").on('click', function () {
		var imgageData = getCanvas.toDataURL("image/png");
		// Now browser starts downloading it instead of just showing it
		var newData = imgageData.replace(/^data:image\/png/, "data:application/octet-stream");
		$("#btn-Convert-Html2Image").attr("download", "Clasificacion Jornada <?=$numJornada?>.png").attr("href", newData);
	});

}); 
</script>


    <div class="col-sm-12 col-lg-12 col-xs-12">
        <h5><center><a id="btn-Convert-Html2Image" href="#">Descargar Imagen</a></center></h5>
        <h5><center><input id="btn-Preview-Image" type="button" value="Crear Imagen"/></center></h5>
        <div id="previewImage"></div>
    </div>
<?php } ?>