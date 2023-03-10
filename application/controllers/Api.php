<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	
	public function  __construct(){
		parent::__construct();
		$this->load->model("Session");
	
	}
	
	public function generate()
	{
		$data=file_get_contents('php://input');
		$jsonData = json_decode($data);
	
		if(!($jsonData->username && $jsonData->site)){
			echo json_encode(array("status"=>"error","message"=>"username and site required"));
		}else{
			$postData = array("username"=>$jsonData->username,"site"=>$jsonData->site);
			
			if($this->Session->generate($postData) == 1){
				echo json_encode(array("status"=>"success","message"=>"session generated"));
			}
			else{
				echo json_encode(array("status"=>"error","message"=>"session already exists"));
			}
		}
	}
	
	public function request()
	{
		$data=file_get_contents('php://input');
		$jsonData = json_decode($data);
		
		if(!($jsonData->username && $jsonData->site)){
			echo json_encode(array("status"=>"error","message"=>"username and site required"));
		}else{
			$postData = array("username"=>$jsonData->username,"site"=>$jsonData->site);
			$response = $this->Session->request($postData);
			if($response != "expired"){
				echo json_encode(array("status"=>"success","message"=>$response));
			}
			else{
				$this->Session->remove($postData);
				echo json_encode(array("status"=>"error","message"=>"session not found"));
			}
			
		}
	}
	
	public function remove()
	{
		$data=file_get_contents('php://input');
		$jsonData = json_decode($data);
		
		if(!($jsonData->username && $jsonData->site)){
			echo json_encode(array("status"=>"error","message"=>"id and site required"));
		}else{
			$postData = array("username"=>$jsonData->username,"site"=>$jsonData->site);
			$response = $this->Session->remove($postData);
			if($response > 0){
				echo json_encode(array("status"=>"success","message"=>"session deleted"));
			}
			else{
				echo json_encode(array("status"=>"error","message"=>"session not found"));
			}			
		}
	}
}
