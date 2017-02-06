<?
require_once($_SERVER['DOCUMENT_ROOT']."/mod/mod_func.php");
require_once($_SERVER['DOCUMENT_ROOT']."/admin/db.php");

$Db=new Db ($DBServer,$DBLogin,$DBPassword,$DBName);
$Db->connect();
 
$result=array();
$result['goods']=array();
  
//$str_query=iconv('utf-8','windows-1251',$_POST[query]);
$str_query=$_POST[query];

$Db->query = "SELECT mod_catalog.name_goods, mod_catalog.anchor_goods, mod_catalog.id_goods, mod_catalog_cat.id_cat,mod_catalog_cat.anchor_cat
						FROM `mod_catalog` 
						LEFT JOIN `mod_catalog_cat` ON (mod_catalog_cat.id_cat=mod_catalog.cat) 
						WHERE mod_catalog.act='1' AND  mod_catalog.name_goods LIKE ('%".$str_query."%') ORDER BY mod_catalog.name_goods LIMIT 5";
$Db->query();
if(mysql_num_rows($Db->lQueryResult) > 0)	
{
	while($lRes = mysql_fetch_assoc($Db->lQueryResult)) 
	{
		$result['goods'][]=$lRes;
		/*$result['goods'][]=array(
			'name_goods'=>iconv('windows-1251','utf-8',$lRes["name_goods"]),
			'anchor_goods'=>$lRes["anchor_goods"],
			'id_goods'=>$lRes["id_goods"],
			'id_cat'=>$lRes["id_cat"],
			'anchor_cat'=>$lRes["anchor_cat"]
		);*/		
	}
}
else $result['goods']='EMPTY';

echo json_encode($result);
?>