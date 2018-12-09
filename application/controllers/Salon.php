<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller; 

class Salon extends REST_Controller {

		function __construct($config = 'rest') {         
    	parent::__construct($config);
    	$this->load->database();
    } 
	//$this->response(array("status"=>"success","result" => $get_salon));
	//$this->response(array("status"=>"success"));
	function index_get(){
		$get_salon = $this->db->query("SELECT nama_salon, alamat, koordinat_x, koordinat_y, photo FROM salon")->result();
		$this->response(array("status"=>"success","result" => $get_salon));
	}
	function index_post() {
		$action = $this->post('action');
		$data_salon = array(
			'nama_salon' => $this->post('nama_salon'),
			'alamat' => $this->post('alamat'),
			'koordinat_x' => $this->post('koordinat_x'),
			'koordinat_y' => $this->post('koordinat_y'),
			'photo' => $this->post('photo')
	);
	if ($action==='post')
		{	
			$this->insertSalon($data_salon);
		}else if ($action==='put'){
			$this->updateSalon($data_salon);
		}else if ($action==='delete'){ 
			$this->deleteSalon($data_salon);
		}else{
			$this->response(array("status"=>"failed","message" => "action harus diisi"));
		}
	}
	function insertSalon($data_salon){
		//function upload image
		$uploaddir = str_replace("application/", "", APPPATH).'upload/salon';
		if(!file_exists($uploaddir) && !is_dir($uploaddir)) {
			echo mkdir($uploaddir, 0750, true);
		}
		if (!empty($_FILES)){
			$path = $_FILES['photo']['name'];
			$ext = pathinfo($path, PATHINFO_EXTENSION);
			// $salon_img = time() . rand() . '.' . $ext;
			$salon_img = $data_salon['nama_salon']. '.' . "png";
			$uploadfile = $uploaddir . $salon_img;
			$data_salon['photo'] = "upload/salon".$salon_img;
		}else{
			$data_salon['photo']="";
		}
		//////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////
		//cek validasi
		if (empty($data_salon['nama_salon'])){
			$this->response(array('status' => "failed", "message"=>"nama_salon harus diisi"));
		}else if (empty($data_salon['alamat'])){
			$this->response(array('status' => "failed", "message"=>"alamat harus diisi"));
		}
		else if (empty($data_salon['jenis_kelamin'])){
			$this->response(array('status' => "failed", "message"=>"jenis_kelamin harus diisi"));
		}
		else{
			$get_salon_baseid = $this->db->query("SELECT * FROM salon as s WHERE s.id_salon='".$data_salon['_salon']."'")->result();
			if(empty($get_salon_baseid)){
				$insert= $this->db->insert('salon',$data_salon);
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
					$data_salon['photo'] = base_url()."upload/salon".$salon_img;
				}else{
						$data_salon['photo'] = "";
					}
					if ($insert){
						$this->response(array('status'=>'success','result' =>
						array($data_salon),"message"=>$insert));
					}
			}else{
						$this->response(array('status' => "failed", "message"=>"id_salon
					sudah ada"));
			}
		}
	}
	function updateSalon($data_salon){
	//function upload image
		$uploaddir = str_replace("application/", "", APPPATH).'upload/salon';
		if(!file_exists($uploaddir) && !is_dir($uploaddir)) {
			echo mkdir($uploaddir, 0750, true);
		}
		if(!empty($_FILES)){
			$path = $_FILES['photo']['name'];
			// $ext = pathinfo($path, PATHINFO_EXTENSION);
			//$salon_img = time() . rand() . '.' . $ext;
			$salon_img = $data_salon['id_salon'].'.' ."png";
			$uploadfile = $uploaddir . $salon_img;
			$data_salon['photo'] = "upload/salon".$salon_img;
		}
		//$this->response(array(base_url()."upload/salon".$salon_img));
		//////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////
		//cek validasi
		if (empty($data_salon['id_salon'])){
			$this->response(array('status' => "failed", "message"=>"Id salon harus diisi"));
		}else if (empty($data_salon['nama_salon'])){
			$this->response(array('status' => "failed", "message"=>"nama_salon harus diisi"));
		}else if (empty($data_salon['alamat'])){
			$this->response(array('status' => "failed", "message"=>"alamat harus diisi"));
		}else if (empty($data_salon['jenis_kelamin'])){
			$this->response(array('status' => "failed", "message"=>"jenis_kelamin harus	diisi"));
		}else{
			$get_salon_baseid = $this->db->query("SELECT * FROM salon as s WHERE s.id_salon='".$data_salon['id_salon']."'")->result();
			if(empty($get_salon_baseid)){
				$this->response(array('status' => "failed", "message"=>"id_salon Tidak ada dalam database"));
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
					$update= $this->db->query("Update salon Set nama_salon
					='".$data_salon['nama_salon']."'
					, alamat ='".$data_salon['alamat']."' 
					, koordinat_x	='".$data_salon['koordinat_x']."' 
					, koordinat_y	='".$data_salon['koordinat_y']."'
					, photo ='".$data_salon['photo']."' 
					Where id_salon
					='".$data_salon['id_salon']."'");
					$data_salon['photo'] = base_url()."upload/salon".$salon_img;
				}else{
					//jika photo di kosong atau tidak di update eksekusi query
					$update= $this->db->query("Update salon Set nama_salon
					='".$data_salon['nama_salon']."', alamat ='".$data_salon['alamat']."' 
					, koordinat_x	='".$data_salon['koordinat_x']."'
					, koordinat_y	='".$data_salon['koordinat_y']."'
					 Where id_salon ='".$data_salon['id_salon']."'");
					$getPhotoPath =$this->db->query("SELECT photo FROM salon Where id_salon='".$data_salon['id_salon']."'")->result();
					if(!empty($getPhotoPath)){
						foreach ($getPhotoPath as $row)
						{
						$salon_img = $row->photo;
						$data_salon['photo'] =
						base_url().$salon_img;
						}
					}
				}
				if ($update){
					$this->response(array('status'=>'success','result' =>
					array($data_salon),"message"=>$update));
				}
			}
		}
	}
	function deleteSalon($data_salon){
	if (empty($data_salon['id_salon'])){
		$this->response(array('status' => "failed", "message"=>"Id salon harus diisi"));
	}
	else{
		$getPhotoPath =$this->db->query("SELECT photo FROM salon Where
		id_salon='".$data_salon['id_salon']."'")->result();
		if(!empty($getPhotoPath)){
			foreach ($getPhotoPath as $row)
			{
				$path = str_replace("application/", "",	APPPATH).$row->photo;
			}
			//delete image
			unlink($path);
			$this->db->query("Delete From salon Where id_salon='".$data_salon['id_salon']."'");
			$this->response(array('status'=>'success',"message"=>"Data id = ".$data_salon['id_salon']." berhasil di delete "));
		} else{
				$this->response(array('status'=>'fail',"message"=>"Id salon tidak ada dalam database"));
			}
		}
	}
}