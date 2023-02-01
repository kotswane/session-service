<?php
	class Session extends CI_Model {
		
		public function generate($data)
        {
	
			$sql = "SELECT * FROM session_management.user_session where username='".$data['id']."' and site='".$data['site']."' and created >= Now() limit 1;";
            $query=$this->db->query($sql);
			if ($query->num_rows() > 0)
			{
				return 0;
			}
			$data["username"] = $data['id'];
			$data["token"] = $data['id']."-".$data['site']."-".uniqid();
			$data["created"] = date("Y-m-d H:i:s", strtotime("+1 minutes"));
			$this->remove($data);
			$this->db->insert('user_session',$data);
			return (($this->db->insert_id()>0)?$this->db->insert_id():0); 
        }
		
		public function request($data){
			
			$sql = "SELECT * FROM session_management.user_session where username='".$data['id']."' and site='".$data['site']."' and created >= Now() limit 1;";
			$query=$this->db->query($sql);
            if ($query->num_rows() == 0)
			{
				$this->remove($data);
				return "expired";
			}else{
				$row = $query->result();
				return $row[0]->token;
			}
						
		}

		 public function remove($data){
			 
			 $this->db->where('username', $data['id']);
			 $this->db->delete('user_session');
			 return $this->db->affected_rows();
					
		}

		
	}
?>