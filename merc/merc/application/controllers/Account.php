<?php
	class account extends CI_controller{
	
		public function register(){

			if($this->session->userdata('logged_in')){
					redirect('pages/view/mainpage');
				}

			$data['title'] = "Registration";
			$this->form_validation->set_rules('login','Login','required|xss_clean|is_unique[users.login]');
			$this->form_validation->set_rules('passwd','Password','required');
			$this->form_validation->set_rules('passwd2','Password Conformation','matches[passwd]');

			if($this->form_validation->run() === FALSE){

				$this->load->view('templates/header', $data);
				$this->load->view('account/register', $data);
				$this->load->view('templates/footer');

			} else {

				
				$enc_passwd = md5($this->input->post('passwd'));
				$this->Regmodel->register($enc_passwd);

				redirect('account/login');
			}
		}

		public function login(){

			if($this->session->userdata('logged_in')){
					redirect('pages/view/mainpage');
				}

			$data['title'] = "Log In";
			$this->form_validation->set_rules('login','Login','required');
			$this->form_validation->set_rules('passwd','Password','required');
			

			if($this->form_validation->run() === FALSE){
				
				$this->load->view('templates/header', $data);
				$this->load->view('account/login', $data);
				$this->load->view('templates/footer');

			} else {
				$login = $this->input->post('login');
				$passwd = md5($this->input->post('passwd'));
				$acc_info = $this->Regmodel->login($login,$passwd);

				$calc1 = random_int(1000, 9999);
				$calc2 = random_int(1000, 9999);
				$calcres = $calc1 + $calc2;

				if ($acc_info) {

					$usrdata = array(
						'login' => $login,
						'logged_in' => true,
						'calc1' => $calc1,
						'calc2' => $calc2,
						'calcres' => $calcres,
						'member' => $acc_info['member']
					);

					$this->session->set_userdata($usrdata);
					$this->session->set_flashdata('loggedin', 'Logged In Successfully');
					redirect('pages/view/mainpage');

				} else {
					$this->session->set_flashdata('login_failed', 'Login is invalid');

					redirect('account/login');
				}
			}
		}

		public function logout(){
			$this->session->unset_userdata('logged_in');
			$this->session->unset_userdata('login');
			$this->session->unset_userdata('id');

			redirect('pages/view/homepage');
		}

		public function buy(){

			$this->form_validation->set_rules('amount', 'Amount', 'required|xss_clean');

			if (!$this->form_validation->run()===FALSE){

				$data['title'] = 'Welcome, '.$this->session->userdata('login').'!';

				$this->load->view('templates/mainheader',$data);
				$this->load->view('pages/view/mainpage');

			} else {
				
				$this->session->set_flashdata('purchase_success', 'Purchase successful!');

			}
		}
	}