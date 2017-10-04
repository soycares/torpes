<?php $this->load->view('torpes/cabecera') ?>


<!-- test datepicker -->

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  
        <script src="<?=base_url()?>/js/bootstrap-datetimepicker.min.js"></script>
        <link href="<?= base_url('') ?>/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
                
<!--<input size="16" type="text" readonly class="form_datetime1">
     
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
    -->

<div class="row">

    <div class="col-md-9" id="blog-post">
        <div class="row">
            <div class="heading" style="text-align: center">
                <h2>Nueva Temporada</h2>
            </div>
            
            <?php
            if ($error != '')
                    echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
                    
            echo form_open('torpes/admin/temporadas/grabar', 'method="post"');
            ?>

            
            
            <div class="col-md-12 col-sm-12">
                <label for="billing_firstname">Nombre</label>
                <input id="descripcion" class="form-control required" type="text" name="descripcion" >
            </div>
            <div class="col-md-6 col-sm-6">
                <label for="billing_firstname">Temporada</label>
                <input id="numTemporada" class="form-control required" type="text" name="numTemporada" >
            </div>
            <div class="col-md-6 col-sm-6">
                <label for="billing_firstname">Temporada Activa</label>
                <select id="activa" class="form-control required" name="activa">
                    <option value="1">Si</option>
                    <option value="0"  selected >No</option>
                </select>
            </div>
            <div class="col-md-6 col-sm-6">
                <label for="billing_firstname">Nº Jornadas</label>
                <input id="numJornadas" class="form-control required" type="number" name="numJornadas"  value="0">
            </div>
            

            <div class="col-md-6 col-sm-6">
                <label for="billing_firstname">Nº Partidos</label>
                <input id="numPartidos" class="form-control required" type="number" name="numPartidos"  value="0">
            </div>
             <div class="col-md-6 col-sm-6">
                <label for="billing_firstname">€ por Jornada</label>
                <input id="imp_jornada" class="form-control required" type="number" name="imp_jornada"  value="0">
            </div>
             <div class="col-md-12 col-sm-12">
              <div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                        <h3 class="panel-title">Puntos</h3>
                    </div>
                  </div>
            </div>

            
            <div class="col-md-6 col-sm-6">
                <label for="billing_firstname">Puntos Resultado</label>
                <input id="num_puntos_res" class="form-control required" type="number" name="num_puntos_res"  value="0">
            </div>
            <div class="col-md-6 col-sm-6">
                <label for="billing_firstname">Puntos Signo</label>
                <input id="num_puntos_signo" class="form-control required" type="number" name="num_puntos_signo"  value="0">
            </div>
            <div class="col-md-6 col-sm-6">
                <label for="billing_firstname">Puntos Goles</label>
                <input id="num_puntos_goles" class="form-control required" type="number" name="num_puntos_goles"  value="0">
            </div>
            <div class="col-md-6 col-sm-6">
                <label for="billing_firstname">Puntos Resta</label>
                <input id="num_puntos_resta" class="form-control required" type="number" name="num_puntos_resta"  value="0">
            </div>
            
           
            
            <div class="col-md-12 col-sm-12">
                <label for="billing_firstname">Diferencia de Puntos</label>
                <select id="diferencia_puntos" class="form-control required" name="diferencia_puntos">
                    <option value="1" >Si </option>
                    <option value="0" >No</option>
                </select>
            </div>
            
            <div class="col-md-12 col-sm-12">
              <div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                        <h3 class="panel-title">Tramos</h3>
                    </div>
                  </div>
            </div>

            <div class="col-md-4 col-sm-4">
                <label for="billing_firstname">Nº Tramos</label>
                <input id="num_tramos" class="form-control required" type="number" name="num_tramos"  value="0">
            </div>
            <div class="col-md-4 col-sm-4">
                <label for="billing_firstname">Jornadas por Tramo</label>
                <input id="num_tramos_jornadas" class="form-control required" type="number" name="num_tramos_jornadas"  value="0">
            </div>
             <div class="col-md-4 col-sm-4">
				 
                <label for="billing_firstname">Puntos Dobles Ultima Jornada</label>
                <select id="puntos_dobles_tramo" class="form-control required" name="puntos_dobles_tramo">
                    <option value="1" >Si</option>
                    <option value="0" >No</option>
                </select>
                
                
            </div>


			<div class="col-md-12 col-sm-12">
              <div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                        <h3 class="panel-title">Máxima Puntuación</h3>
                    </div>
                  </div>
            </div>

            <div class="col-md-6 col-sm-6">
				 
                <label for="billing_firstname">Máxima Puntuación</label>
                <select id="maxima_puntuacion" class="form-control required" name="maxima_puntuacion">
                    <option value="1" >Si</option>
                    <option value="0" >No</option>
                </select>
                
                
            </div>
            <div class="col-md-6 col-sm-6">
                <label for="billing_firstname">€ Máxima Puntuación</label>
                <input id="num_tramos_jornadas" class="form-control required" type="number" name="imp_maxima_puntuacion">
            </div>


            <div class="col-md-12 col-sm-12">
              <div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                        <h3 class="panel-title">Aciertos Equipos</h3>
                    </div>
                  </div>
            </div>

            <div class="col-md-6 col-sm-6 ">
                <label for="billing_firstname">Aciertos Equipos</label>
                <select id="aciertos_equipos" class="form-control required" name="aciertos_equipos">
                    <option value="1" >Si</option>
                    <option value="0" >No</option>
                </select>
            </div>
            <div class="col-md-2 col-sm-2 ">
                <label for="billing_firstname">€ Primero</label>
                <input id="imp_aciertos_equipos_1" class="form-control required" type="number" name="imp_aciertos_equipos_1" value="0">
            </div>
            <div class="col-md-2 col-sm-2 ">
                <label for="billing_firstname">€ Segundo</label>
                <input id="imp_aciertos_equipos_2" class="form-control required" type="number" name="imp_aciertos_equipos_2" value="0">
            </div>
            <div class="col-md-2 col-sm-2 ">
                <label for="billing_firstname">€ Tercero</label>
                <input id="imp_aciertos_equipos_3" class="form-control required" type="number" name="imp_aciertos_equipos_3" value="0">
            </div>




            

            <div class="col-md-12 col-sm-12">
              <div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                        <h3 class="panel-title">Reparto General</h3>
                    </div>
                  </div>
            </div>

            <div class="col-md-3 col-sm-3">
                <label for="billing_firstname">€ Primero</label>
                <input id="imp_general_1" class="form-control required" type="number" name="imp_general_1"  value="0">
            </div>
            <div class="col-md-3 col-sm-3">
                <label for="billing_firstname">€ Segundo</label>
                <input id="imp_general_2" class="form-control required" type="number" name="imp_general_2"  value="0">
            </div>
            <div class="col-md-3 col-sm-3">
                <label for="billing_firstname">€ Tercero</label>
                <input id="imp_general_3" class="form-control required" type="number" name="imp_general_3"  value="0">
            </div>
            <div class="col-md-3 col-sm-3">
                <label for="billing_firstname">€ Cuarto</label>
                <input id="imp_general_4" class="form-control required" type="number" name="imp_general_4"  value="0">
            </div>


            <div class="col-md-12 col-sm-12">
              <div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                        <h3 class="panel-title">Reparto Tramos</h3>
                    </div>
                  </div>
            </div>

            <div class="col-md-3 col-sm-3">
                <label for="billing_firstname">€ Primero</label>
                <input id="imp_tramo_1" class="form-control required" type="number" name="imp_tramo_1"  value="0">
            </div>
            <div class="col-md-3 col-sm-3">
                <label for="billing_firstname">€ Segundo</label>
                <input id="imp_tramo_2" class="form-control required" type="number" name="imp_tramo_2"  value="0">
            </div>
            <div class="col-md-3 col-sm-3">
                <label for="billing_firstname">€ Tercero</label>
                <input id="imp_tramo_3" class="form-control required" type="number" name="imp_tramo_3"  value="0">
            </div>
            <div class="col-md-3 col-sm-3">
                <label for="billing_firstname">€ Cuarto</label>
                <input id="imp_tramo_4" class="form-control required" type="number" name="imp_tramo_4"  value="0">
            </div>


            <div class="col-md-12 col-sm-12">
              <div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                        <h3 class="panel-title">Reparto Jornada</h3>
                    </div>
                  </div>
            </div>

            <div class="col-md-3 col-sm-3">
                <label for="billing_firstname">€ Primero</label>
                <input id="imp_jornada_1" class="form-control required" type="text" name="imp_jornada_1"  value="0">
            </div>
            <div class="col-md-3 col-sm-3">
                <label for="billing_firstname">€ Segundo</label>
                <input id="imp_jornada_2" class="form-control required" type="text" name="imp_jornada_2"  value="0">
            </div>
            <div class="col-md-3 col-sm-3">
                <label for="billing_firstname">€ Tercero</label>
                <input id="imp_jornada_3" class="form-control required" type="text" name="imp_jornada_3"  value="0">
            </div>
            <div class="col-md-3 col-sm-3">
                <label for="billing_firstname">€ Cuarto</label>
                <input id="imp_jornada_4" class="form-control required" type="text" name="imp_jornada_4"  value="0">
            </div>
             <div class="col-sm-12 text-center" style="margin-top: 15px">
                <button class="btn btn-template-main" type="submit"><i class="fa fa-envelope-o"></i>Enviar</button>

            </div> 
            </form>

 </div> 
  </div> 
   </div> 

<?php $this->load->view('torpes/pie'); ?>
