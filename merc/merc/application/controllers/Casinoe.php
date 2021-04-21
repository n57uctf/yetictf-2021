<?php
	class casinoe extends CI_controller{

		public function frontpage(){


			if(!$this->session->userdata('logged_in')){
					redirect('pages/view/homepage');
				}
			if (!$this->session->userdata('member')){
				redirect('pages/view/mainpage');
			}

			$data['title'] = "Kiosk";

			$this->load->view('kiosk/kiosk_header',$data);
			$this->load->view('pages/kiosk',$data);

		}

		public function account() {

			if(!$this->session->userdata('logged_in')){
					redirect('pages/view/homepage');
				}
			if (!$this->session->userdata('member')){
				redirect('pages/view/mainpage');
			}

			$data['title'] = "Account";

			redirect('casinoe/VIP_page');

		}

		public function plays() {

			if(!$this->session->userdata('logged_in')){
					redirect('pages/view/homepage');
				}
			if (!$this->session->userdata('member')){
				redirect('pages/view/mainpage');
			}

			$data['title'] = "Plays";

			redirect('casinoe/VIP_page');

		}

		public function VIP_page(){

			if(!$this->session->userdata('logged_in')){
					redirect('pages/view/homepage');
				}
			if (!$this->session->userdata('member')){
				redirect('pages/view/mainpage');
			}

			$data['title'] = "VIP";
			$data['mail'] = "";
			$check = $this->Casmodel->application_check();

			if ($check) {

				$data['mail'] = $check;
				$this->load->view('kiosk/kiosk_header',$data);
				$this->load->view('kiosk/VIP',$data);

			} else {

			$this->form_validation->set_rules('email','Email','required|xss_clean');
			$this->form_validation->set_rules('message','Message','required');

			if($this->form_validation->run() === FALSE)
			{

				$this->load->view('kiosk/kiosk_header',$data);
				$this->load->view('kiosk/VIP',$data);

			} else {

				$contact_mail = $this->input->post('email');
				$message = $this->input->post('message');
				$recv_mail = "tellers2006@yandex.ru";
				$header = "applicant";
				$subject = "Manager application, ".$this->session->userdata('login')."";

				$this->email->validate = true;

				$this->email->from($contact_mail);
				$this->email->to($recv_mail); 
				$this->email->subject($subject);
				$this->email->message($message);	
				$this->email->send();

				$this->Casmodel->application();

				redirect('casinoe/VIP_page');

				}
			}
		}

	}
?>