<?php

    $this->db->where('idTemporada',$this->session->userdata("tor_idTemporada"));
            $rs_temp = $this->db->get('tor_temporadas');
            $fila_temp=$rs_temp->row();

            $numTramos=$fila_temp->num_tramos;
            $jornadasTramos=$fila_temp->num_tramos_jornadas;
            //$numJornada=30;
			if ($numTramos == 0)
				return;

if (!isset($numJornada))
{
    $rs = $this->db->query("select numJornada from tor_temporadas a, tor_jornadas b where a.activa=1 and a.idTemporada=b.idTemporada and b.activa=1");
    if ($rs->num_rows() > 0)
    {
        $fila=$rs->row();
        $numJornada=$fila->numJornada;
    }

}

if ($numJornada >= 1)
    {
        // Si no hay partidos finalizados a la fecha actual, en la clasificacion mostrar puntos de la jornada anterior
        $fechaCierre=date ('Y-m-d H:i:s');
        $fechaCierre = strtotime ( '+120 minute' , strtotime ( $fechaCierre  ) ) ;
        $fechaCierre=date ('Y-m-d H:i:s',$fechaCierre);
        // Si ningun partido de la jornada esta finalizado, coger la jornada anterior
        // a.idJornada,d.numTemporada, a.numJornada, b.nombre as local, c.nombre as visitante, fecha
         $rs = $this->db->query("SELECT count(1) as total
                FROM tor_temporadas d, tor_jornadas a
                WHERE d.idTemporada = ".$this->session->userdata("tor_idTemporada")."
                and d.idTemporada=a.idTemporada
                and a.activa = 1
                and fecha <= '".$fechaCierre."'");
        $fila = $rs->row();
        $num_finalizados = $fila->total;
        // Si no hay finalizados de la jornada activa, coger puntos y posicion de la anterior
        if ($num_finalizados == 0)
                $numJornada--;
    }


?>

<!-- PRUEBA -->






<!-- FIN PRUEBA -->

        <div class="panel panel-default sidebar-menu">
            <div class="panel-heading">
                <h3 class="panel-title">Tramos</h3>
            </div>
          </div>

            <?php
            if ($numJornada == 0)
            {
                echo "No hay jornadas";
            }
            else
            {
            ?>
            <div class="panel-group accordion" id="accordionThree">
            <?php



            // tramo actual
            $tramoActual = floor($numJornada / $jornadasTramos);
            if (($numJornada % $jornadasTramos) == 0)
            $tramoActual--;

            for ($i=1; $i<=$numTramos;++$i)
            {
              ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordionThree" href="#collapse<?=$i?>a">Tramo <?=$i?></a>
                            </h4>
                    </div>

                    <div id="collapse<?=$i?>a" class="panel-collapse collapse <?php if($i == ($tramoActual + 1 ))  echo "in"; else echo "out"; ?>">


                        <div class="panel-body">
                          <div class="row" style="border-bottom: 1px solid #ccc;margin-left:1px;margin-right:1px;padding-bottom:2px;padding-top:5px;background:#fafafa">
                              <div class="col-sd-2 col-md-2 col-xs-2">Pos.</div>
                              <div class="col-sd-8 col-md-8 col-xs-8">Nombre</div>
                              <div class="col-sd-2 col-md-2 col-xs-2">Pts.</div>
                          </div>
                          <?php
                          // clasificacion por tramo
                           $tramoIni = ($i -1) * $jornadasTramos;
                           $tramoFin = ($i ) * $jornadasTramos;

                           $rs2 = $this->db->query("SELECT nombreUsuario, sum(puntos_total)+sum(puntos_extra) as puntos_total FROM v_tor_consulta_nueva
                                                    WHERE numJornada>".($tramoIni)." and numJornada <=".$tramoFin."
                                                        and idTemporada=".$this->session->userdata("tor_idTemporada")."
                                                    group by nombreUsuario
                                                    order by sum(puntos_total)+sum(puntos_extra) desc");
                           $j=0;
                           foreach ($rs2->result() as $fila){
                               $j++;
							   $fondo1="";
                               if ($this->session->userdata("tor_imp_tramo_1") != "" && $j==1)
									$fondo1=' background: rgb(218, 246, 219) ';
								if ($this->session->userdata("tor_imp_tramo_2") != "" && $j==2)
									$fondo1=' background: rgb(218, 246, 219) ';
								//if ($this->session->userdata("tor_imp_tramo_2") != "" && $j==3)
								//	$fondo1=' background: rgb(218, 246, 219) ';
                          ?>
                            <div class="row" style="border-bottom: 1px solid #ccc;margin-left:1px;margin-right:1px;padding-bottom:2px;padding-top:5px;<?=$fondo1?>" >
                                <div class="col-sd-2 col-md-2 col-xs-2"><?=$j?></div>
                                <div class="col-sd-8 col-md-8 col-xs-8"><?=$fila->nombreUsuario?></div>
                                <div class="col-sd-2 col-md-2 col-xs-2"><?=$fila->puntos_total?></div>
                            </div>

                            <?php
            }
                             ?>
                        </div>
                    </div>
                </div>
            <?php } ?>


            </div>
            <?php } ?>
