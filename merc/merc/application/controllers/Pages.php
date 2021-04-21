<?php
	class pages extends CI_controller{
		public function view($page = 'homepage'){
			if(!file_exists(APPPATH.'views/pages/'.$page.'.php')){
				echo "nah";
			}

			if($page=='mainpage'){

				if(!$this->session->userdata('logged_in')){
					redirect('homepage');
				}

				$data['title'] = 'Welcome, '.$this->session->userdata('login').'!';

				$this->Mngmodel->balance_update($this->session->userdata('usr_hash'));

				$this->load->view('templates/mainheader',$data);
				$this->load->view('pages/mainpage', $data);

			} else {
				$data['title'] = ucfirst($page);

			$this->load->view('templates/header', $data);
			$this->load->view('pages/'.$page, $data);
			$this->load->view('templates/footer');
			}
		}
	}
