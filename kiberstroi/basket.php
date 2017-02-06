<? 
	parse_str($_SERVER['QUERY_STRING']);
	$edit_array = array("delete","refrash");
	if (in_array($action, $edit_array))
	{
		if ($action=="delete")
		{
				$content = "<h1>Корзина заказа</h1>";
				$content.= "<p>Удаление товара из корзины. Пожалуйста подождите.</p>";

				$id = preg_replace("/\D/","",$id);
				$basket = substr($_COOKIE['basket'], 0, strlen($_COOKIE['basket']) - 1);
				$arr = explode(",",$basket);
				for($i = 0; $i < count($arr); $i++) $goods[] = explode(":", $arr[$i]);
				for($i = 0; $i < count($goods); $i++) if ($goods[$i][0]==$id) unset($goods[$i]);
				$goods = array_values($goods);
				$basket = "";
				if (!empty($goods)) for($i = 0; $i < count($goods); $i++) $basket.= $goods[$i][0].":".$goods[$i][1].":".$goods[$i][2].","; 
				setcookie("basket",$basket);
				header("Location: /order/");
		}
		if ($action=="refrash")
		{
				$content = "<h1>Корзина заказа</h1>";
				$content.= "<p>Обновление товара в корзине. Пожалуйста подождите.</p>";

				$cartMass = $_REQUEST['kolvo'];
				
				$basket = substr($_COOKIE['basket'], 0, strlen($_COOKIE['basket']) - 1);
				$arr = explode(",",$basket);
				for($i = 0; $i < count($arr); $i++) $goods[] = explode(":", $arr[$i]);
				for($i = 0; $i < count($goods); $i++) {
				$tmpval=$cartMass[$i+1];
				$goods[$i][1]=$tmpval;
				}
				$goods = array_values($goods);
				$basket = "";
				if (!empty($goods)) for($i = 0; $i < count($goods); $i++) $basket.= $goods[$i][0].":".$goods[$i][1].":".$goods[$i][2].","; 
				setcookie("basket",$basket);
				header("Location: /order/");
		}
	}
	else
	{
		die("Access denied");	
	}

?>