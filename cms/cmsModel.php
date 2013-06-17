<?php // ACM (cms) created by ACore -1370884118
class cmsModel extends AbstractModel{
	
	public function searchPage($page){
		$result = $this->querySelect("acore_pages",FALSE,'*','url = :url',array('url'=>$page));
		if(!empty($result)){
			return $result[0];
		}else{
			return 0;
		}
	}
	
	public function searchChildren($parent){
		$result = $this->querySelect("acore_pages",FALSE,'*','parent = :parent',array('parent'=>$parent));
		if(!empty($result)){
			return $result;
		}else{
			return 0;
		}
	}
}