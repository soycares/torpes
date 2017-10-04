        <div class="panel panel-default sidebar-menu">
            <div class="panel-heading">
                <h3 class="panel-title">ESTADISTICAS</h3>
            </div>

<div class="tabs">
    <ul class="nav nav-tabs nav-justified">
        <li class="active"><a href="#tab2-1" data-toggle="tab" aria-expanded="true">Aciertos</a></li>
        <li class=""><a href="#tab2-2" data-toggle="tab" aria-expanded="false">Goles</a></li>
    </ul>
    <div class="tab-content tab-content-inverse">
        <div class="tab-pane active" id="tab2-1">


                <?php
        $idTemporada = $this->session->userdata('tor_idTemporada');
        
        $resumen=$this->db->query("select nombre, sum(puntos_res) as puntos_res, sum(puntos_signo) as puntos_signo 
            from tor_apuestas a, tor_jornadas b, tor_usuarios c 
            where b.idJornada=a.idJornada 
            and b.idTemporada=".$idTemporada." 
            and a.idUsuario=c.idUsuario
            and b.activa = 0
            group by nombre
            order by sum(puntos_res) desc, sum(puntos_signo) desc");
        

        
        $numPartidos = $this->session->userdata('tor_numPartidos');

        if ($numJornada > 1)
             $numJornada--;
        
 
        $this->load->model('Torpes_model');       
        $datos_puntos = $this->Torpes_model->temporada_puntos($idTemporada);


        $puntos_signo = $datos_puntos['num_puntos_signo'];
        $puntos_res = $datos_puntos['num_puntos_res'];
        


        echo '<table class="table">';
        echo '<tbody><tr>';
        echo '<td style="background:#fafafa">Nombre</td>';
        echo '<td style="background:#fafafa">PA</td>';
        echo '<td style="background:#fafafa">SA</td>';
        echo '</tbody></tr>';
        foreach ($resumen->result() as $fila){

            $tot_res = round(($fila->puntos_res / $puntos_res) * 100 /($numJornada * 4),0);
            $tot_signo = round(($fila->puntos_signo / $puntos_signo) * 100 /($numJornada * 4),0);
            echo '<tr>';
            echo '<td style="background:#ffffff">'.$fila->nombre."</td>";
            echo '<td style="background:#ffffff">'.($fila->puntos_res / $puntos_res)." (".$tot_res."%)</td>";
            
            echo '<td style="background:#ffffff">'.($fila->puntos_signo / $puntos_signo)." (".$tot_signo."%)</td>";
            echo '</tr>';
        }
        echo '</table>';

        echo '
        <div class="col-sm-12 text-center" style="margin-top:-15px">
            <span class="label label-info">PA: Partidos </span>&nbsp;
            <span class="label label-info">SA: Signos </span>
        </div>';
                

                ?>


        </div>
        <!-- /.tab -->
        <div class="tab-pane" id="tab2-2">
            <p>
            <?php
            $rs = $this->db->query("select nombreUsuario,
                      sum(if(golesLocalApuesta=golesLocalResultado,1,0)) as totGolesLocal,
                      sum(if(golesVisitanteApuesta=golesVisitanteResultado,1,0)) as totGolesVisitante,
                      (sum(if(golesLocalApuesta=golesLocalResultado,1,0)) +
                      sum(if(golesVisitanteApuesta=golesVisitanteResultado,1,0))) as  total,
                      count(1)*2 as totalJornadas
                      from v_tor_consulta_nueva
                      where TemporadaActiva=1
                        and jornadaActiva=0
                      group by nombreUsuario
                      order by (sum(if(golesLocalApuesta=golesLocalResultado,1,0)) +
                      sum(if(golesVisitanteApuesta=golesVisitanteResultado,1,0))) desc");

            if ($rs->num_rows() == 0)
                return;   
 ?>
        
            <table class="table">
                <tbody>
                    <tr>
                        <td style="background:#fafafa">Nombre</td>
                <td style="background:#fafafa"><center>Loc.</center></td>
                <td style="background:#fafafa"><center>Vis.</center></td>
                <td style="background:#fafafa"><center>Total</center></td>
                </tr>
                <?php

                        // Ganancias
        //$this->load->model('Torpes_model');
        //$datos = $this->Torpes_model->ganancias_usuario();

        //if ($datos == null)
        //return;
                $i=0;
                foreach ($rs->result() as $fila){
                    $i++;

                ?>
                <tr>

                    <td style="background:#ffffff"><?=$fila->nombreUsuario?></td>
                    <td style="background:#ffffff" class="text-center"><?=$fila->totGolesLocal?> </td>
                    <td style="background:#ffffff" class="text-center"><?=$fila->totGolesVisitante?> </td>
                    <td style="background:#ffffff" class="text-center"><?=$fila->total?> 
                            (<?=round($fila->total * 100 / $fila->totalJornadas,0)?>%)  
                    </td>
                </tr>
                <?php } ?>
                </tbody>
            </table>

        </div>
            </p>
        </div>
        <!-- /.tab -->
    
</div>


        </div>
