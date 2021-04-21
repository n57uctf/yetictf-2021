<?php
	class management extends CI_controller{

		public function exchange(){

			if(!$this->session->userdata('logged_in')){
					redirect('pages/view/homepage');
				}


			$data['title'] = "Exchange";
			$data['error'] = "";

			$this->form_validation->set_rules('amount1','Amount1','required|xss_clean');
			$this->form_validation->set_rules('type1','Type1','required|in_list[coins,links,rocks]');
			$this->form_validation->set_rules('amount2','Amount2','required|xss_clean|less_than_equal_to['.$this->session->userdata('usr_'.$this->input->post('type2').'').']');
			$this->form_validation->set_rules('type2','Type2','required|differs[type1]|in_list[coins,links,rocks]');

			if($this->form_validation->run() === FALSE){

			$this->load->view('templates/mainheader',$data);
			$this->load->view('management/exchange',$data);

			} else {

				$amount1 = $this->input->post('amount1');
				$type1 = $this->input->post('type1');
				$amount2 = $this->input->post('amount2');
				$type2 = $this->input->post('type2');

				if ($this->Mngmodel->exchange($amount1, $type1, $amount2, $type2, $this->session->userdata('usr_hash')))
				{

				redirect('pages/view/mainpage');

				} else {

					$data['error'] = "Something went wrong! Try not to tamper with amounts next time, pal.";
					$this->load->view('templates/mainheader',$data);
					$this->load->view('management/exchange',$data);

				}
			}

		}

		public function exchange_prep(){
			
			if($this->input->post('type1'))
			{
				$data = array(

					'type1' => $this->input->post('type1'),
					'type2' => $this->input->post('type2'),
					'amount2' => $this->input->post('amount2')

				);

				$amount1 = $this->Mngmodel->value_fetch($data);
				echo $amount1;
			}
		}

		public function chart_prep(){

			$chart_info = $this->Mngmodel->chart_fetch();
			$response = json_encode($chart_info);

			echo $response;
		}

		public function purchase(){
			if(!$this->session->userdata('logged_in')){
					redirect('pages/view/homepage');
				}

			$data['title'] = "Exchange";
			$data['error'] = "";

			$this->form_validation->set_rules('amount','Amount','required|xss_clean');
			$this->form_validation->set_rules('type','Type','required');

			if($this->form_validation->run() === FALSE){

			$this->load->view('templates/mainheader',$data);
			$this->load->view('management/exchange',$data);

			} else {

				$data['error'] = "Operation not available";
				
				$this->load->view('templates/mainheader',$data);
				$this->load->view('management/exchange',$data);
				
			}
		}

		public function calculations(){
			if(!$this->session->userdata('logged_in')){
					redirect('pages/view/homepage');
				}

			$data['title'] = "Calculations";

			$this->form_validation->set_rules('type','Type','required|in_list[coins,links,rocks]');
			$this->form_validation->set_rules('calcres','Calculation result','required|less_than_equal_to['.$this->session->userdata('calcres').']|greater_than_equal_to['.$this->session->userdata('calcres').']');

			if($this->form_validation->run() === FALSE){

			$calc1 = random_int(1000, 9999);
			$calc2 = random_int(1000, 9999);
			
			$calc11 = sqrt($calc1+$calc2);
			$calc22 = sqrt($calc1*$calc2);

			$calcres = round(sqrt($calc22/$calc11),2);

			$this->session->set_userdata('calc1',$calc1);
			$this->session->set_userdata('calc2',$calc2);
			$this->session->set_userdata('calcres',$calcres);

			$data['task'] = "<div class=\"task\">Calculate some square roots. First, sqrt sum of ".$calc1." and ".$calc2.", then sqrt of multiplication of those, divide one on another and square root them. Round result by 2 digits. Easy enough..</div>";

			$this->load->view('templates/mainheader',$data);
			$this->load->view('management/calculations',$data);

			} else {

				$type = $this->input->post('type');

				$this->Mngmodel->calculations($type, $this->session->userdata('usr_hash'));

				redirect('pages/view/mainpage');
			}
		}

		public function transactions(){

			if(!$this->session->userdata('logged_in')){
					redirect('pages/view/homepage');
				}

			$data['title'] = "Transactions";

			$this->form_validation->set_rules('type','Type','required|xss_clean|in_list[coins,links,rocks]');
			$this->form_validation->set_rules('recv_login','Receivers Login','required|xss_clean');
			$this->form_validation->set_rules('message','Message','required|xss_clean|max_length[128]');
			$this->form_validation->set_rules('amount','Amount', 'required|xss_clean|less_than_equal_to['.$this->session->userdata('usr_'.$this->input->post('type').'').']|greater_than_equal_to[0.01]');

			if ($this->form_validation->run() === FALSE){

				$this->load->view('templates/mainheader',$data);
				$this->load->view('management/transactions');

			} else {

				$recv_login = $this->input->post('recv_login');
				$type = $this->input->post('type');
				$amount = $this->input->post('amount');
				$message = $this->input->post('message');

				$this->Mngmodel->transactions($recv_login,$type,$amount,$message);

				redirect('management/transactions');
			}
		}

		public function contact_us(){

			if(!$this->session->userdata('logged_in')){
					redirect('pages/view/homepage');
				}

			$data['title'] = "Contact Page";

			$this->load->view('templates/mainheader',$data);
			$this->load->view('management/contact');

		}

		public function membership(){

			if(!$this->session->userdata('logged_in')){
					redirect('pages/view/homepage');
				}

			$data['title'] = "Membership";
			$data['error'] = "";

			$this->form_validation->set_rules('type','Type','required|in_list[coins,links,rocks,bucks]');
			$this->form_validation->set_rules('amount','Amount','required|less_than_equal_to['.$this->session->userdata('usr_'.$this->input->post('type').'').']');

			if($this->form_validation->run() === FALSE){

			$this->load->view('templates/mainheader',$data);
			$this->load->view('management/membership', $data);

			} else {

				$data = array(

					'type' => $this->input->post('type'),

				);
				$amount_chk = $this->Mngmodel->membership_fetch($data);
				$amount = $this->input->post('amount');

				if ($amount >= $amount_chk){

				$recv_login = 'tellers2006';
				$type = $this->input->post('type');
				$message = ''.$this->session->userdata('login').' bought membership with '.$amount.' '.$type.'';

				$this->Mngmodel->transactions($recv_login,$type,$amount,$message);
				$this->Mngmodel->membership();

				redirect('management/membership');

				} else {

					$data['title'] = "Membership";
					$data['error'] = "Hey, no need for that, pal!";
					$this->load->view('templates/mainheader',$data);
					$this->load->view('management/membership', $data);

				}

			}

		}

		public function membership_prep(){

			if($this->input->post('type'))
			{
				$data = array(

					'type' => $this->input->post('type'),

				);

				$amount = $this->Mngmodel->membership_fetch($data);
				echo $amount;
			}

		}
}