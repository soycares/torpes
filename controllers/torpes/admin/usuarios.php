<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller {

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
            
        }
        
        
        //$datos=array();
        $rs= $this->db->query("SELECT * from tor_usuarios");

        $datos = array('usuarios' => $rs);
        
        $this->load->view('torpes/admin/usuarios',$datos);
        //$this->session->unset_userdata('tor_usuario');
    }
    
    function editar($id)
    {
        if (!$this->session->userdata('tor_usuario')) {
            //echo $this->input->post('usuario');

            $datos = array();
            $this->load->view('torpes/login', $datos);
            return;
            
        }
        $error="";
        
        //$datos=array();
        $rs= $this->db->query("SELECT * from tor_usuarios where idUsuario=".$id);
        $fila = $rs->row();
        
        $datos = array('nombre' => $fila->nombre,
            'password' => $fila->password,
            'email' => $fila->email,
            'rol' => $fila->rol,
            'usuario' => $fila->usuario,
            'activo' => $fila->activo,
            'error' => $error,
            'idUsuario' => $id
                
            );
        
        $this->load->view('torpes/admin/usuarios_modificar',$datos);
    }
    
    function grabar()
    {
        
        $datos= array(
            'nombre' => $this->input->post("nombre"),
            'usuario' => $this->input->post("usuario"),
            'password' => $this->input->post("password"),
            'email' => $this->input->post("email"),
            'activo' => $this->input->post("activo"),
            'rol' => $this->input->post("rol")
        );

       
        if ($this->input->post("idUsuario") == "") {
            // NUEVO
            $this->db->insert('tor_usuarios', $datos);
            //$sql = $this->db->set($datos)->get_compiled_insert('tor_temporadas');
            //echo $sql;

        }
        else {
            // EDITAR
            $this->db->where('idUsuario', $this->input->post('idUsuario'));
            $this->db->update('tor_usuarios',$datos);
//            $sql = $this->db->set($datos)->get_compiled_update('tor_temporadas');
//            echo $sql;

        }
        
       redirect('torpes/admin/usuarios');
      
    }
    
    
    function nuevo()
    {
        if (!$this->session->userdata('tor_usuario')) {
            //echo $this->input->post('usuario');

            $datos = array();
            $this->load->view('torpes/login', $datos);
            return;
            
        }
        $error="";

        $datos = array('error' => $error);
        
        $this->load->view('torpes/admin/usuarios_nuevo',$datos);
    }
}
