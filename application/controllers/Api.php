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
			print json_encode(array("status"=>"error","message"=>"username and site required"));
		}else{
			$postData = array("id"=>$jsonData->username,"site"=>$jsonData->site);
			if($this->Session->generate($postData) > 0){
				print json_encode(array("status"=>"success","message"=>"token generate"));
			}
			else{
				print json_encode(array("status"=>"error","message"=>"token already exists"));
			}
		}
	}
	
	public function request()
	{
		$data=file_get_contents('php://input');
		$jsonData = json_decode($data);
		
		if(!($jsonData->id && $jsonData->site)){
			print json_encode(array("status"=>"error","message"=>"username and site required"));
		}else{
			$postData = array("username"=>$jsonData->username,"site"=>$jsonData->site);
			$response = $this->Session->request($postData);
			if($response != "expired"){
				print json_encode(array("status"=>"success","message"=>$response));
			}
			else{
				$this->Session->remove($postData);
				print json_encode(array("status"=>"error","message"=>"token not found"));
			}
			
		}
	}
	
	public function remove()
	{
		$data=file_get_contents('php://input');
		$jsonData = json_decode($data);
		
		if(!($jsonData->username && $jsonData->site)){
			print json_encode(array("status"=>"error","message"=>"id and site required"));
		}else{
			$postData = array("username"=>$jsonData->username,"site"=>$jsonData->site);
			$response = $this->Session->remove($postData);
			if($response > 0){
				print json_encode(array("status"=>"success","message"=>"token deleted"));
			}
			else{
				print json_encode(array("status"=>"error","message"=>"token not found"));
			}			
		}
	}
}
