<?php $this->load->view('torpes/cabecera') ?>
 
<div class="row">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  <script>
 
  $( document ).ready(function() {

          $("#idJornada").change(function() {
 
  $(location).attr('href','<?=site_url('torpes/jornada/clasificacion/')?>/'+$('#idJornada').val()); 
 
      
       
       
});

          $("#idJornada2").change(function() {
 
  $(location).attr('href','<?=site_url('torpes/jornada/clasificacion/')?>/'+$('#idJornada2').val()); 
 
      
       
       
});


  });
 
  </script>
  
    <div class="col-md-9" id="blog-post">
        <div class="row">
            
                <div class="heading" style="text-align: center">
                    <h2>Ganancias por Jornada <?=$numJornada?></h2>
                </div>
            <?php
            
            

                // echo "<h4>Jornada ".form_dropdown('idJornada2', $jornadas, $numJornada,'id="idJornada2"') . '</h4><br><br>';
            ?>
            <table class="table">
                <tbody>
                <?php
                    echo "<tr>";
                    echo '<td style="background:#fafafa"><b>Usuario</b></td>';
                    echo '<td style="background:#fafafa" class="text-center"><b>€</b></td>';
                    echo "</tr>";

                foreach ($ganancia_jor as $idJornada => $fila) {
                        echo "<tr>";
                        echo '<td style="background:#fafafa" colspan="2"><b>Jornada '.$idJornada.'</b></td>';
                        echo "</tr>";
                    
                    foreach ($fila as $usuario => $ganancia)
                    {
                          echo "<tr>";
                        echo '<td>'.$usuario.'</td>';
                        echo '<td>'.$ganancia.' €</td>';
                        echo "</tr>";
                    }
                    
                }
                ?>
                </tbody></table>
                <?php
            
            ?>
                



        
        
        
    </div>  
    </div>
    <div class="col-md-3" id="blog-post">
         <br>
        <?php //$this->load->view('torpes/clasificacion_peq_jor'); ?>
        <?php $this->load->view('torpes/clasificacion_peq'); ?>
        <?php $this->load->view('torpes/ganancias_peq'); ?>
    </div>
</div>



<?php $this->load->view('torpes/pie'); ?>