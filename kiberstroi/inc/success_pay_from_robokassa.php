<? 


$mrh_pass2 = "LBU5FGo2u3lkj";

$tm=getdate(time()+9*3600);
$date="$tm[year]-$tm[mon]-$tm[mday] $tm[hours]:$tm[minutes]:$tm[seconds]";

$out_summ = $_REQUEST["OutSum"];
$inv_id = $_REQUEST["InvId"];
$shp_item = $_REQUEST["Shp_item"];
$login = $_REQUEST["Shp_login"];
$crc = $_REQUEST["SignatureValue"];

$crc = strtoupper($crc);

$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass2:Shp_item=$shp_item:Shp_login=$login"));
				
if ($my_crc != $crc)
{
 // echo "bad sign\n";
 // mail("tonya@cherepkova.ru", "1", "bad sign: $my_crc / $out_summ / $inv_id / $shp_item / $crc");
  exit();
}

	 //mail("tonya@cherepkova.ru", "2", "ok sign: $my_crc / $out_summ / $login / $inv_id / $shp_item / $crc");
	 
	require_once("../mod/mod_config.php");

					
	$subject = 'Новый заказ в интернет-магазине';
	$message = 'Здравствуйте! На сайте '.$DomenName.' новый заказ. Посмотреть подробности заказа, а также контактную информацию заказавшего Вы можете в соответствующем разделе системы администрирования.';
			
	$headers= "MIME-Version: 1.0\r\n";
				$headers.= "Content-type: text/html; charset=windows-1251\r\n";
				$headers.= "From: ".$DomenName." <noreplay@noreplay.ru>";	

	mail($config["main"]["main_email"], $subject, $message, $headers);

	file_get_contents("http://sms.ru/sms/send?api_id=1036c775-4d1f-ba84-993a-9f73658d4658&to=79873838577&text=".urlencode(iconv("windows-1251","utf-8","Новый заказ в вашем интернет-магазине")));
	
	$Db->query="UPDATE `mod_order` SET `finish`='1' WHERE `id_order`='".$inv_id."'";
	$Db->query();

?>
