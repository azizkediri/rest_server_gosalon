<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
 
require APPPATH . '/libraries/REST_Controller.php'; 
use Restserver\Libraries\REST_Controller; 
 
class Login extends REST_Controller { 
 
    function __construct($config = 'rest') {         
    	parent::__construct($config);
    	$this->load->database();
    } 
 
    //Menampilkan data Kendaraan     
    function index_post() {
        $this->db->select('*');
        $this->db->from('customer');
        $this->db->where('nama_cust',$this->input->post('nama_cust'));
        $this->db->where('password',$this->input->post('password'));
        $query=$this->db->get();
        $row = $query->first_row();

        if ($query->num_rows()==1) {
            return $this->response(array('status' => 'Berhasil','result'=>$row));
        }else{
            return $this->response(array('status' => 'fail', 502));
        }
           
    }

}?>