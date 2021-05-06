<?php

abstract class API{
	
	protected $success = false;
	
	protected $error = false;
	
	protected $collection = array(
		"collection_name"=>null,
		"collection"=>null
	);
	
	protected $collection_updated = false;
	
	protected function update_error($error_message){
		$this->error = $error_message;
	}
	
	protected function update_collection($collection_name, $collection){
		$this->collection['collection_name'] = $collection_name;
		$this->collection['collection'] = $collection;
		$this->collection_updated = true;
		$this->success = true;
	}
	
	public function get_item_data(){
		
		$param_name = $this->get_query_param_name();
		
		if(isset($_REQUEST[$param_name])){
			$this->fetch_item_data($_REQUEST[$param_name]);
		}
		
		else{
			$this->update_error("Invalid request");
		}
		
   		$this->render_result_json();			
	}
	
	private function render_result_json(){
		$return_array = array(
			"success"=>$this->success
		);
		
		if($this->error){
			$return_array["error"] = $this->error;
		}
		if($this->collection_updated){
			$return_array[$this->collection['collection_name']] = $this->collection['collection'];
		}
		echo json_encode($return_array);
	}
	
	//abstract public function update_items();
	abstract protected function fetch_item_data($item_id);
	abstract protected function get_query_param_name();
}

class Products_API extends API{
	
	protected $dummy_products = array(
		0 => array(
			"id" => 0,
			"name"=> "A very very smart watch!!!",
			"description"=> "This watch, created by \"watch me\" company, is a magnifecent toy to show off to your friends. <br/> Purchase this watch and start bragging!! <b>Yeah!!!</b>",
		),
		1 => array(
			"id" => 1,
			"name"=> "A very very smart watch!!!",
			"description"=> "This watch, created by watch me company, is a magnifecent toy to show off to your friends. <br/> Purchase this watch and start bragging!! <b>Yeah!!!</b>",
		),
		2 => array(
			"id" => 2,
			"name"=> "\"ray-bun\" sun glasses",
			"description"=> "Now here are fun sunglasses to walk around with. <br/> wear them and your eyes will be much healthier. Garenty!!",
		),
		3 => array(
			"id" => 3,
			"name"=> "A beautyfull shue",
			"description"=> "Have you ever wanted to run faster, look higher and feel healthier? well this is defenetly the shue for you!!!",
		)		
	);
	
	protected function get_query_param_name(){
		return "product";
	}
	
	protected function fetch_item_data($item_id){
		if(isset($this->dummy_products[$item_id])){
			$item = $this->dummy_products[$item_id];
			$this->update_collection("product", $item);
		}
		else{
			$this->update_error("Product was not found...");
		}
		
		return;
	}
    
}



$products = new Products_API();
$products->get_item_data();