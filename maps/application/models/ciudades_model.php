<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ciudades_model extends CI_Model{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function provincias()
	{
		$this->db->order_by('provincia','asc');
		$provincia = $this->db->get('provincia');
		if($provincia->num_rows()>0)
		{
			return $provincia->result();
		}
	}
	
	public function localidades($provincia)
	{
		$this->db->where('idprovincia',$provincia);
		$this->db->order_by('poblacion','asc');
		$localidades = $this->db->get('poblacion');
		if($localidades->num_rows()>0)
		{
			return $localidades->result();
		}
	}
}
