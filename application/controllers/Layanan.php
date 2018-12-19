<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller; 

class Layanan extends REST_Controller {

		function __construct($config = 'rest') {         
    	parent::__construct($config);
    	$this->load->database();
    } 
	//$this->response(array("status"=>"success","result" => $get_layanan));
	//$this->response(array("status"=>"success"));
	function index_get(){
		$get_layanan = $this->db->query("SELECT * FROM layanan")->result();
		$this->response(array("status"=>"success","result" => $get_layanan));
	}

	function detaillayanan_post(){
		$get_layanan = $this->db->query("SELECT * FROM `layanan` WHERE id_salon =".$this->post('id'))->result();
		$this->response(array("status"=>"success","result" => $get_layanan));
	}


	function index_post() {
		$action = $this->post('action');
		$data_layanan = array(
			'id_layanan' => $this->post('id_layanan'),
			'id_salon' => $this->post('id_salon'),
			'nama_layanan' => $this->post('nama_layanan'),
			'deskripsi' => $this->post('deskripsi'),
			'harga' => $this->post('harga'),
			'status' => $this->post('status'),
			'photo' => $this->post('photo')
		);

	if ($action==='post')
		{	
			// $this->insertLayanan($data_layanan);
		}else if ($action==='put'){
			$this->updateLayanan($data_layanan);
		}else if ($action==='delete'){ 
			$this->deleteLayanan($data_layanan);
		}else{
			$this->response(array("status"=>"failed","message" => "action harus diisi"));
		}
	}

	// function insertLayanan($data_layanan){
	// 	//function upload image
	// 	$uploaddir = str_replace("application/", "", APPPATH).'upload/layanan/';
	// 	if(!file_exists($uploaddir) && !is_dir($uploaddir)) {
	// 		echo mkdir($uploaddir, 0750, true);
	// 	}
	// 	if (!empty($_FILES)){
	// 		$path = $_FILES['photo']['name'];
	// 		$ext = pathinfo($path, PATHINFO_EXTENSION);
	// 		// $user_img = time() . rand() . '.' . $ext;
	// 		$user_img = $data_layanan['nama_layanan']. '.' . "png";
	// 		$uploadfile = $uploaddir . $user_img;
	// 		$data_layanan['photo'] = "upload/layanan/".$user_img;
	// 	}else{
	// 		$data_layanan['photo']="";
	// 	}
	// 	//////////////////////////////////////////////////////////////////
	// 	//////////////////////////////////////////////////////////////////
	// 	//cek validasi
	// 	if (empty($data_layanan['nama_layanan'])){
	// 		$this->response(array('status' => "failed", "message"=>"nama_layanan harus diisi"));
	// 	}else if (empty($data_layanan['deskripsi'])){
	// 		$this->response(array('status' => "failed", "message"=>"deskripsi harus diisi"));
	// 	}else if (empty($data_layanan['harga'])){
	// 		$this->response(array('status' => "failed", "message"=>"harga harus diisi"));
	// 	}
	// 	else{
	// 		$get_layanan_baseid = $this->db->query("SELECT * FROM layanan as l WHERE l.id_layanan='".$data_layanan['_layanan']."'")->result();
	// 		if(empty($get_layanan_baseid)){
	// 			$insert= $this->db->insert('layanan',$data_layanan);
	// 		if (!empty($_FILES)){
	// 			if ($_FILES["photo"]["name"]) {
	// 				if(move_uploaded_file($_FILES["photo"]["tmp_name"],$uploadfile)){
	// 					$insert_image = "success";
	// 				} else{
	// 					$insert_image = "failed";
	// 				}
	// 				}else{
	// 					$insert_image = "Image Tidak ada Masukan";
	// 				}
	// 				$data_layanan['photo'] = base_url()."upload/layanan/".$user_img;
	// 			}else{
	// 					$data_layanan['photo'] = "";
	// 				}
	// 				if ($insert){
	// 					$this->response(array('status'=>'success','result' =>
	// 					array($data_layanan),"message"=>$insert));
	// 				}
	// 		}else{
	// 					$this->response(array('status' => "failed", "message"=>"id_layanan
	// 				sudah ada"));
	// 		}
	// 	}
	// }


	function updateLayanan($data_layanan){
	//function upload image
		$uploaddir = str_replace("application/", "", APPPATH).'upload/layanan/';
		if(!file_exists($uploaddir) && !is_dir($uploaddir)) {
			echo mkdir($uploaddir, 0750, true);
		}
		if(!empty($_FILES)){
			$path = $_FILES['photo']['name'];
			// $ext = pathinfo($path, PATHINFO_EXTENSION);
			//$user_img = time() . rand() . '.' . $ext;
			$user_img = $data_layanan['id_layanan'].'.' ."png";
			$uploadfile = $uploaddir . $user_img;
			$data_layanan['photo'] = "upload/layanan/".$user_img;
		}
		//$this->response(array(base_url()."upload/layanan/".$user_img));
		//////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////
		//cek validasi
		if (empty($data_layanan['id_layanan'])){
			$this->response(array('status' => "failed", "message"=>"Id layanan harus diisi"));
		}else if (empty($data_layanan['nama_layanan'])){
			$this->response(array('status' => "failed", "message"=>"nama_layanan harus diisi"));
		}else if (empty($data_layanan['deskripsi'])){
			$this->response(array('status' => "failed", "message"=>"deskripsi harus diisi"));
		}else if (empty($data_layanan['harga'])){
			$this->response(array('status' => "failed", "message"=>"harga harus	diisi"));
		}else{
			$get_layanan_baseid = $this->db->query("SELECT * FROM layanan as l WHERE l.id_layanan='".$data_layanan['id_layanan']."'")->result();
			if(empty($get_layanan_baseid)){
				$this->response(array('status' => "failed", "message"=>"id_layanan Tidak ada dalam database"));
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
					$update= $this->db->query("Update layanan Set nama_layanan
					='".$data_layanan['nama_layanan']."', deskripsi ='".$data_layanan['deskripsi']."' , harga
					='".$data_layanan['harga']."', photo ='".$data_layanan['photo']."' Where id_layanan
					='".$data_layanan['id_layanan']."'");
					$data_layanan['photo'] = base_url()."upload/layanan/".$user_img;
				}else{
					//jika photo di kosong atau tidak di update eksekusi query
					$update= $this->db->query("Update layanan Set nama_layanan
					='".$data_layanan['nama_layanan']."', deskripsi ='".$data_layanan['deskripsi']."' , harga
					='".$data_layanan['harga']."' Where id_layanan ='".$data_layanan['id_layanan']."'");
					$getPhotoPath =$this->db->query("SELECT photo
					FROM layanan Where id_layanan='".$data_layanan['id_layanan']."'")->result();
					if(!empty($getPhotoPath)){
						foreach ($getPhotoPath as $row)
						{
						$user_img = $row->photo;
						$data_layanan['photo'] =
						base_url().$user_img;
						}
					}
				}
				if ($update){
					$this->response(array('status'=>'success','result' =>
					array($data_layanan),"message"=>$update));
				}
			}
		}
	}

	function deleteLayanan($data_layanan){
	if (empty($data_layanan['id_layanan'])){
		$this->response(array('status' => "failed", "message"=>"Id layanan harus diisi"));
	}
	else{
		$getPhotoPath =$this->db->query("SELECT photo FROM layanan Where
		id_layanan='".$data_layanan['id_layanan']."'")->result();
		if(!empty($getPhotoPath)){
			foreach ($getPhotoPath as $row)
			{
				$path = str_replace("application/", "",	APPPATH).$row->photo;
			}
			//delete image
			unlink($path);
			$this->db->query("Delete From layanan Where id_layanan='".$data_layanan['id_layanan']."'");
			$this->response(array('status'=>'success',"message"=>"Data id = ".$data_layanan['id_layanan']." berhasil di delete "));
		} else{
				$this->response(array('status'=>'fail',"message"=>"Id layanan tidak ada dalam database"));
			}
		}
	}


}