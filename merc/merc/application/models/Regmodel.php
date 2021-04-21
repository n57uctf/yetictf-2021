<?php 
		class Regmodel extends CI_Model{
			public function __construct(){
				$this->load->database();
			}

			

			public function register($enc_passwd){

				$hashstr = (string)$this->session->userdata('login').(string)random_bytes(16);

				$data = array(
					'login' => $this->input->post('login'),
					'passwd' => $enc_passwd,
					'hash' => md5($hashstr)
				);

				$this->db->insert('users', $data);

				mkdir("transactions/".md5($hashstr)."");
				mkdir("transactions/".md5($hashstr)."/incoming");
				mkdir("transactions/".md5($hashstr)."/outgoing");

				$data = array ('hash' => md5($hashstr));

				return $this->db->insert('balance', $data);
			}

			public function login($login,$passwd){
				$this->db->where('login',$login);
				$this->db->where('passwd',$passwd);

				$result = $this->db->get('users');
				$chart = $this->db->get('chart');

				if($result->num_rows() == 1){

					$this->Mngmodel->balance_update($result->row(0)->hash);
					$acc_info['member'] = $result->row(0)->member;

					return $acc_info;
					
				} else {
				return false;
				}
			}
		}