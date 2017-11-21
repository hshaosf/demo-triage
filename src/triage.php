<?php

class Triage{

	var $settings;

	public function __construct($settings){
		$this->settings = $settings;
	}


	public function exec(){
		$this->get_tickets();
		//$this->get_cat('post agenda');
	}

	protected function get_tickets(){

		$headers = [
		    'Authorization:Zoho-authtoken '.$this->settings['ticket_token'],
		    'orgId:'.$this->settings['ticket_org_id']
		];

		$opt = array(
			CURLOPT_HTTPHEADER => $headers
		);

		$data = $this->curl($this->settings['ticket_api'] . 'tickets?sortBy=dueDate', $opt);
		if($data && $tickets = $data->data){
			$hasTicket = false;
	  	foreach($tickets as $ticket){
	  		if(!$ticket->assigneeId){
	  			$hasTicket = true;
	  			$cat = $this->get_cat($ticket->subject);
	  			$class = json_encode(array('classification'=>$cat));
	  			$assigned = $this->assign_ticket($ticket->id, $class);
	  			if($assigned){
						echo 'Assigned ticket #'.$ticket->id.' as '.$cat;
					}
	  		}
	  	}
	  	if(!$hasTicket){
	  		echo 'No new tickets.';
	  	}
	  }

	}


	protected function assign_ticket($id, $class){

		$headers = [
		    'Authorization:Zoho-authtoken '.$this->settings['ticket_token'],
		    'orgId:'.$this->settings['ticket_org_id']
		];

		$opt = array(
			CURLOPT_CUSTOMREQUEST => 'PATCH',
			CURLOPT_POSTFIELDS => $class,
			CURLOPT_HTTPHEADER => $headers

		);
		$data = $this->curl($this->settings['ticket_api'] . 'tickets/'.$id, $opt);
		return $data;
	}


	public function get_cat($text){
		$headers = [
			'Authorization: Bearer '. $this->settings['ai_token'],
			'Content-Type: application/json; charset=utf-8'
		];
		$opt = array(
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => json_encode(array('query'=>$text, 'lang'=>'en', 'sessionId'=>time())),
			CURLOPT_HTTPHEADER => $headers
		);

		$data = $this->curl($this->settings['ai_api'] . 'query?v=20150910', $opt);
		if($result = $data->result){
			$cat = $result->fulfillment->speech;
		}
		switch($cat){
			case 'Content Posting & Accessibility' :
			case 'WCM Administration' :
			case 'WCM Operations' :
			case 'Website Enhancements' :
			case 'Website Fixes' :
				$cat = str_replace('&', 'and', $cat);
				break;
			default : 
				$cat = 'Not Sure';
				break;
		}
		return $cat;
	}

	protected function curl($url, $opt){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);         
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // FOR DEMO ONLY

		foreach($opt as $k=>$v){
			curl_setopt($ch, $k, $v);
		}
		$output = curl_exec ($ch);
		$data = false;
		if(!curl_errno($ch)){ 
		  $data = json_decode($output);
		  //print_r($data);
		} else { 
		  echo 'Curl error: ' . curl_error($ch); 
		} 
		curl_close ($ch);
		return $data;


	}


}

