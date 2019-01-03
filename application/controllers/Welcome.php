<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	 public function __construct()
       {
		   
            parent::__construct();
           $this->load->database();
       }

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$data['title']="Fénymásoló kártya";
        $this->load->view('templates/header', $data);
		$this->load->view('welcome_message');
		$this->load->view('templates/footer');
	}
	public function generate()
	{
		$this->load->model('Database_Helper');
		$this->load->helper('form'); 
		$tizes=$this->input->post('tizes');
		$huszas=$this->input->post('huszas');
		$otvenes=$this->input->post('otvenes');
		//levédem hogy ha nem írt be számot akkor 0 át generáljon belőle.
		if($tizes=="")
		{
			$tizes=0;
		}
		if($huszas=="")
		{
			$huszas=0;
		}
		if($otvenes=="")
		{
			$otvenes=0;
		}
		$tizes_a=array();
		for($i=0;$i<$tizes;$i++)
		{
			$rand=rand(1000,9999);
			$szam="10".$rand;
			$joe=$this->Database_Helper->addSzam($szam);
			if($joe)
			{
				array_push($tizes_a,$szam);
			}
			else
			{
				$i--;
			}
		}
        $huszas_a=array();
		for($i=0;$i<$huszas;$i++)
		{
			$rand=rand(1000,9999);
			$szam="20".$rand;
			$joe=$this->Database_Helper->addSzam($szam);
			if($joe)
			{
				array_push($huszas_a,$szam);
			}
			else
			{
				$i--;
			}
		}
        $otvenes_a=array();
		for($i=0;$i<$otvenes;$i++)
		{
			$rand=rand(1000,9999);
			$szam="50".$rand;
			$joe=$this->Database_Helper->addSzam($szam);
			if($joe)
			{
				array_push($otvenes_a,$szam);
			}
			else
			{
				$i--;
			}
		}
		$datum=Date('Y_m_d_H_i_s');
        $fp = fopen('csvk/file_'.$datum.'.csv', 'w+');
		$txt="100;add;a18a14;Ágnes;Ducza;do_not_convert@@PIN6b1539ca3e023e31a4b6fcb184f7e905;0;;\\\srv-hallgfs01\home\A18A14 \n";
		fwrite($fp, $txt);	
		for($i=0;$i<count($tizes_a);$i++)
		{
			$txt="100;add;lib".$tizes_a[$i].";Fenymasolo;Tizes;PIN".$tizes_a[$i].";3;;\n";
			fwrite($fp, $txt);	
		}
        for($i=0;$i<count($huszas_a);$i++)
		{
			$txt="100;add;lib".$huszas_a[$i].";Fenymasolo;Huszas;PIN".$huszas_a[$i].";2;;\n";
			fwrite($fp, $txt);	
		}
        for($i=0;$i<count($otvenes_a);$i++)
		{
			$txt="100;add;lib".$otvenes_a[$i].";Fenymasolo;Otvenes;PIN".$otvenes_a[$i].";1;;\n";
			fwrite($fp, $txt);	
		}
fclose($fp);
        
		
	
        $data['fileName']='file_'.$datum.'.csv';
        $data['tizes']=$tizes_a;
        $data['huszas']=$huszas_a;
        $data['otvenes']=$otvenes_a;
        $data['title']="Fénymásoló kártya";
        $this->load->view('templates/header', $data);
		$this->load->view('welcome_message', $data);
		$this->load->view('templates/footer');
	}
}
