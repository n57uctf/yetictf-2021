<?php
class Casmodel extends CI_Model{

	public function application(){

		$data = array(

			'email' => $this->input->post('email',true),
			'message' => $this->input->post('message',true),
			'login' => $this->session->userdata('login')

		);

		return $this->db->insert('applications',$data);

	}

	public function application_check(){

		$result = $this->db->where('login', $this->session->userdata('login'))->get('applications');

		if($result->num_rows() == 1){

			return $result->row(0)->message;

		} else {

			return FALSE;

		}

		}
	
}
?>