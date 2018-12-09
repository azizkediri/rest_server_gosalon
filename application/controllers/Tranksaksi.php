<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller; 

class Tranksaksi extends REST_Controller {

		function __construct($config = 'rest') {         
    	parent::__construct($config);
    	$this->load->database();
    } 
	//$this->response(array("status"=>"success","result" => $get_tranksaksi));
	//$this->response(array("status"=>"success"));
	function index_get(){
		$get_tranksaksi = $this->db->query("SELECT id_cust, no_antrian, nama_salon, nama_layanan, total, tanggal FROM tranksaksi")->result();
		$this->response(array("status"=>"success","result" => $get_tranksaksi));
	}
	function index_post() {
		$action = $this->post('action');
		$data_tranksaksi = array(
			'id_cust' => $this->post('id_cust'),
			'no_antrian' => $this->post('no_antrian'),
			'nama_salon' => $this->post('nama_salon'),
			'nama_layanan' => $this->post('nama_layanan'),
			'total' => $this->post('total'),
			'tanggal' => $this->post('tanggal')
	);
	if ($action==='post')
		{	
			$this->insertTranksaksi($data_tranksaksi);
		}else if ($action==='put'){
			$this->updateTranksaksi($data_tranksaksi);
		}else if ($action==='delete'){ 
			$this->deleteTranksaksi($data_tranksaksi);
		}else{
			$this->response(array("status"=>"failed","message" => "action harus diisi"));
		}
	}
	function insertTranksaksi($data_tranksaksi){
		
		
		//////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////
		//cek validasi
		if (empty($data_tranksaksi['id_transaksi'])){
			$this->response(array('status' => "failed", "message"=>"id_cust harus diisi"));
		}else if (empty($data_tranksaksi['no_antrian'])){
			$this->response(array('status' => "failed", "message"=>"no_antrian harus diisi"));
		}else if (empty($data_tranksaksi['nama_salon'])){
			$this->response(array('status' => "failed", "message"=>"nama_salon harus diisi"));
		}
		else{
			$get_tranksaksi_baseid = $this->db->query("SELECT * FROM tranksaksi as p WHERE p.id_tranksaksi='".$data_tranksaksi['_tranksaksi']."'")->result();
			if(empty($get_tranksaksi_baseid)){
				$insert= $this->db->insert('tranksaksi',$data_tranksaksi);
			// if (!empty($_FILES)){
			// 	if ($_FILES["photo"]["name"]) {
			// 		if(move_uploaded_file($_FILES["photo"]["tmp_name"],$uploadfile)){
			// 			$insert_image = "success";
			// 		} else{
			// 			$insert_image = "failed";
			// 		}
			// 		}else{
			// 			$insert_image = "Image Tidak ada Masukan";
			// 		}
			// 		$data_tranksaksi['photo'] = base_url()."upload/".$user_img;
			// 	}else{
			// 			$data_tranksaksi['photo'] = "";
					//}
					if ($insert){
						$this->response(array('status'=>'success','result' =>
						array($data_tranksaksi),"message"=>$insert));
					}
			}else{
						$this->response(array('status' => "failed", "message"=>"Id_ Tranksaksi sudah ada"));
			}
		}
	}
	function updateTranksaksi($data_tranksaksi){
	//function upload image
		// $uploaddir = str_replace("application/", "", APPPATH).'upload/';
		// if(!file_exists($uploaddir) && !is_dir($uploaddir)) {
		// 	echo mkdir($uploaddir, 0750, true);
		// }
		// if(!empty($_FILES)){
		// 	$path = $_FILES['photo']['name'];
		// 	// $ext = pathinfo($path, PATHINFO_EXTENSION);
		// 	//$user_img = time() . rand() . '.' . $ext;
		// 	$user_img = $data_tranksaksi['id_transaksi'].'.' ."png";
		// 	$uploadfile = $uploaddir . $user_img;
		// 	$data_tranksaksi['photo'] = "upload/".$user_img;
		// }
		//$this->response(array(base_url()."upload/".$user_img));
		//////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////
		//cek validasi
		if (empty($data_tranksaksi['id_transaksi'])){
			$this->response(array('status' => "failed", "message"=>"Id tranksaksi harus diisi"));
		}else if (empty($data_tranksaksi['id_transaksi'])){
			$this->response(array('status' => "failed", "message"=>"id_cust harus diisi"));
		}else if (empty($data_tranksaksi['no_antrian'])){
			$this->response(array('status' => "failed", "message"=>"no_antrian harus diisi"));
		}else if (empty($data_tranksaksi['nama_salon'])){
			$this->response(array('status' => "failed", "message"=>"nama_salon harus	diisi"));
		}else{
			$get_tranksaksi_baseid = $this->db->query("SELECT * FROM tranksaksi as p WHERE p.id_tranksaksi='".$data_tranksaksi['id_transaksi']."'")->result();
			if(empty($get_tranksaksi_baseid)){
				$this->response(array('status' => "failed", "message"=>"Id_cust Tidak ada dalam database"));
			}else{
				//$this->response(unlink($uploadfile));
				//cek apakah image
				// if (!empty($_FILES["photo"]["name"])) {
				// 	if(move_uploaded_file($_FILES["photo"]["tmp_name"],$uploadfile)){
				// 		$insert_image = "success";
				// 	}else{
				// 		$insert_image = "failed";
				// 	}
				// }else{
				// 	$insert_image = "Image Tidak ada Masukan";
				// }
				//if ($insert_image==="success"){
					//jika photo di update eksekusi query
					$update= $this->db->query("Update tranksaksi Set id_cust
					='".$data_tranksaksi['id_cust']."'
					, no_antrian ='".$data_tranksaksi['no_antrian']."' 
					, nama_salon ='".$data_tranksaksi['nama_salon']."'
					, photo ='".$data_tranksaksi['photo']."'
					 Where id_tranksaksi ='".$data_tranksaksi['id_transaksi']."'");
					// $data_tranksaksi['photo'] = base_url()."upload/".$user_img;
				//}
				//else{
					//jika photo di kosong atau tidak di update eksekusi query
					$update= $this->db->query("Update tranksaksi Set id_cust
					='".$data_tranksaksi['id_cust']."'
					, no_antrian ='".$data_tranksaksi['no_antrian']."'
					, nama_salon ='".$data_tranksaksi['nama_salon']."' 
					Where id_tranksaksi ='".$data_tranksaksi['id_transaksi']."'");
					// $getPhotoPath =$this->db->query("SELECT photo
					// FROM tranksaksi Where id_tranksaksi='".$data_tranksaksi['id_transaksi']."'")->result();
					// if(!empty($getPhotoPath)){
					// 	foreach ($getPhotoPath as $row)
					// 	{
					// 	$user_img = $row->photo;
					// 	$data_tranksaksi['photo'] =
					// 	base_url().$user_img;
					// 	}
					// }
				}
				if ($update){
					$this->response(array('status'=>'success','result' =>
					array($data_tranksaksi),"message"=>$update));
				}
			}
		}
	}

	function deleteTranksaksi($data_tranksaksi){
	if (empty($data_tranksaksi['id_transaksi'])){
		$this->response(array('status' => "failed", "message"=>"Id tranksaksi harus diisi"));
	 }
	// else{
	// 	// $getPhotoPath =$this->db->query("SELECT photo FROM tranksaksi Where id_tranksaksi='".$data_tranksaksi['id_transaksi']."'")->result();
	// 	if(!empty($getPhotoPath)){
	// 		foreach ($getPhotoPath as $row)
	// 		{
	// 			$path = str_replace("application/", "",	APPPATH).$row->photo;
	// 		}
	// 		//delete image
	// 		unlink($path);
			$this->db->query("Delete From tranksaksi Where id_tranksaksi='".$data_tranksaksi['id_transaksi']."'");
			$this->response(array('status'=>'success',"message"=>"Data id = ".$data_tranksaksi['id_transaksi']." berhasil di delete "));
		// } else{
		// 		$this->response(array('status'=>'fail',"message"=>"Id tranksaksi tidak ada dalam database"));
		// 	}
		//}
	}
