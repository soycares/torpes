<?php 
    //$this->load->model('Torpes_model');       
    //$aciertos = $this->Torpes_model->aciertos_por_equipo($idTemporada);
?>
<div class="panel panel-default sidebar-menu">
            <div class="panel-heading">
                <h3 class="panel-title">Aciertos por Equipos</h3>
            </div>

<div class="tabs">
    <ul class="nav nav-tabs nav-justified">
        <li class="active"><a href="#tab3-1" data-toggle="tab" aria-expanded="true">Real Oviedo</a></li>
        <li class=""><a href="#tab3-2" data-toggle="tab" aria-expanded="false">Sporting</a></li>
        <li class=""><a href="#tab3-3" data-toggle="tab" aria-expanded="false">Barcelona</a></li>
        <li class=""><a href="#tab3-4" data-toggle="tab" aria-expanded="false">Real Madrid</a></li>
    </ul>
    <div class="tab-content tab-content-inverse">
        <div class="tab-pane active" id="tab3-1">

            <div class="row" style="border-bottom: 1px solid #ccc;margin-left:1px;
                                    margin-right:1px;padding-bottom:2px;padding-top:5px;background:#fafafa">
                <div class="col-sd-2 col-md-2 col-xs-2" style="padding-left:5px!important;padding-right:5px!important">Pos.</div>
                <div class="col-sd-6 col-md-6 col-xs-6" style="padding-left:5px!important;padding-right:5px!important">Nombre</div>
                <div class="col-sd-2 col-md-2 col-xs-2" style="padding-left:5px!important;padding-right:5px!important">Ac.</div>
                <div class="col-sd-2 col-md-2 col-xs-2" style="padding-left:5px!important;padding-right:5px!important">Pts.</div>
            </div>   
            <?php
             $idTemporada = $this->session->userdata('tor_idTemporada');
               
            $resumen=$this->db->query('SELECT nombreUsuario, sum(if(puntos_res>0,1,0)) as aciertos, sum(puntos_total) as total
                FROM v_tor_consulta_nueva
                WHERE idTemporada='.$idTemporada.'
                and (equipoLocal="Real Oviedo" or equipoVisitante="Real Oviedo")
                group by nombreUsuario
                order by sum(if(puntos_res>0,1,0)) desc,  sum(puntos_total) desc');

            $j=0;
            foreach ($resumen->result() as $fila){
                $j++;
                $fondo1="";
                //if ($this->session->userdata("tor_imp_tramo_1") != "" && $j==1)
                if ($j==1)
                    $fondo1=' background: rgb(218, 246, 219) ';
                //if ($this->session->userdata("tor_imp_tramo_2") != "" && $j==2)
                //    $fondo1=' background: rgb(218, 246, 219) ';
            ?>
                <div class="row" style="border-bottom: 1px solid #ccc;margin-left:1px;margin-right:1px;padding-bottom:2px;padding-top:5px;<?=$fondo1?>" >
                    <div class="col-sd-2 col-md-2 col-xs-2"><?=$j?></div>
                    <div class="col-sd-6 col-md-6 col-xs-6"><?=$fila->nombreUsuario?></div>
                    <div class="col-sd-2 col-md-2 col-xs-2"><?=$fila->aciertos?></div>
                    <div class="col-sd-2 col-md-2 col-xs-2"><?=$fila->total?></div>
                </div>
            <?php
            }
            ?>
        </div>
        <!-- /.tab -->
        <div class="tab-pane" id="tab3-2">
            <div class="row" style="border-bottom: 1px solid #ccc;margin-left:1px;
                                    margin-right:1px;padding-bottom:2px;padding-top:5px;background:#fafafa">
                <div class="col-sd-2 col-md-2 col-xs-2" style="padding-left:5px!important;padding-right:5px!important">Pos.</div>
                <div class="col-sd-6 col-md-6 col-xs-6" style="padding-left:5px!important;padding-right:5px!important">Nombre</div>
                <div class="col-sd-2 col-md-2 col-xs-2" style="padding-left:5px!important;padding-right:5px!important">Ac.</div>
                <div class="col-sd-2 col-md-2 col-xs-2" style="padding-left:5px!important;padding-right:5px!important">Pts.</div>
            </div>   
            <?php
            $resumen=$this->db->query('SELECT nombreUsuario, sum(if(puntos_res>0,1,0)) as aciertos, sum(puntos_total) as total
                FROM v_tor_consulta_nueva
                WHERE idTemporada='.$idTemporada.'
                and (equipoLocal="Sporting de Gijón" or equipoVisitante="Sporting de Gijón")
                group by nombreUsuario
                order by sum(if(puntos_res>0,1,0)) desc,  sum(puntos_total) desc');

            $j=0;
            foreach ($resumen->result() as $fila){
                $j++;
                $fondo1="";
                //if ($this->session->userdata("tor_imp_tramo_1") != "" && $j==1)
                if ($j==1)
                    $fondo1=' background: rgb(218, 246, 219) ';
                //if ($this->session->userdata("tor_imp_tramo_2") != "" && $j==2)
                //    $fondo1=' background: rgb(218, 246, 219) ';
            ?>
                <div class="row" style="border-bottom: 1px solid #ccc;margin-left:1px;margin-right:1px;padding-bottom:2px;padding-top:5px;<?=$fondo1?>" >
                    <div class="col-sd-2 col-md-2 col-xs-2"><?=$j?></div>
                    <div class="col-sd-6 col-md-6 col-xs-6"><?=$fila->nombreUsuario?></div>
                    <div class="col-sd-2 col-md-2 col-xs-2"><?=$fila->aciertos?></div>
                    <div class="col-sd-2 col-md-2 col-xs-2"><?=$fila->total?></div>
                </div>
            <?php
            }
            ?>
         </div>
        <!-- /.tab -->
    
        <div class="tab-pane" id="tab3-3">
            <div class="row" style="border-bottom: 1px solid #ccc;margin-left:1px;
                                    margin-right:1px;padding-bottom:2px;padding-top:5px;background:#fafafa">
                <div class="col-sd-2 col-md-2 col-xs-2" style="padding-left:5px!important;padding-right:5px!important">Pos.</div>
                <div class="col-sd-6 col-md-6 col-xs-6" style="padding-left:5px!important;padding-right:5px!important">Nombre</div>
                <div class="col-sd-2 col-md-2 col-xs-2" style="padding-left:5px!important;padding-right:5px!important">Ac.</div>
                <div class="col-sd-2 col-md-2 col-xs-2" style="padding-left:5px!important;padding-right:5px!important">Pts.</div>
            </div>   
            <?php
            $resumen=$this->db->query('SELECT nombreUsuario, sum(if(puntos_res>0,1,0)) as aciertos, sum(puntos_total) as total
                FROM v_tor_consulta_nueva
                WHERE idTemporada='.$idTemporada.'
                and (equipoLocal="F.C. Barcelona" or equipoVisitante="F.C. Barcelona")
                group by nombreUsuario
                order by sum(if(puntos_res>0,1,0)) desc,  sum(puntos_total) desc');

            $j=0;
            foreach ($resumen->result() as $fila){
                $j++;
                $fondo1="";
                //if ($this->session->userdata("tor_imp_tramo_1") != "" && $j==1)
                if ($j==1)
                    $fondo1=' background: rgb(218, 246, 219) ';
                //if ($this->session->userdata("tor_imp_tramo_2") != "" && $j==2)
                //    $fondo1=' background: rgb(218, 246, 219) ';
            ?>
                <div class="row" style="border-bottom: 1px solid #ccc;margin-left:1px;margin-right:1px;padding-bottom:2px;padding-top:5px;<?=$fondo1?>" >
                    <div class="col-sd-2 col-md-2 col-xs-2"><?=$j?></div>
                    <div class="col-sd-6 col-md-6 col-xs-6"><?=$fila->nombreUsuario?></div>
                    <div class="col-sd-2 col-md-2 col-xs-2"><?=$fila->aciertos?></div>
                    <div class="col-sd-2 col-md-2 col-xs-2"><?=$fila->total?></div>
                </div>
            <?php
            }
            ?>
         </div>
        <!-- /.tab -->
        <div class="tab-pane" id="tab3-4">
            <div class="row" style="border-bottom: 1px solid #ccc;margin-left:1px;
                                    margin-right:1px;padding-bottom:2px;padding-top:5px;background:#fafafa">
                <div class="col-sd-2 col-md-2 col-xs-2" style="padding-left:5px!important;padding-right:5px!important">Pos.</div>
                <div class="col-sd-6 col-md-6 col-xs-6" style="padding-left:5px!important;padding-right:5px!important">Nombre</div>
                <div class="col-sd-2 col-md-2 col-xs-2" style="padding-left:5px!important;padding-right:5px!important">Ac.</div>
                <div class="col-sd-2 col-md-2 col-xs-2" style="padding-left:5px!important;padding-right:5px!important">Pts.</div>
            </div>   
            <?php
            $resumen=$this->db->query('SELECT nombreUsuario, sum(if(puntos_res>0,1,0)) as aciertos, sum(puntos_total) as total
                FROM v_tor_consulta_nueva
                WHERE idTemporada='.$idTemporada.'
                and (equipoLocal="Real Madrid" or equipoVisitante="Real Madrid")
                group by nombreUsuario
                order by sum(if(puntos_res>0,1,0)) desc,  sum(puntos_total) desc');

            $j=0;
            foreach ($resumen->result() as $fila){
                $j++;
                $fondo1="";
                //if ($this->session->userdata("tor_imp_tramo_1") != "" && $j==1)
                if ($j==1)
                    $fondo1=' background: rgb(218, 246, 219) ';
                //if ($this->session->userdata("tor_imp_tramo_2") != "" && $j==2)
                //    $fondo1=' background: rgb(218, 246, 219) ';
            ?>
                <div class="row" style="border-bottom: 1px solid #ccc;margin-left:1px;margin-right:1px;padding-bottom:2px;padding-top:5px;<?=$fondo1?>" >
                    <div class="col-sd-2 col-md-2 col-xs-2"><?=$j?></div>
                    <div class="col-sd-6 col-md-6 col-xs-6"><?=$fila->nombreUsuario?></div>
                    <div class="col-sd-2 col-md-2 col-xs-2"><?=$fila->aciertos?></div>
                    <div class="col-sd-2 col-md-2 col-xs-2"><?=$fila->total?></div>
                </div>
            <?php
            }
            ?>
         </div>
        <!-- /.tab -->

</div>


        </div>
