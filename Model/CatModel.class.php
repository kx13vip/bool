<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-08-18 15:35:23
 * @version $Id$
 */

class CatModel extends Model {
	protected $table = 'bool_category';

	/*
	你给我一个关连数组,键->表中的列,键值->表中的值;
	add()自动插入该行数据
	 */
	

	public function add($data){
		return $this -> db -> autoExecute($this->table,$data);
	}

	public function update($data,$cat_id=0){
		$this -> db -> autoExecute($this->table,$data,'update' , ' where cat_id='.$cat_id);
		return $this->db->affected_rows();
	}


	//取出该表下的所有栏目数据
	public function select(){
		$sql = 'select cat_id,cat_name,parent_id from '. $this -> table;
		return $this->db->getAll($sql);
	}

	/*
	getCatTree
	pram: int $id
	return $id栏目下的子孙树
	 */
	public function getCatTree($arr,$lev=0,$id=0){

		$tree = array();

		foreach($arr as $v){
			if ($v['parent_id'] == $id) {
				$v['lev'] = $lev;
				$tree[] = $v;

				$tree = array_merge($tree,$this -> getCatTree($arr,$lev+1,$v['cat_id']));
			}
		}
		return $tree;
	}


/*
parm:int $id
return array $id栏目的家谱树
 */
public function getParTree($id=0){
	$tree = array();
	$cats = $this->select();

while($id>0){
		foreach($cats as $v){
			if ($v['cat_id'] == $id) {
				$tree[] = $v;

				$id = $v['parent_id'];//循环关键所在
				break;
			}
		}
	}
	return array_reverse($tree);
}





/*
parm: int $id
return $id栏目下的子栏目
 */
	public function getson($id){
		$sql = 'select cat_id,cat_name,parent_id from '.$this->table.' where parent_id='.$id;
		return $this->db->getAll($sql);
	}








//删除栏目
public function delete($cat_id=0){
	$sql = 'delete from '. $this -> table . ' where cat_id = ' . $cat_id;
	$this -> db -> query($sql);
	return $this -> db -> affected_rows();
}

//读取主键一行数据
public function find($cat_id){
	$sql = 'select cat_id,cat_name,intro,parent_id from ' . $this->table . ' where cat_id = ' . $cat_id;
	return $this->db->getRow($sql);
}



}


?>