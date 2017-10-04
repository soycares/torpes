        <div class="panel panel-default sidebar-menu">
            <div class="panel-heading">
                <h3 class="panel-title">Ganancias</h3>
            </div>
            <?php 
            $this->load->model('Torpes_model');
            $datos = $this->Torpes_model->ganancias_usuario();

            if ($this->session->userdata('tor_idTemporada') < 5) { 
            ?>
            <table class="table">
                <tbody>
                    <tr>
                        <td style="background:#fafafa"><center>Pos.</center></td>
                <td style="background:#fafafa"><center>Nombre</center></td>
                <td style="background:#fafafa"><center>€</center></td>

                </tr>
                <?php
                
                        // Ganancias
        
                if ($datos == null)
                     return;
                $i=0;
                foreach ($datos['ganancia_usr'] as $usuario => $dinero){
                    $i++;

                ?>
                <tr>
                    <td style="background:#ffffff" class="text-center"><?=$i?></td>
                    <td style="background:#ffffff"><?=$usuario?></td>
                    <td style="background:#ffffff" class="text-center"><?=$dinero?></td>

                </tr>
                <?php } ?>
                </tbody>
            </table>
                <?php } else { ?>
            <table class="table">
                <tbody>
                    <tr>
                        <td style="background:#fafafa"><center>Pos.</center></td>
                        <td style="background:#fafafa"><center>Nombre</center></td>
                        <td style="background:#fafafa"><center>€</center></td>

                     </tr>
                <?php
                
        // Ganancias
        if ($datos == null)
        return;
        $ganancias_usr_nuevo = $datos['ganancias_usr_nuevo'];
                $i=0;
                foreach ($ganancias_usr_nuevo->result() as $fila){
                    if ($fila->ganancia == 0)
                        continue;
                    $i++;
                    $info = $datos["leyenda_final"][$fila->idUsuario];

                ?>
                <tr>
                    <td style="background:#ffffff" class="text-center"><?=$i?></td>
                    <td style="background:#ffffff"><?=$fila->nombre.$info?></td>
                    <td style="background:#ffffff" class="text-center"><?=str_replace(".",",", $fila->ganancia)?></td>

                </tr>
                <?php } ?>
                </tbody>
            </table>

                <?php } ?>
        </div>
