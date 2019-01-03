<?php
class Database_Helper extends CI_Model {

      
        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }

		/**
		*Get Main Title lvl 1 from terület and returnt it
		**/
        public function addSzam($szam)
        {
			$this->load->database();
			$this->db->select('NUMBER');	
			$this->db->from('general'); 	
			$this->db->where('NUMBER',$szam);
			$query = $this->db->get();
			if($query->num_rows()>0)
			{
				$res=false;
			}
			else
			{
            $date=date("Y-m-d H:i:s");	
			$data =array(
					'NUMBER'=>$szam,
					);
            $this->db->insert('general',$data);	   
			$res=true;	
			}
            return $res;
        }
		/**
		*Get Selected Title from terület and returnt it
		**/
		   public function getSelectedTitle($keres)
        {
			$res=null;
			if(isset($_GET['keres'])){
			$this->load->database();
			$query = $this->db->query("SELECT terulet_kif,t_azon FROM terulet where t_azon='".$keres."' and eft_megjel='1'");
			
			$res=$query->result();
			}
            return $res;
        }
		/**
		*Get All Children lvl 2 from selected and returnt it
		**/
		 public function getChild($keres)
        {
			$this->load->database();			
			$query = $this->db->query("SELECT distinct terulet_kif, terulet.t_azon FROM terulet,hirlevel where szulo='".$keres."' and eft_megjel='1'");
			$res=$query->result();
            return $res;
			
        }
		/**
		*Get All Children's children lvl 3 from selected and returnt it
		**/
		 public function getChildrenChild($keres)
        {
			$res=Array();
			$this->load->database();			
			$query = $this->db->query("SELECT distinct terulet.t_azon FROM terulet,hirlevel where szulo='".$keres."' and eft_megjel='1' ");
			foreach ($query->result() as $row)
			{	$sql="SELECT  terulet_kif,terulet.t_azon,kulfoldi FROM terulet where t_azon not in (select t_azon from hirlevel where terulet.t_azon=hirlevel.t_azon and hirlevel.f_azon=".get_cookie('uid').") and  szulo='".$row->t_azon."' and eft_megjel='1'";
				$query1 = $this->db->query($sql);
				array_push($res,"szulo",$row->t_azon);
				foreach ($query1->result() as $row1)
				{
				array_push($res,$row1->kulfoldi,$row1->t_azon,$row1->terulet_kif);
				}
			
			}
			
            return $res;
			
        }
		//minden megrendelt folyóirat feltöltése
		public function set_eft()
			{
		$this->load->database();	
		$kell=array();
			foreach ( $this->input->post('valaszt[]') as $item)
				{
					array_push($kell,$item);
				
				}
			for($i=0;$i<count($kell);$i++)
			{
			$date=date("Y-m-d H:i:s");	
			$data =array(
					'h_azon'=>'',
					'f_azon'=>get_cookie('uid'),
					't_azon'=>$kell[$i],
					'idopont'=>$date,
					);
					/*insert into hirlevel*/
					$this->db->insert('hirlevel',$data);	
			}
   // return $this->db->insert('news', $data);
}
 public function getUserIdLdap()
 {
	 $username=$this->input->post('username');
	 $this->load->database();			
	 $query = $this->db->get_where('felhasznalo', array('user' => $username,'pass'=>'ldap'));
	 foreach ($query->result() as $row)
{
        $di=$row->f_azon;
}
return $di;
 }
 
  public function getUserNewsLetter()
 {
	 $this->load->database();			
	 $query = $this->db->query("SELECT distinct terulet.terulet_kif,terulet.t_azon as ID FROM terulet,hirlevel where terulet.t_azon=hirlevel.t_azon and hirlevel.f_azon=".get_cookie('uid')." ");
	 $res=$query->result();
	 return $res;
 } 
 public function deleteFromNews($t_azon)
 {
	 $this->load->database();			
	 $this->db->where('t_azon', $t_azon)->where('f_azon',get_cookie('uid'));
	 $this->db->delete('hirlevel'); 
 } 
 public function getProfile()
 {
	 $this->load->database();		
	$this->db->select('e_mail');	
	 $this->db->from('felhasznalo'); 	
	 $this->db->where('f_azon',get_cookie('uid'));
	 $query = $this->db->get();
	 $res=$query->result();
	 return $res;

 }
 public function set_profile()
 {
	 $this->load->database();		
	 $email=$this->input->post('be_name');
	 $data=array(
		'e_mail'=>$email,);
		$this->db->set($data);
		$this->db->where('f_azon',get_cookie('uid'));
		$this->db->update('felhasznalo'); 

 }
 public function isAdmin($id)
 {
	 $res=false;
	$this->load->database();		
	$this->db->select('admin');	
	 $this->db->from('felhasznalo'); 	
	 $this->db->where('f_azon',$id);
	 $query = $this->db->get();
	 $res=$query->result();
	 foreach($res as $row){
		 $admin=$row->admin;
	 }
	 if($admin=="Y")
	 {
		 $res=true;
	 }
	 return $res;

 }
 public function getlvl1Them()
 {
	$this->load->database();		
	$this->db->select('*');	
	 $this->db->from('terulet'); 	
	 $this->db->where('szint','11');
	 $this->db->order_by('terulet_kif','asc');
	 $query = $this->db->get();
	 $res=$query->result();
	 return $res;

 }
 public function getlvl2Them()
 {
	$this->load->database();		
	$this->db->select('*');	
	 $this->db->from('terulet'); 	
	 $this->db->where('szint','12');
	 $this->db->order_by('szulo','asc');
	 $query = $this->db->get();
	 $res=$query->result();
	 $array_a=array();
	 foreach($res as $row)
	 {
		 $this->db->select('terulet_kif');	
		$this->db->from('terulet'); 	
		$this->db->where('t_azon',$row->szulo);
		$query1 = $this->db->get();
		$res1=$query1->result();
		array_push($array_a,$row->t_azon);
		array_push($array_a,$row->terulet_kif);
		foreach($res1 as $row1)
		{
			array_push($array_a,$row1->terulet_kif);
		}
		
	 }
	 return $array_a;

 }
 public function getAllUjsag()
 {
	$this->load->database();		
	$this->db->select('*');	
	 $this->db->from('terulet'); 	
	 $this->db->where('szint <','11');
	 $this->db->order_by('szulo','asc');
	 $this->db->distinct();
	 $query = $this->db->get();
	 $res=$query->result();
	 $array_a=array();
	 foreach($res as $row)
	 {
		$this->db->select('terulet_kif');	
		$this->db->from('terulet'); 	
		$this->db->where('t_azon',$row->szulo);
		$query1 = $this->db->get();
		$res1=$query1->result();
		array_push($array_a,$row->t_azon);
		array_push($array_a,$row->terulet_kif);
		if($query1->num_rows()==0)
		{
			array_push($array_a,"nincs meg a szülő!");
		}
		else{
		foreach($res1 as $row1)
		{
		array_push($array_a,$row1->terulet_kif);
		}
		}
		array_push($array_a,$row->eft_megjel);
		array_push($array_a,$row->eka_megjel);
	 }
	 return $array_a;

 }
 public function getAllUser() {
	$this->load->database();		
	$this->db->select('*');	
	 $this->db->from('felhasznalo'); 	
	 $this->db->order_by('f_azon','asc');
	 $this->db->distinct();
	 $query = $this->db->get();
	 $res=$query->result();
	 return $res;

 }
 public function getContent() {
	$this->load->database();		
	$this->db->select('*');	
	 $this->db->from('terulet'); 	
	 $this->db->where('szint <','11'); 	
	 $this->db->where('eft_megjel','1'); 	
	 $this->db->order_by('terulet_kif','asc');
	 $this->db->distinct();
	 $query = $this->db->get();
	 $res=$query->result();
	 $array_res=array();
	 foreach($res as $row)
	 {
		array_push($array_res,"phase0");
		array_push($array_res,$row->t_azon);
		array_push($array_res,$row->terulet_kif);
		$this->db->select('COUNT(f_azon) as db');	
		$this->db->from('hirlevel'); 	
		$this->db->where('t_azon',$row->t_azon); 	
		$query1 = $this->db->get();
		$res1=$query1->result();
		array_push($array_res,"phase1");
		foreach($res1 as $row1)
		{
			array_push($array_res,$row1->db);
		}
		$this->db->select('f_azon');	
		$this->db->from('hirlevel'); 	
		$this->db->where('t_azon',$row->t_azon); 	
		$query2 = $this->db->get();
		$res2=$query2->result();
		array_push($array_res,"phase2");
			foreach($res2 as $row2)
			{
				$this->db->select('e_mail');	
				$this->db->from('felhasznalo'); 	
				$this->db->where('f_azon',$row2->f_azon); 	
				$query3 = $this->db->get();
				$res3=$query3->result();
				foreach($res3 as $row3)
					{
						array_push($array_res,$row3->e_mail);
						
					}
			}
		 array_push($array_res,"Vege");
	 }
	 
	 return $array_res;

 }
 public function newThemLvl1()
 {
	$this->load->database();	
	$tema=$this->input->post('tema');
	$kulf=$this->input->post('kulfoldi');
	if($kulf==1)
	{}
else{$kulf=0;}
	$data =array(
					'szint'=>'11',
					'eka_szint'=>'11',
					'szulo'=>'9999',
					'kulfoldi'=>$kulf,
					'terulet_kif'=>$tema,
					'tart_jegyz'=>'0',
					'issn'=>'0000-0000',
					'sorrend'=>'0',
					'eft_megjel'=>'1',
					'eka_megjel'=>'1',
					);
					
			$this->db->trans_start();
			$this->db->insert('terulet',$data);
			$eid=$this->db->insert_id();
			$this->db->trans_complete();  

 }
 public function newThemLvl2()
 {
	$this->load->database();	
	$tema=$this->input->post('tema');
	$kulf=$this->input->post('kulfoldi');
	$szulo=$this->input->post('szulo');
	if($kulf==1)
	{}
else{$kulf=0;}
	if($szulo==0)
	{
}
else{
	$data =array(
					'szint'=>'12',
					'eka_szint'=>'12',
					'szulo'=>$szulo,
					'kulfoldi'=>$kulf,
					'terulet_kif'=>$tema,
					'tart_jegyz'=>'0',
					'issn'=>'0000-0000',
					'sorrend'=>'0',
					'eft_megjel'=>'1',
					'eka_megjel'=>'1',
					);
					
			$this->db->trans_start();
			$this->db->insert('terulet',$data);
			$eid=$this->db->insert_id();
			$this->db->trans_complete();  

 }
 }
 public function addUjsag()
 {
	$this->load->database();	
	$tema=$this->input->post('tema');
	$kulf=$this->input->post('kulfoldi');
	$szulo=$this->input->post('szulo');
	if($kulf==1)
	{}
else{$kulf=0;}
	if($szulo==0)
	{
}
else{
	$data =array(
					'szint'=>'0',
					'eka_szint'=>'0',
					'szulo'=>$szulo,
					'kulfoldi'=>$kulf,
					'terulet_kif'=>$tema,
					'tart_jegyz'=>'0',
					'issn'=>'0000-0000',
					'sorrend'=>'0',
					'eft_megjel'=>'1',
					'eka_megjel'=>'1',
					);
					
			$this->db->trans_start();
			$this->db->insert('terulet',$data);
			$eid=$this->db->insert_id();
			$this->db->trans_complete();  

 }
 }
 public function deleteThem()
 {
	$this->load->database();	
	$id=$this->input->post('id');
	$this->db->select('*');	
	 $this->db->from('terulet'); 	
	 $this->db->where('szulo',$id);
	 $query = $this->db->get();
	 $res=$query->result();
	 foreach($res as $row)
	 {
		$this->db->set('szulo', '0');
		$this->db->set('eft_megjel', '0');
		$this->db->where('szulo', $row->t_azon);
		$this->db->update('terulet');
	 }
	 $this->db->where('t_azon', $id);
	 $this->db->delete('terulet');
	

 }
 public function EFTmegjelent()
 {
	$this->load->database();	
	$id=$this->input->post('id');
	$this->db->select('eft_megjel');	
	 $this->db->from('terulet'); 	
	 $this->db->where('t_azon',$id);
	 $query = $this->db->get();
	 $res=$query->result();
	 foreach($res as $row)
	 {
	
	if($row->eft_megjel==1)
	{
		$this->db->set('eft_megjel', '0');
	}
	else
	{
	$this->db->set('eft_megjel', '1');	
	}
		$this->db->where('t_azon', $id);
		$this->db->update('terulet');
	 }
 }
 public function EKAmegjelent()
 {
	$this->load->database();	
	$id=$this->input->post('id');
	$this->db->select('eka_megjel');	
	 $this->db->from('terulet'); 	
	 $this->db->where('t_azon',$id);
	 $query = $this->db->get();
	 $res=$query->result();
	 foreach($res as $row)
	 {
	
	if($row->eft_megjel==1)
	{
		$this->db->set('eka_megjel', '0');
	}
	else
	{
	$this->db->set('eka_megjel', '1');	
	}
		$this->db->where('t_azon', $id);
		$this->db->update('terulet');
	 }
 }
 
 
}
?>