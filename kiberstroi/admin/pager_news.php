<?php require_once($_SERVER['DOCUMENT_ROOT']."/mod/mod_config.php");
if(!empty($_GET)){
	$filter = new filter;
	$num = $filter->html_filter($_GET['page']);
	$limit = $filter->html_filter($_GET['limit']);
	$cat = $filter->html_filter($_GET['cat']);
	$filter = $filter->html_filter($_GET['filter']);
	if (is_numeric($num) && is_numeric($limit))
	{
		if (!empty($filter)) $filter = base64_decode($filter);
			$page = @$num;
			$Db->query="SELECT COUNT(id_news) FROM `mod_news` WHERE `id_news`!='0'".$filter; 
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			$posts = $lRes["COUNT(id_news)"]; //кол-во 
			$total = (($posts - 1) / $limit) + 1;
			$total =  intval($total); // общее число страниц
			$page = intval($page);
			if(empty($page) or $page < 0) $page = 1;
  			if($page > $total) $page = $total;
			$start = $page * $limit - $limit;
			if ($start<0) $start=0;	
	
		$Db->query="SELECT `view`,`date`,`id_news`,`act`,`name`
					FROM  `mod_news` 
					 WHERE `id_news`!='0'".$filter." 
					GROUP BY id_news
					ORDER BY mod_news.date DESC LIMIT $start, $limit";
		$Db->query();
		$num_news = 0;
		$temp_date = date("Y-m-d");
		if (mysql_num_rows($Db->lQueryResult)>0) {
				echo '<form method="post" action="index.php?mod=news&action=pereschet" name="form1"> 
			<table border="0" cellspacing="0" cellpadding="0" id="my-list" width="100%"><tr class="head"><td>Название</td><td>Просмотры</td><td width="120">Дата выхода</td><td width="60" class="nobg"><div class="conf"><img src="img/icons/accept_item.png" class="pnghack" align="middle" /><img src="img/icons/trash.png" class="pnghack" align="middle" hspace="7" /></div></td></tr>';

			while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) {
					if ($lRes['act']!=0) $act = "<img src='images/show.png' class='pnghack' alt='Показывается' title='Показывается' border=0 />"; else $act = "<img src='images/hide.png' class='pnghack' alt='Не показывается' title='Не показывается' border=0 />";
					if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
					echo '<tr class="one_news">
								<td><a href="index.php?mod=news&action=edit&id='.$lRes["id_news"].$pagestr.'">'.$lRes["name"].'</a></td>
								<td>'.$lRes["view"].'&nbsp;</td>
								<td>'.formatedpost($lRes["date"]).'&nbsp;</td>
								<td><div class="conf">';
								
		echo "<input type='hidden' value='0' name='act[".$lRes['id_news']."]' />";
		if ($lRes['act']!=0) echo "<input type='checkbox' value='1' name='act[".$lRes['id_news']."]' class='checkboxact' checked='checked' />"; else echo "<input type='checkbox' value='1' name='act[".$lRes['id_news']."]' class='checkboxact' />";
		echo "<input type='checkbox' value='1' name='delete[".$lRes['id_news']."]' class='checkbox'  />
		</div>";
								echo '</td>
								</tr>';
								$num++;
				}
				echo '<tr><td></td><td></td><td><input src="img/icons/tick_red_icon.png" align="middle" class="pnghack" type="image" hspace="7" /></td><td>
				<script src="jscripts/jquery.uniform.js" type="text/javascript" charset="windows-1251"></script><script language="javascript" type="text/javascript" src="jscripts/jquery-ui-1.9.2.custom.min.js"></script>
				<script type="text/javascript">
				$(function () {
					$("input").uniform();								 
					var i = $(\'input\').size() + 1;
		
					$("#selall").live("click", function () {
						if (!$("#selall").is(":checked")){
							$(".checkbox").removeAttr("checked");
							$.uniform.update();
						}
						else{
							$(".checkbox").attr("checked", true);
							$.uniform.update();
						}	
					});
					$("#selall_act").live("click", function () {
						if (!$("#selall_act").is(":checked")){
							$(".checkboxact").removeAttr("checked");
							$.uniform.update();
						}
						else{
							$(".checkboxact").attr("checked", true);
							$.uniform.update();
						}	
					});
				});
			</script>
			
			<div class="conf"><input type="checkbox" value="1" class="checkboxact" id="selall_act" /><input type="checkbox" value="1" class="checkbox" id="selall" /></div></td></tr></table></form>';
		}
	}
}

?>