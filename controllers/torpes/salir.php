<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Salir extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index() {

        $this->session->unset_userdata('tor_usuario');
        $this->session->unset_userdata('tor_idUsuario');
        $this->session->unset_userdata('idTemporada');
        $this->session->unset_userdata('tor_imp_tramo_1');
        $this->session->unset_userdata('tor_imp_tramo_2');
        $this->session->unset_userdata('tor_imp_tramo_3');
        $this->session->unset_userdata('tor_imp_tramo_4');
        
        $this->session->unset_userdata('tor_imp_jornada_1');
        $this->session->unset_userdata('tor_imp_jornada_2');
        $this->session->unset_userdata('tor_imp_jornada_3');
        $this->session->unset_userdata('tor_imp_jornada_4');
        
        $this->session->unset_userdata('tor_imp_general_1');
        $this->session->unset_userdata('tor_imp_general_2');
        $this->session->unset_userdata('tor_imp_general_3');
        $this->session->unset_userdata('tor_imp_general_4');
        $this->session->unset_userdata('tor_numJornadas');
        $this->session->unset_userdata('tor_puntos_dobles_tramo');

        
        $this->session->unset_userdata('tor_num_tramos_jornadas');
        $this->session->unset_userdata('tor_imp_maxima_puntuacion');

        $this->session->unset_userdata('tor_puntos_temporada');

        redirect("/torpes/inicio");
        
        
    }

 }
