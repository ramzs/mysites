<? 
$PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied"); 
// ���������� ��������� get �������
			 
	$filter = new filter;
	if (what_ras($param[1])=="html") $page_name = substr($filter->html_filter(@$param[1]), 0, -5); else $page_str = $filter->html_filter(@$param[1]);
	$content = "";
	$pagename = 'stati';
	
// ���� �� ����� �������� ���������� ������
if (!@($page_name)) { // ���� �� ����� �������� ��������� ������� ��� ������	

			// ������� ��� ������������ ���������
			$num = 5; // ���-�� ��������� �� ��������	
			$page = @$page_str;
			$Db->query="SELECT COUNT(id_docs) FROM `mod_docs` WHERE act ='1'"; 
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			$posts = $lRes["COUNT(id_docs)"]; //���-�� �� ������ ���������
			$total = (($posts - 1) / $num) + 1;
			$total =  intval($total); // ����� ����� �������
			$page = intval($page);
			if(empty($page) or $page < 0) $page = 1;
  			if($page > $total) $page = $total;
			$start = $page * $num - $num;
			if ($start<0) $start=0;

	$Db->query="SELECT * FROM `mod_docs` WHERE `act`='1' ORDER BY date DESC LIMIT $start, $num";
	$Db->query();

	$title = ($lRes['title']=='') ? '���������� ������ - '.$config['main']['main_title'] : $lRes['title'].' - '.$config['main']['main_title'];
	$keys = ($lRes['keys']=='') ? '����������, ������,'.$config['main']['main_keys'] : $lRes['keys'].' - '.$config['main']['main_keys'];
	$meta = ($lRes['meta']=='') ? '���������� ������ - '.$config['main']['main_meta'] : $lRes['meta'].' - '.$config['main']['main_meta'];		

	$content.= '<div class="breadcrombs"><a href="/">�������</a> / ���������� ������</div><h1 class="no_icon_header"><i class="fa fa-pencil"></i> ���������� ������</h1>';
	if (mysql_num_rows($Db->lQueryResult)>0) 
	{
		while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) {
							
							$content.= '<div class="one_docs"><a href="/docs/'.$lRes["anchor"].'.html">'.$lRes["name"].'</a>'.$lRes["anons"].' <a href="/docs/'.$lRes["anchor"].'.html">��������� >></a>
								</div>';
							}
if ($total>1) {
	for ($i=1; $i<=$total; $i++) { 
		if ($page!=$i) 
		{
		$navi.=' <a href=/docs/'.$i.'/>[ '.$i.' ]</a> | '; 
		}
		else 
		{
		$navi.= '<b>'.$i.'</b> |';
		}
	}	
}
			$content.="<div class='sub_categ'>".$navi."</div>";
	}
	else $content.="<div class='docs_text'>������ ���.</div>";
		}
// ���� ����� �������� ���������� ������ ������� ��
	else {
$Db->query="SELECT * FROM `mod_docs` WHERE `anchor`='".$page_name."' LIMIT 1";
$Db->query();
if (mysql_num_rows($Db->lQueryResult)>0) 
	{
	$lRes=mysql_fetch_assoc($Db->lQueryResult);
	$title = $lRes['title'];
	$keys = $lRes['keys'];
	$meta = $lRes['meta'];
	$normtext=str_replace("../","/",stripslashes($lRes[text]));
	$content = '<div class="breadcrombs"><a href="/">�������</a> / <a href="/docs/">���������� ������</a> / '.$lRes['name'].'</div>';

	$icon = ($lRes['icon']=='') ? '' : '<i class="fa '.$lRes['icon'].'"></i> ';
	
	$content.= '<h1 class="no_icon_header">'.$icon.$lRes['name'].'</h1>';
	$id = $lRes['id_docs'];
	$date = $lRes['date'];
	$view = $lRes['view'];
	
	$content.="<div class='docs_text'>$normtext</div>";
	$view++;
	$Db->query="UPDATE `mod_docs` SET `view`='".$view."' WHERE `anchor`='".$page_name."'";
	$Db->query();
		
	}
	else
	{
		header('HTTP/1.0 404 not found');
		$content = "<h1>������.</h1>�������� �� �������.</p>";
	}
}

include("inc/header.php");
echo $content;
include("inc/footer.php");
?>