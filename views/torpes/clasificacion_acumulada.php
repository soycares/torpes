<?php $this->load->view('torpes/cabecera') ;
        
        $cad = "";
        $ykeys="";
        $i=0;
        $arr = array();
        foreach($tabla as $key => $value)
        {
          
            if ($cad != "")
                $cad.=",";
            $cad.="'".$key."'";
            if ($ykeys == "")
                $ykeys="'j".$i."'";
            else
                $ykeys.=", 'j".$i."'";
            
            $ii=0;
            foreach ($value as $jor => $datos)
            {
                ++$ii;
                $arr[$jor][$key] = $datos["puntosAcumulados"];
            }
            ++$i;
        }
        
        
        
        foreach($arr as $jor => $usr)
        {
            $cad2 ="";
            $j=0;
            $cad2.="x:'".$jor."' ";
            foreach ($usr as $nombre)
            {
                $cad2.=" j".$j.":'".$nombre."' ";
                $j++;
            }
            
        }
                
        ?>
  
<div class="row">
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
 <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
 <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
 
  <script>
 
  $( document ).ready(function() {

          $("#idJornada").change(function() {
 
  $(location).attr('href','<?=site_url('torpes/jornada/clasificacion_acumulada/')?>/'+$('#idJornada').val()); 
 
      
       
       
});


Morris.Line({
  element: 'myfirstchart',
  data: [
      <?php
              foreach($arr as $jor => $usr)
        {
            $cad2 ="";
            $j=0;
            $cad2.="{x:'".$jor."', ";
            foreach ($usr as $nombre)
            {
                if ($j>0)
                    $cad2.=", ";
                $cad2.=" j".$j.":'".$nombre."' ";
                $j++;
            }
            $cad2.="},";
            echo $cad2."\n";
        }
      ?>
  ],
  xkey: 'x',
  ykeys: [<?=$ykeys?>],
  
  labels: [<?=$cad?>],
  parseTime: false,
   behaveLikeLine: true,

}).on('click', function(i, row){
  console.log(i, row);
});

  });
 
  </script>
  
    <div class="col-md-9" id="blog-post">
        <div class="row">
            <div class="heading" style="text-align: center">
                    <h2>Clasificacion Acumulada</h2>
                </div>
            
        <?php
        echo "<h4>Jornadas ".form_dropdown('idJornada', $jornadas,'', 'id="idJornada"') . '</h4><br><br>';
        
        echo "<table class='table'>";
        echo "<tr style='background:#fafafa'>";
        echo "<td><strong><center>Usuario</center></strong></td>";
         $i=1;
         
        foreach($jornadas as $key => $value)
        {
            echo "<td colspan='2'><strong><center>$value</center></strong></td>";
              if ($i > 6)
                break;
            ++$i;
        }
        echo "</tr>";
        $i=1;
        foreach($tabla as $key => $value)
        {
            echo "<tr>";
            echo "<td>$key</td>";
            
            
            foreach ($value as $key1 => $value1)
            {
                echo "<td><center>".$value1['puntosTotal']."</center></td>";
            echo "<td><center><font color='#286E96'><b>".$value1['puntosAcumulados']."</b></font></center></td>";
             if ($i > 6)
                break;
            ++$i;
            }
            echo "</tr>";
           $i=1;
            
        }
        echo "</table>";
        
        
        
            // Ver partidos en juego o finalizados de la jornada
            if (false && $partidos_resumen->num_rows() > 0) {
                ?>
                <div class="heading" style="text-align: center">
                    <h2>Clasificacion Acumulada</h2>
                </div>
            <?php
                echo "<h4>Usuario ".form_dropdown('idUsuario', $listaUsuarios, $idUsuario,'id="idUsuario"') . '</h4><br><br>';
            ?>
            <table class="table">
                <tbody>
                <?php
                    echo "<tr>";
                    echo '<td style="background:#fafafa"><b>Jornada</b></td>';
                    
                    
                    echo '<td style="background:#fafafa"><b>PR</b></td>';
                    echo '<td style="background:#fafafa"><b>PS</b></td>';
                    echo '<td style="background:#fafafa"><b>PG</b></td>';
                    echo '<td style="background:#fafafa"><b>PN</b></td>';
                    echo '<td style="background:#fafafa"><b>Total</b></td>';
                    echo '<td style="background:#fafafa"><b>Acumulado</b></td>';
                    
                    echo "</tr>";
                    
                foreach ($partidos_resumen->result() as $fila) {
                    echo "<tr>";
                    echo '<td>'.$fila->numJornada."</td>";
                    
                    echo '<td>'.$fila->puntos_res."</td>";
                    echo '<td>'.$fila->puntos_signo."</td>";
                    echo '<td>'.$fila->puntos_goles."</td>";
                    echo '<td>'.$fila->puntos_resta."</td>";
                    echo '<td style="color:#C91818"><strong><center>'.$fila->puntos_total."</center></strong></td>";
                    echo '<td style="color:#C91818"><strong><center>'.$fila->puntos_acumulados."</center></strong></td>";
                    
                    
                    echo "</tr>";
                }
                ?>
                </tbody></table>
                <?php
            }
            ?>
                



            <div class="col-sm-12 text-center">
                &nbsp;

            </div>
        </div>
        
        
        <div class="row">
            
        </div>
      
    </div>
    <div class="col-md-3" id="blog-post">
        <br>
        <?php $this->load->view('torpes/clasificacion_peq_jor'); ?>
        <?php $this->load->view('torpes/clasificacion_peq'); ?>
    </div>
</div>


 <div class="col-sm-12">
  <div id="myfirstchart" ></div>   
     
 </div>

<?php $this->load->view('torpes/pie'); ?>