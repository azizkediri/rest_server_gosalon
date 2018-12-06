<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller; 

class Customer extends REST_Controller {

		function __construct($config = 'rest') {         
    	parent::__construct($config);
    	$this->load->database();
    } 
	//$this->response(array("status"=>"success","result" => $get_customer));
	//$this->response(array("status"=>"success"));
	function user_get(){
		$get_customer = $this->db->query("SELECT nama_cust, alamat, sex, photo, password FROM customer")->result();
		$this->response(array("status"=>"success","result" => $get_customer));
	}
	function user_post() {
		$action = $this->post('action');
		$data_customer = array(
			'nama_cust' => $this->post('nama_cust'),
			'alamat' => $this->post('alamat'),
			'sex' => $this->post('sex'),
			'password' => $this->post('password'),
			'photo' => $this->post('photo')
	);
	if ($action==='post')
		{	
			$this->insertCustomer($data_customer);
		}else if ($action==='put'){
			$this->updateCustomer($data_customer);
		}else if ($action==='delete'){ 
			$this->deleteCustomer($data_customer);
		}else{
			$this->response(array("status"=>"failed","message" => "action harus diisi"));
		}
	}
	function insertCustomer($data_customer){
		//function upload image
		$uploaddir = str_replace("application/", "", APPPATH).'upload/';
		if(!file_exists($uploaddir) && !is_dir($uploaddir)) {
			echo mkdir($uploaddir, 0750, true);
		}
		if (!empty($_FILES)){
			$path = $_FILES['photo']['name'];
			$ext = pathinfo($path, PATHINFO_EXTENSION);
			// $user_img = time() . rand() . '.' . $ext;
			$user_img = $data_customer['nama_cust']. '.' . "png";
			$uploadfile = $uploaddir . $user_img;
			$data_customer['photo'] = "upload/".$user_img;
		}else{
			$data_customer['photo']="";
		}
		//////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////
		//cek validasi
		if (empty($data_customer['nama_cust'])){
			$this->response(array('status' => "failed", "message"=>"nama_cust harus diisi"));
		}else if (empty($data_customer['alamat'])){
			$this->response(array('status' => "failed", "message"=>"alamat harus diisi"));
		}else if (empty($data_customer['sex'])){
			$this->response(array('status' => "failed", "message"=>"sex harus diisi"));
		}
		else{
			$get_customer_baseid = $this->db->query("SELECT * FROM customer as p WHERE p.id_cust='".$data_customer['_customer']."'")->result();
			if(empty($get_customer_baseid)){
				$insert= $this->db->insert('customer',$data_customer);
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
					$data_customer['photo'] = base_url()."upload/".$user_img;
				}else{
						$data_customer['photo'] = "";
					}
					if ($insert){
						$this->response(array('status'=>'success','result' =>
						array($data_customer),"message"=>$insert));
					}
			}else{
						$this->response(array('status' => "failed", "message"=>"Id_cust
					sudah ada"));
			}
		}
	}
	function updateCustomer($data_customer){
	//function upload image
		$uploaddir = str_replace("application/", "", APPPATH).'upload/';
		if(!file_exists($uploaddir) && !is_dir($uploaddir)) {
			echo mkdir($uploaddir, 0750, true);
		}
		if(!empty($_FILES)){
			$path = $_FILES['photo']['name'];
			// $ext = pathinfo($path, PATHINFO_EXTENSION);
			//$user_img = time() . rand() . '.' . $ext;
			$user_img = $data_customer['id_cust'].'.' ."png";
			$uploadfile = $uploaddir . $user_img;
			$data_customer['photo'] = "upload/".$user_img;
		}
		//$this->response(array(base_url()."upload/".$user_img));
		//////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////
		//cek validasi
		if (empty($data_customer['id_cust'])){
			$this->response(array('status' => "failed", "message"=>"Id customer harus diisi"));
		}else if (empty($data_customer['nama_cust'])){
			$this->response(array('status' => "failed", "message"=>"nama_cust harus diisi"));
		}else if (empty($data_customer['alamat'])){
			$this->response(array('status' => "failed", "message"=>"alamat harus diisi"));
		}else if (empty($data_customer['sex'])){
			$this->response(array('status' => "failed", "message"=>"sex harus	diisi"));
		}else{
			$get_customer_baseid = $this->db->query("SELECT * FROM customer as p WHERE p.id_cust='".$data_customer['id_cust']."'")->result();
			if(empty($get_customer_baseid)){
				$this->response(array('status' => "failed", "message"=>"Id_cust Tidak ada dalam database"));
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
					$update= $this->db->query("Update customer Set nama_cust
					='".$data_customer['nama_cust']."', alamat ='".$data_customer['alamat']."' , sex
					='".$data_customer['sex']."', photo ='".$data_customer['photo']."' Where id_cust
					='".$data_customer['id_cust']."'");
					$data_customer['photo'] = base_url()."upload/".$user_img;
				}else{
					//jika photo di kosong atau tidak di update eksekusi query
					$update= $this->db->query("Update customer Set nama_cust
					='".$data_customer['nama_cust']."', alamat ='".$data_customer['alamat']."' , sex
					='".$data_customer['sex']."' Where id_cust ='".$data_customer['id_cust']."'");
					$getPhotoPath =$this->db->query("SELECT photo
					FROM customer Where id_cust='".$data_customer['id_cust']."'")->result();
					if(!empty($getPhotoPath)){
						foreach ($getPhotoPath as $row)
						{
						$user_img = $row->photo;
						$data_customer['photo'] =
						base_url().$user_img;
						}
					}
				}
				if ($update){
					$this->response(array('status'=>'success','result' =>
					array($data_customer),"message"=>$update));
				}
			}
		}
	}
	function deleteCustomer($data_customer){
	if (empty($data_customer['id_cust'])){
		$this->response(array('status' => "failed", "message"=>"Id customer harus diisi"));
	}
	else{
		$getPhotoPath =$this->db->query("SELECT photo FROM customer Where
		id_cust='".$data_customer['id_cust']."'")->result();
		if(!empty($getPhotoPath)){
			foreach ($getPhotoPath as $row)
			{
				$path = str_replace("application/", "",	APPPATH).$row->photo;
			}
			//delete image
			unlink($path);
			$this->db->query("Delete From customer Where id_cust='".$data_customer['id_cust']."'");
			$this->response(array('status'=>'success',"message"=>"Data id = ".$data_customer['id_cust']." berhasil di delete "));
		} else{
				$this->response(array('status'=>'fail',"message"=>"Id customer tidak ada dalam database"));
			}
		}
	}
}