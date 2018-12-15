<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    require APPPATH . '/libraries/REST_Controller.php';
    use Restserver\Libraries\REST_Controller;

    class Salon extends REST_Controller {

        function __construct($config = 'rest') {
            parent::__construct($config);
            $this->load->database();
            $this->load->helper(array('form', 'url'));

        }

        public function index_get()
        {
            $this->db->select('*');
            $this->db->from('salon');
            $query = $this->db->get()->result();
            $this->response(array('status' => 'sukses','result'=>$query, 200));
        }

        public function index_post()
        {
            $data = array(
                'nama_salon'       => $this->post('nama_salon'),
                'alamat'       => $this->post('alamat'),
                'koordinat_x'            => $this->post('koordinat_x'),
                'koordinat_y'         => $this->post('koordinat_y'));
            

                //$get_wisata = $this->db->query("SELECT * FROM wisata WHERE nama_wisata = '".$data['nama_wisata']."' ")->result();


                if(!empty($_FILES)){
                    $config['upload_path']   = './image';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['max_size']      = 80000;
                    $config['max_width']     = 4400;
                    $config['max_height']    = 3320;
                    $this->load->library('upload',$config);
                    if($this->upload->do_upload('photo')){
                        //$upload_data = $this->upload->data('file_name');


                        $data['photo'] = $this->upload->data('file_name');
                        $insert_image = "upload image success";
                    }else{
                        $insert_image = "upload image gagal";
                    }

                }else{
                    $data['photo'] = "";
                }



                $insert = $this->db->insert('salon', $data);
                if ($insert) {
                    $this->response(array('result' => $data, '' => $this->upload->display_errors()));
                } else {
                    $this->response(array('status' => 'fail', 502));
            }
        }


    }

    /* End of file Wisata.php */

?>