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
        <link href="<?=  base_url('')?>/css/animate.css" rel="stylesheet">

        <!-- Theme stylesheet, if possible do not edit this stylesheet -->
        <link href="<?=  base_url('')?>/css/style.default.css" rel="stylesheet" id="theme-stylesheet">

        <!-- Custom stylesheet - for your changes -->
        <link href="<?=  base_url('')?>/css/custom.css" rel="stylesheet">

        <!-- Responsivity for older IE -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

        <!-- Favicon and apple touch icons-->
        <link rel="shortcut icon" href="<?=  base_url('')?>/img/favicon.ico" type="image/x-icon" />
        <link rel="apple-touch-icon" href="<?=  base_url('')?>/img/apple-touch-icon.png" />
        <link rel="apple-touch-icon" sizes="57x57" href="<?=  base_url('')?>/img/apple-touch-icon-57x57.png" />
        <link rel="apple-touch-icon" sizes="72x72" href="<?=  base_url('')?>/img/apple-touch-icon-72x72.png" />
        <link rel="apple-touch-icon" sizes="76x76" href="<?=  base_url('')?>/img/apple-touch-icon-76x76.png" />
        <link rel="apple-touch-icon" sizes="114x114" href="<?=  base_url('')?>/img/apple-touch-icon-114x114.png" />
        <link rel="apple-touch-icon" sizes="120x120" href="<?=  base_url('')?>/img/apple-touch-icon-120x120.png" />
        <link rel="apple-touch-icon" sizes="144x144" href="<?=  base_url('')?>/img/apple-touch-icon-144x144.png" />
        <link rel="apple-touch-icon" sizes="152x152" href="<?=  base_url('')?>/img/apple-touch-icon-152x152.png" />
    </head>

    <body style="background:#fcfcfc">


        <div id="all">
           
            <!-- *** LOGIN MODAL ***
    _________________________________________________________ -->

          

            <!-- *** LOGIN MODAL END *** -->



            <div id="content">
                <div id="contact" class="container">
                    
                    <section>

                    <div class="row text-center">

                        <div class="col-md-12">
                            <div class="heading">
                                <h2>CAMBIO DE CONTRASEÑA</h2>
                            </div>
                        </div>

                        <div class="col-md-8 col-md-offset-2">
                            <?php
                            
                            ?>
                            <?=form_open('torpes/inicio/cambio','method="post"')?>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input type="password" placeholder="Nueva contraseña" id="password" name="password" class="form-control">
                                        </div>
                                    </div>
                                  

                                    <div class="col-sm-12 text-center">
                                        <button class="btn btn-template-main" type="submit"><i class="fa fa-envelope-o"></i>Enviar</button>

                                    </div>
                                </div>
                                <!-- /.row -->
                            </form>



                        </div>
                    </div>


                </section>
                    
                </div>

                    </div>


                    <div id="copyright">
                        <div class="container">
                            <div class="col-md-12">
                                <p class="pull-left">&copy; 2015. Peña Los Torpes</p>
                                <p class="pull-right">By cares                                </p>

                            </div>
                        </div>
                    </div>
                    <!-- /#copyright -->

                    <!-- *** COPYRIGHT END *** -->



                </div>
                <!-- /#all -->


                <!-- #### JAVASCRIPT FILES ### -->

                <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
                <script>
                    window.jQuery || document.write('<script src="<?=  base_url('')?>/js/jquery-1.11.0.min.js"><\/script>')
                </script>
                <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

                <script src="<?=  base_url('')?>/js/jquery.cookie.js"></script>
                <script src="<?=  base_url('')?>/js/waypoints.min.js"></script>
                <script src="<?=  base_url('')?>/js/jquery.counterup.min.js"></script>
                <script src="<?=  base_url('')?>/js/jquery.parallax-1.1.3.js"></script>
                <script src="<?=  base_url('')?>/js/front.js"></script>





                </body>

                </html>