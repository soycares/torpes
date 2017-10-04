        <div class="panel panel-default sidebar-menu">
            <div class="panel-heading">
                <h3 class="panel-title">Máxima Puntuación</h3>
            </div>

            <table class="table">
                <tbody>
                    
                <?php
                
                        // Ganancias
        $this->load->model('Torpes_model');
        $datos = $this->Torpes_model->maxima_puntuacion();
         if ($datos == null)
              return;
                

                ?>
                <tr>
                    <td style="background:#fafafa" class="text-center" >
						<h3>
							<?=$datos["nombre"]?> <?=$datos["puntos"]?> Puntos
						</h3>
                        <?php // mostrar maxima puntuacion del usuario
                        if ($datos["puntosUsuario"] != null && $datos["idUsuario"] != $this->session->userdata('tor_idUsuario')) { ?>
                        
                                <div class="alert alert-info" role="alert">Tu máxima puntuación es de <strong><?=$datos["puntosUsuario"]?></strong> Puntos</div>
                        <?php } ?>
                        
					</td>

                </tr>
              
                </tbody>
            </table>

        </div>
