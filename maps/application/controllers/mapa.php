<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mapa extends CI_Controller{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('mapa_model');
	}
	
	public function index()
	{
		//creamos la configuración del mapa con un array
		$config = array();		
		$config['center'] = '4.60971,-74.08175';		
		$config['zoom'] = '10';		
		$config['map_type'] = 'ROADMAP';		
		$config['map_width'] = '80%';		
		$config['map_height'] = '600px';	
		//inicializamos la configuración del mapa	
		$this->googlemaps->initialize($config);	
		
		//hacemos la consulta al modelo para pedirle 
		//la posición de los markers y el infowindow
		$markers = $this->mapa_model->get_markers();
		foreach($markers as $info_marker)
		{
			$marker = array();
			$marker ['animation'] = 'DROP';
			$marker ['position'] = $info_marker->pos_y.','.$info_marker->pos_x;	
			$marker ['infowindow_content'] = $info_marker->infowindow;
			$marker['id'] = $info_marker->id; 
			$this->googlemaps->add_marker($marker);
			//$marker ['icon'] = base_url().'imagenes/'.$fila->imagen;
			//si queremos que se pueda arrastrar el marker
			//$marker['draggable'] = TRUE;
			//si queremos darle una id, muy útil
		}
		
		//creamos el mapa y lo asignamos a map que lo 
		//tendremos disponible en la vista mapa_view con el array data
		$data['datos'] = $this->mapa_model->get_markers();
		$data['map'] = $this->googlemaps->create_map();
		$this->load->view('mapa_view',$data);
	}
}