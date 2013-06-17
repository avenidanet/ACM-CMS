<?php // ACM (cms) created by ACore -1370884118
class cmsController extends AbstractController{
	
	private $vars = '';
	private $page_data = '';
	private $page_name = '';
	private $path_template = 'template/';

	public function __construct(){
		parent::__construct();
		$url = explode("?", $_SERVER['REQUEST_URI']);
		$this->vars = explode("/",$url[0]);
		
		//Limpiar vars y filtrar
		$this->vars = array_filter($this->vars);
		$this->vars = array_values($this->vars);
		
		//Define que buscar 1 o 0 o nada
		if(isset($this->vars[1])){
			$parent = $this->model->searchPage($this->vars[0]);
			if(!empty($parent)){
				$child = $this->model->searchPage($this->vars[1]);
				if($child['parent'] == $parent['id']){
					$this->page_name = $this->vars[1];
				}
			}
		}elseif(isset($this->vars[0])){
			$this->page_name = $this->vars[0];
		}else{
			$this->page_name = 'index';
		}
		
		$this->get_functions();
		$this->get_template();
	}
	
	/*
	 * Template
	 */
	public function get_header(){
		//Incluye un header
		$this->get_file('header');
	}
	public function get_footer(){
		//Incluye un footer
		$this->get_file('footer');
	}
	private function get_functions(){
		//si existe funciones
		$this->get_file('functions');
	}
		
	private function get_template(){
		if($this->page_name != ''){
			//Busca el archivo
			$file = 'template/'.$this->page_name.".php";
			if(file_exists($file)){
				require_once $file;
			}else{
				//Busca en BD y guarda la data
				$this->page_data = $this->model->searchPage($this->page_name);
				if(!empty($this->page_data)){
					$this->get_page($this->page_data[id]);
					$children = $this->model->searchChildren($this->page_data[id]);
					if(empty($children)){
						$this->get_file('single', 'index');
					}else{
						$this->get_file('multiple', 'single');
					}
				}else{
					$this->get_file('404', 'index');				
				}
			}	
		}else{
			$this->get_file('404', 'index');
		}			
	}
	
	/*
	 * Metodos obtencion de datos
	 */
	public function page($val){
		return $this->page_data[$val];
	}
	public function get_parent($page = ''){
		//Devuelve data el padre si existe
		//
	}
	public function get_children($page = ''){
		//Devuelve data los hijos si existen
		//$children = $this->model->searchChildren($tmp_page[id]);
	}
	
	public function get_page($page = ''){
		//Devuelve todos los valores de page
	}
	
	public function get_vars(){
		return $this->vars;
	}
	
	private function get_file($file,$option = ''){
		if(file_exists($this->path_template.$file.'.php')){
			require_once $this->path_template.$file.'.php';
		}else{
			if(file_exists($this->path_template.$option.'.php')){
				require_once $this->path_template.$option.'.php';
			}
		}
	}
	
}