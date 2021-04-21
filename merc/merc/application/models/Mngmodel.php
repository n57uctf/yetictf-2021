<?php
class Mngmodel extends CI_Model{
			
			public function __construct(){
				$this->load->database();
			}

			public function value_fetch($data){

				
				$result = $this->db->where('name',$data['type1'])->get('state');
				$price1 = $result->row(0)->price;

				$result = $this->db->where('name',$data['type2'])->get('state');
				$price2 = $result->row(0)->price;

				$amount1 = ($price2 * $data['amount2'])/$price1;

				return $amount1;
			}

			public function membership_fetch($data){

				$result = $this->db->where('name', $data['type'])->get('state');
				$price = $result->row(0)->price;

				$amount = 20;
				return round($amount,2);

			}

			public function chart_fetch(){

				$chart = $this->db->get('chart');
				$chart_info = array();

				foreach($chart->result() as $row){
					$chart_info[] = $row->date;
					$chart_info[] = $row->coins;
					$chart_info[] = $row->links;
					$chart_info[] = $row->rocks;
					}

				return $chart_info;
			}

			public function balance_update($hash){
				
					$this->db->where('hash',$hash);
					$balance = $this->db->get('balance');
					$state = $this->db->get('state');

					if($balance->num_rows() == 1){
					$acc_info = array(
						'usr_hash' => $hash,
						'usr_coins' => round($balance->row(0)->coins,2),
						'usr_links' => round($balance->row(0)->links,2),
						'usr_rocks' => round($balance->row(0)->rocks,2),
						'usr_bucks' => round($balance->row(0)->bucks,2),
						'coins_price' => round($state->row(0)->price,2),
						'links_price' => round($state->row(1)->price,2),
						'rocks_price' => round($state->row(2)->price,2)
					);
					return $this->session->set_userdata($acc_info);
					} else {
					return false;
					}
			}

			public function state_update($type1,$amount1,$type2,$amount2){

				$state1 = $this->db->where('name', $type1)->get('state');
				$state2 = $this->db->where('name', $type2)->get('state');


				if (!($type2 == 'bucks') ){
				$value = $state1->row(0)->value + $amount2 * $state2->row(0)->price;
				$amount = $state1->row(0)->amount + $amount1;
				$price = $value/$amount;

				$data = array(
					'value' => $value,
					'amount' => $amount,
					'price' => $price
				);

				$this->db->where('name', $type1)->update('state', $data);

				$value = $state2->row(0)->value - $amount2 * $state2->row(0)->price;
				$amount = $state2->row(0)->amount - $amount2;
				$price = $value/$amount;

				$data = array(
					'value' => $value,
					'amount' => $amount,
					'price' => $price
				);
				
				return $this->db->where('name', $type2)->update('state', $data);
				} else {

				$value = $state1->row(0)->value + $amount1 * $state1->row(0)->price;
				$amount = $state1->row(0)->amount + $amount1;
				$price = $value/$amount;

				$data = array(
					'value' => $value,
					'amount' => $amount,
					'price' => $price
				);

				return $this->db->where('name', $type1)->update('state', $data);

				}
			}

			public function exchange($amount1,$type1,$amount2,$type2,$hash){

				$this->db->where('hash',$hash);
				$result = $this->db->get('balance');

				$price = $this->db->where('name',$type1)->get('state');
				$price1 = $price->row(0)->price;

				$price = $this->db->where('name',$type2)->get('state');
				$price2 = $price->row(0)->price;

				$amount_chk = ($price2 * $amount2)/$price1;

				if (round($amount1, 2) == round($amount_chk, 2)){

				$data = array(
					$type2 => $result->row(0)->$type2 - $amount2,
					$type1 => $amount1 + $result->row(0)->$type1
				);

				$this->db->where('hash',$hash)->update('balance',$data);

				$this->Mngmodel->state_update($type1,$amount1,$type2,$amount2);

				$this->Mngmodel->balance_update($hash);

				return TRUE;

				} else {

					return FALSE;

				}

			}

			public function purchase($amount,$type,$hash){

				$this->db->where('hash',$hash);
				$result = $this->db->get('balance');
				
				$data = array(
					$type => $amount + $result->row(0)->$type
				);

				$this->db->where('hash',$hash)->update('balance',$data);

				$this->Mngmodel->state_update($type,$amount,'bucks',1);

				return $this->Mngmodel->balance_update($hash);

			}

			public function calculations($type,$hash){

				$this->db->where('hash',$hash);
				$result = $this->db->get('balance');

				$data = array(
					$type => 11 + $result->row(0)->$type
				);

				$this->db->where('hash',$hash)->update('balance',$data);

				$this->Mngmodel->state_update($type,11,'placeholder',0);
				
				return $this->Mngmodel->balance_update($hash);

			}

			public function transactions($recv_login, $type, $amount, $message){

				$result = $this->db->where('login',$recv_login)->get('users');

				if($result->num_rows() == 1){

					$balance = $this->db->where('hash',$result->row(0)->hash)->get('balance');
					$upd_amount = $balance->row(0)->$type + $amount;

					$data = array(

						$type => $upd_amount 

					);

					$this->db->where('hash',$result->row(0)->hash)->update('balance', $data);

					$timestamp = time();
					$outgoing = $message;
					$incoming = $message;

					$incoming .="\nreceived ".$amount." ".$type." from ".$this->session->userdata('login')." on ".date('j-F-Y-h:i:s-A',$timestamp)."";
					$path = "transactions/".$result->row(0)->hash."/incoming/".md5((int)$incoming + (int)$timestamp)."";
					write_file($path, $incoming);

					$outgoing .="\ntransfered ".$amount." ".$type." to ".$recv_login." on ".date('j-F-Y-h:i:s-A',$timestamp)."";
					$path = "transactions/".$this->session->userdata('usr_hash')."/outgoing/".md5((int)$outgoing + (int)$timestamp)."";
					write_file($path, $outgoing);

					$data = array(

						$type => $this->session->userdata('usr_'.$type.'') - $amount 

					);

					$this->db->where('hash',$this->session->userdata('usr_hash'))->update('balance', $data);

					return $this->Mngmodel->balance_update($this->session->userdata('usr_hash'));
				}

			}

			public function membership(){

				$data = array(

					'member' => true

				);
				$this->session->set_userdata('member', true);

				return $this->db->where('login', $this->session->userdata('login'))->update('users',$data);

			}
}
