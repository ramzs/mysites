<?php
header("Content-type: text/html; charset=windows-1251");
require_once("../mod/mod_config.php");

$content = '';
$order = '';

if (isset($_POST['action']) && $_POST['action']=='send_basket_cookies'){
	$filter = new filter;
	$phone = $filter->html_filter(stripslashes($_POST['phone']));
	$name = $filter->html_filter(stripslashes($_POST['name']));
	
	$name = iconv("utf-8", "windows-1251", $name);
	$phone = iconv("utf-8", "windows-1251", $phone);
			$subject = 'Заявка с сайта';
            $message = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
						  <td width="30%"><p align="center"><img src="http://kiberstroi.ru/images/logo.png" /></p></td>
						  <td><p>'.$config["main"]["title"].'<br />
						  '.$config["main"]["main_email"].'<br />
						  '.$config["main"]["main_phone"].'</p></td>
							</tr>
						  </table>
							Здравствуйте! Заявка с сайта:
							<p>Имя: '.$name.'<br />Телефон: '.$phone.'</p>
						
							
							<br /><br />С уважением, администрация '.$DomenName;
				
						$headers= "MIME-Version: 1.0\r\n";
						$headers.= "Content-type: text/html; charset=windows-1251\r\n";
						$headers.= "From: Kiberstroi.ru <".$config["main"]["main_email"].">";				
							
    		mail($config["main"]["main_email"], $subject, $message, $headers);
	
}

if (isset($_POST['action']) && $_POST['action']=='filter'){
	
	if (empty($_POST["myArray"])) $_POST["myArray"] = array();
	$filter = new filter;
	$this_page = $filter->html_filter(@$_POST['this_page']);
	$parametry=array_filter($_POST["myArray"]);
	foreach ($parametry as $k=>$v) 
	{
		if ($v!="все") {$filter_query.=$v.","; $export_filter_id_array[]=$v;$nubm++;}					
	}
	
	$filter_query = substr($filter_query,0,-1); 
	//echo $filter_query;
	
	//находим все товары, подпадающие под параметры
					
					if ($filter_query!="") 
					{
					
						$filter_query_arr=explode(',',$filter_query);				
						$arr_numb=0;
						$query_arrays=array();
						//var_dump($filter_query_arr);
						foreach ($filter_query_arr as $v)
						{
							$Db->query="SELECT goods_id FROM filter_goods WHERE params_rel=$v ORDER BY goods_id";
							$Db->query();	
							if (mysql_num_rows(($Db->lQueryResult))>0)
							{
								while($lRes=mysql_fetch_assoc($Db->lQueryResult)) $query_arrays[$arr_numb][]=$lRes[goods_id];
							}
							$arr_numb++;
						}
						
						if (sizeof($filter_query_arr)>1)
						{
							$trigg=true;
							$result=array_intersect($query_arrays[0],$query_arrays[1]);
							//var_dump($result);
							for ($i=1;$i<$arr_numb;$i++)
							{
								$result=array_intersect($result,$query_arrays[$i]);
								//var_dump($result);
							}
						}
						$goods_not_found=false;
						if ($trigg) {
							
							if (sizeof($result)!=0) 
							{
							$result=implode(",", $result);
							$goods_query = "AND id_goods IN (".$result.")";
							}
							else {$goods_not_found=true;$its_no_goods=1;}
						}
						else {
						$result=implode(",",$query_arrays[0]);
						$goods_query = "AND id_goods IN (".$result.")";
						}

					}


			if ($goods_query==" AND id_goods IN ()") { $goods_query=" AND id_goods='0'"; $its_no_goods=1; }
			
			$Db->query="SELECT mod_catalog.*, mod_catalog_cat.id_cat,mod_catalog_cat.anchor_cat
						FROM `mod_catalog` 
						LEFT JOIN mod_catalog_cat ON (mod_catalog_cat.id_cat=mod_catalog.cat)
						WHERE cat='".$this_page."'";
			if (!empty($goods_query)) $Db->query.=$goods_query.$param_query;
			$Db->query.=" AND mod_catalog.act='1' ORDER BY price";
			$Db->query();
			if (mysql_num_rows($Db->lQueryResult)>0) {
				while ($lRes=mysql_fetch_array($Db->lQueryResult)) { 
					if ($lRes["img_good"]!='') $img=$lRes["img_good"]; else $img="empty";
					
					echo '<li>
						<div class="product_image"><img src="/upload/goods/sm'.$img.'.jpg" alt=""></div>
						<a href="/catalog/'.$lRes["id_cat"].'-'.$lRes["anchor_cat"].'/'.$lRes["id_goods"].'-'.$lRes["anchor_goods"].'.html" class="product_name">'.$lRes["name_goods"].'</a>
						<div class="product_text">'.stripslashes($lRes["text_teh"]).'</div>
						<div class="product_price">'.$lRes["price"].' руб</div>
						<a href="#" class="button_buy" data-id="good-'.$lRes["id_goods"].'-'.$lRes["price"].'">в корзину</a>
						
					</li>';
				}
			}

	
}
?>