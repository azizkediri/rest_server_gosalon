<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller; 

class Pegawai extends REST_Controller {

		function __construct($config = 'rest') {         
    	parent::__construct($config);
    	$this->load->database();
    } 
	//$this->response(array("status"=>"success","result" => $get_pegawai));
	//$this->response(array("status"=>"success"));
	function index_get(){
		$get_pegawai = $this->db->query("SELECT nama_peg, no_hp, alamat, photo, password, level FROM pegawai")->result();
		$this->response(array("status"=>"success","result" => $get_pegawai));
	}
	function index_post() {
		$action = $this->post('action');
		$data_pegawai = array(
			'nama_peg' => $this->post('nama_peg'),
			'alamat' => $this->post('alamat'),
			'no_hp' => $this->post('no_hp'),
			'password' => $this->post('password'),
			'photo' => $this->post('photo')
	);
	if ($action==='post')
		{	
			$this->insertPegawai($data_pegawai);
		}else if ($action==='put'){
			$this->updatePegawai($data_pegawai);
		}else if ($action==='delete'){ 
			$this->deletePegawai($data_pegawai);
		}else{
			$this->response(array("status"=>"failed","message" => "action harus diisi"));
		}
	}
	function insertPegawai($data_pegawai){
		//function upload image
		$uploaddir = str_replace("application/", "", APPPATH).'upload/';
		if(!file_exists($uploaddir) && !is_dir($uploaddir)) {
			echo mkdir($uploaddir, 0750, true);
		}
		if (!empty($_FILES)){
			$path = $_FILES['photo']['name'];
			$ext = pathinfo($path, PATHINFO_EXTENSION);
			// $user_img = time() . rand() . '.' . $ext;
			$user_img = $data_pegawai['nama_peg']. '.' . "png";
			$uploadfile = $uploaddir . $user_img;
			$data_pegawai['photo'] = "upload/".$user_img;
		}else{
			$data_pegawai['photo']="";
		}
		//////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////
		//cek validasi
		if (empty($data_pegawai['nama_peg'])){
			$this->response(array('status' => "failed", "message"=>"nama_peg harus diisi"));
		}else if (empty($data_pegawai['alamat'])){
			$this->response(array('status' => "failed", "message"=>"alamat harus diisi"));
		}else if (empty($data_pegawai['no_hp'])){
			$this->response(array('status' => "failed", "message"=>"no_hp harus diisi"));
		}
		else{
			$get_pegawai_baseid = $this->db->query("SELECT * FROM pegawai as p WHERE p.id_peg='".$data_pegawai['_pegawai']."'")->result();
			if(empty($get_pegawai_baseid)){
				$insert= $this->db->insert('pegawai',$data_pegawai);
			if (!empty($_FILES)){
				if ($_FILES["photo"]["name"]) {
					if(move_uploaded_file($_FILES["photo"]["tmp_name"],$uploadfile)){
						$insert_image = "success";
					} else{
						$insert_image = "failed";
					}
					}else{
						$insert_image = "Image Tidak ada Masukan";
					}
					$data_pegawai['photo'] = base_url()."upload/".$user_img;
				}else{
						$data_pegawai['photo'] = "";
					}
					if ($insert){
						$this->response(array('status'=>'success','result' =>
						array($data_pegawai),"message"=>$insert));
					}
			}else{
						$this->response(array('status' => "failed", "message"=>"id_peg
					sudah ada"));
			}
		}
	}
	function updatePegawai($data_pegawai){
	//function upload image
		$uploaddir = str_replace("application/", "", APPPATH).'upload/';
		if(!file_exists($uploaddir) && !is_dir($uploaddir)) {
			echo mkdir($uploaddir, 0750, true);
		}
		if(!empty($_FILES)){
			$path = $_FILES['photo']['name'];
			// $ext = pathinfo($path, PATHINFO_EXTENSION);
			//$user_img = time() . rand() . '.' . $ext;
			$user_img = $data_pegawai['id_peg'].'.' ."png";
			$uploadfile = $uploaddir . $user_img;
			$data_pegawai['photo'] = "upload/".$user_img;
		}
		//$this->response(array(base_url()."upload/".$user_img));
		//////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////
		//cek validasi
		if (empty($data_pegawai['id_peg'])){
			$this->response(array('status' => "failed", "message"=>"Id pegawai harus diisi"));
		}else if (empty($data_pegawai['nama_peg'])){
			$this->response(array('status' => "failed", "message"=>"nama_peg harus diisi"));
		}else if (empty($data_pegawai['alamat'])){
			$this->response(array('status' => "failed", "message"=>"alamat harus diisi"));
		}else if (empty($data_pegawai['no_hp'])){
			$this->response(array('status' => "failed", "message"=>"no_hp harus	diisi"));
		}else{
			$get_pegawai_baseid = $this->db->query("SELECT * FROM pegawai as p WHERE p.id_peg='".$data_pegawai['id_peg']."'")->result();
			if(empty($get_pegawai_baseid)){
				$this->response(array('status' => "failed", "message"=>"id_peg Tidak ada dalam database"));
			}else{
				//$this->response(unlink($uploadfile));
				//cek apakah image
				if (!empty($_FILES["photo"]["name"])) {
					if(move_uploaded_file($_FILES["photo"]["tmp_name"],$uploadfile)){
						$insert_image = "success";
					}else{
						$insert_image = "failed";
					}
				}else{
					$insert_image = "Image Tidak ada Masukan";
				}
				if ($insert_image==="success"){
					//jika photo di update eksekusi query
					$update= $this->db->query("Update pegawai Set nama_peg
					='".$data_pegawai['nama_peg']."', alamat ='".$data_pegawai['alamat']."' , no_hp
					='".$data_pegawai['no_hp']."', photo ='".$data_pegawai['photo']."' Where id_peg
					='".$data_pegawai['id_peg']."'");
					$data_pegawai['photo'] = base_url()."upload/".$user_img;
				}else{
					//jika photo di kosong atau tidak di update eksekusi query
					$update= $this->db->query("Update pegawai Set nama_peg
					='".$data_pegawai['nama_peg']."', alamat ='".$data_pegawai['alamat']."' , no_hp
					='".$data_pegawai['no_hp']."' Where id_peg ='".$data_pegawai['id_peg']."'");
					$getPhotoPath =$this->db->query("SELECT photo
					FROM pegawai Where id_peg='".$data_pegawai['id_peg']."'")->result();
					if(!empty($getPhotoPath)){
						foreach ($getPhotoPath as $row)
						{
						$user_img = $row->photo;
						$data_pegawai['photo'] =
						base_url().$user_img;
						}
					}
				}
				if ($update){
					$this->response(array('status'=>'success','result' =>
					array($data_pegawai),"message"=>$update));
				}
			}
		}
	}
	function deletePegawai($data_pegawai){
	if (empty($data_pegawai['id_peg'])){
		$this->response(array('status' => "failed", "message"=>"Id pegawai harus diisi"));
	}
	else{
		$getPhotoPath =$this->db->query("SELECT photo FROM pegawai Where
		id_peg='".$data_pegawai['id_peg']."'")->result();
		if(!empty($getPhotoPath)){
			foreach ($getPhotoPath as $row)
			{
				$path = str_replace("application/", "",	APPPATH).$row->photo;
			}
			//delete image
			unlink($path);
			$this->db->query("Delete From pegawai Where id_peg='".$data_pegawai['id_peg']."'");
			$this->response(array('status'=>'success',"message"=>"Data id = ".$data_pegawai['id_peg']." berhasil di delete "));
		} else{
				$this->response(array('status'=>'fail',"message"=>"Id pegawai tidak ada dalam database"));
			}
		}
	}
}