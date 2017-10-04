<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="robots" content="all,follow">
        <meta name="googlebot" content="index,follow,snippet,archive">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Torpes</title>

        <meta name="keywords" content="">

        <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,500,700,800' rel='stylesheet' type='text/css'>

        <!-- Bootstrap and Font Awesome css -->
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

        <!-- Css animations  -->
        <link href="<?= base_url('') ?>/css/animate.css" rel="stylesheet">

        <!-- Theme stylesheet, if possible do not edit this stylesheet -->
        <link href="<?= base_url('') ?>/css/style.default.css" rel="stylesheet" id="theme-stylesheet">

        <!-- Custom stylesheet - for your changes -->
        <link href="<?= base_url('') ?>/css/custom.css" rel="stylesheet">

        <!-- Responsivity for older IE -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

        <!-- Favicon and apple touch icons-->
        <link rel="shortcut icon" href="<?= base_url('') ?>/img/favicon.ico" type="image/x-icon" />
        <link rel="apple-touch-icon" href="<?= base_url('') ?>/img/apple-touch-icon.png" />
        <link rel="apple-touch-icon" sizes="57x57" href="<?= base_url('') ?>/img/apple-touch-icon-57x57.png" />
        <link rel="apple-touch-icon" sizes="72x72" href="<?= base_url('') ?>/img/apple-touch-icon-72x72.png" />
        <link rel="apple-touch-icon" sizes="76x76" href="<?= base_url('') ?>/img/apple-touch-icon-76x76.png" />
        <link rel="apple-touch-icon" sizes="114x114" href="<?= base_url('') ?>/img/apple-touch-icon-114x114.png" />
        <link rel="apple-touch-icon" sizes="120x120" href="<?= base_url('') ?>/img/apple-touch-icon-120x120.png" />
        <link rel="apple-touch-icon" sizes="144x144" href="<?= base_url('') ?>/img/apple-touch-icon-144x144.png" />
        <link rel="apple-touch-icon" sizes="152x152" href="<?= base_url('') ?>/img/apple-touch-icon-152x152.png" />
    </head>

    <body style="background:#fcfcfc">


        <div id="all">
            <header>
                <div class="navbar-affixed-top" data-spy="affix" data-offset-top="200" >

                    <div class="navbar navbar-default yamm" role="navigation" id="navbar" style="background:#38A7BB">

                        <div class="container">
                            <div class="navbar-header">

                                <a class="navbar-brand home" href="<?=base_url()?>torpes/inicio">
                                    <!--<img src="img/titulo.gif" alt="Porra Los Torpes" class="hidden-xs hidden-sm">
                                    <img src="img/titulo-small.gif" alt="Porra Los Torpes" class="visible-xs visible-sm"><span class="sr-only">Universal - go to homepage</span>
                                    -->
                                    <h3 style="color:white;margin-top:10px;margin-bottom: 0px">Porra Los Torpes</h3>
                                    <h5 style="color:white;margin-top:0px"><?=$this->session->userdata('tor_descripcion')?></h5>
                                </a>
                                 
                                <div class="navbar-buttons">
                                    <button type="button" class="navbar-toggle btn-template-main" data-toggle="collapse" data-target="#navigation" style="color:white">
                                        <span class="sr-only">Toggle navigation</span>
                                        <i class="fa fa-align-justify"></i>
                                    </button>
                                </div>
                            </div>
                            <!--/.navbar-header -->

                            <div class="navbar-collapse collapse" id="navigation">
                                
                                <?php 
                                if ($this->session->userdata('tor_rol') == 1) { ?>
                                <ul class="nav navbar-nav navbar-right">
                                    <li class="dropdown ">
                                        <a href="javascript: void(0)" class="dropdown-toggle" data-toggle="dropdown">Administrar<b class="caret"></b></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?=  base_url('torpes/admin/temporadas')?>">Temporadas</a></li>
                                            <li><a href="<?=  base_url('torpes/admin/usuarios')?>">Usuarios</a></li>
                                            <li><a href="<?=  base_url('torpes/admin/equipos')?>">Equipos</a></li>
                                            <li><a href="<?=  base_url('torpes/admin/jornada')?>">Partidos</a></li>
                                            <li><a href="<?=  base_url('torpes/admin/potras/listado')?>">Potras</a></li>
                                            <li><a href="<?=  base_url('torpes/admin/apuestas/listado')?>">Apuestas</a></li>
                                            <li><a href="#"></a>
                                            </li>
                                        </ul>
                                    </li>
                                    <!-- ========== FULL WIDTH MEGAMENU END ================== -->


                                </ul>
                                <?php }?>
                                
                                <ul class="nav navbar-nav navbar-right">
                                    <li class="dropdown ">
                                        <a href="javascript: void(0)" class="dropdown-toggle" data-toggle="dropdown">Porra<b class="caret"></b></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?=  base_url('torpes/inicio')?>">Jornada Actual</a></li>
                                            <li><a href="<?=  base_url('torpes/jornada/clasificacion')?>">Clasificacion por jornada</a></li>
                                            <li><a href="<?=  base_url('torpes/jornada/clasificacion_usuario')?>">Clasificacion por usuario</a></li>
                                            <li><a href="<?=  base_url('torpes/jornada/clasificacion_general')?>">Clasificacion general</a></li>
                                            <li><a href="<?=  base_url('torpes/jornada/clasificacion_acumulada')?>">Clasificacion acumulada</a></li>
                                            <li><a href="<?=  base_url('torpes/jornada/ganancias')?>">Ganancias por jornada</a></li>
                                            <li><a href="<?=  base_url('torpes/estadisticas')?>">Estadisticas</a></li>
                                            
                                        </ul>
                                    </li>
                                    <li class="dropdown ">
                                        <a href="<?=site_url()?>/torpes/salir" class="dropdown-toggle" >Salir<b class="caret"></b></a>
                                    </li>
                                    <!-- ========== FULL WIDTH MEGAMENU END ================== -->


                                </ul>

                            </div>
                            <!--/.nav-collapse -->



                            <div class="collapse clearfix" id="search">

                                <form class="navbar-form" role="search">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search">
                                        <span class="input-group-btn">

                                            <button type="submit" class="btn btn-template-main"><i class="fa fa-search"></i></button>

                                        </span>
                                    </div>
                                </form>

                            </div>
                            <!--/.nav-collapse -->

                        </div>


                    </div>
                    <!-- /#navbar -->

                </div>

            </header>

            <div id="content" style="background:#fcfcfc">
                <div class="container" style="background:white">

                    
