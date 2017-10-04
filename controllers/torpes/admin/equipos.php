<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Equipos extends CI_Controller {

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

        if (!$this->session->userdata('tor_usuario')) {
            //echo $this->input->post('usuario');

            $datos = array();
            $this->load->view('torpes/login', $datos);
            return;
            /*
              $rs = $this->db->get('ov_config');
              if ($rs->num_rows() == 1) {
              $conf = $rs->row();
              //				echo "<br>-> temp=".$conf->idTemporada;
              }
              //		else
              //			echo "<br>Mas de un registro, ERROR";
              $this->session->set_userdata('idTemporada', $conf->idTemporada);
              $this->session->set_userdata('conf', $conf);
             */
        }
        
        
        //$datos=array();
        $rs= $this->db->query("SELECT * from tor_equipos order by nombre");

        $datos = array('equipos' => $rs);
        
        $this->load->view('torpes/admin/equipos',$datos);
        //$this->session->unset_userdata('tor_usuario');
    }
    
    function grabar()
    {
        //var_dump($_POST);
        $aux=array();
        foreach ($_POST as $dato => $valor)
        {
          //  echo "$dato - $valor<br>";
            $a = explode("_",$dato);
            $campo = $a[0];
            $id_jornada = $a[1];
            $aux[$id_jornada][$campo] = $valor;
                
        }
        //var_dump($aux);
        
        foreach ($aux as $idJornada => $datos)
        {
            
            
            $data = array('idJornada' => $idJornada,
                'idUsuario' => $this->session->userdata("tor_idUsuario"));
            
            foreach ($datos as $campo => $valor)
            {
                $data[$campo] = $valor;
                
            }
            var_dump($data);
                $rs=$this->db->query("select * from tor_apuestas where idJornada=".$idJornada." and idUsuario=".$this->session->userdata("tor_idUsuario"));
                
                if ($rs->num_rows() == 0)
                {
                    echo "NO HAY<br>";
                    $this->db->insert('tor_apuestas',$data);
                    
                    echo $this->db->last_query();
                
                    }
                    else
                    {
                        $this->db->where('idUsuario',$this->session->userdata("tor_idUsuario"));
                        $this->db->where('idJornada',$idJornada);
                        $this->db->update('tor_apuestas',$data);
                        echo "HAY<br>";
                    }
                    
            

            
        }
    }
}
