<? 
// класс подключения к базе
class Db {
private $host;
private $database;
private $name;
private $pass;
private $link;

public $query;

	// Конструктор
	public function __construct ($h, $n, $p, $b) {
$this->host=$h;
$this->name=$n;
$this->pass=$p;
		$this->database=$b;
	}

	// Функция подключения к БД
	public function connect() {
    
		if (!($this->link=mysql_connect($this->host,$this->name,$this->pass))) {
			$this->error('Не могу подключиться к '.$this->host);
			$noerrors=false;
		}

		if (!mysql_select_db($this->database)) {
			$this->error('Не могу войти в базу '.$this->database);
			$noerrors=false;
		}

		return $noerrors;

	}

	// Функция выполнения запроса
	public function query() {

		$this->lQueryResult=mysql_query($this->query)
		or $this->error('Не могу выполнить запрос'.mysql_error());
		return $this->lQueryResult;

	}
	function error ($what_error) {
	echo $what_error;
	}

}

// Класс фильтра входящих данных
class filter {

public function html_filter($input) {

$text = preg_replace('%&\s*\{[^}]*(\}\s*;?|$)%'
, '', $input);
$text = preg_replace('/[<>]/', '', $text);

        if(!get_magic_quotes_gpc()) {
        $text = addslashes($text);
        }
$badwords = array('input', 'union', 'script', 'update', 'script', 'www', 'http', '.ru');
        $text = str_replace($badwords, '', $text);

return $text;

}
}
// Класс отправки писем
class sent_mail {
    var $parts;
    var $to;
    var $from;
    var $headers;
    var $subject;
    var $body;
 
    function Lib_Sent() {
        $this->parts = array();
        $this->to =  "";
        $this->from =  "";
        $this->subject =  "";
        $this->body =  "";
        $this->headers =  "";
    }
 
    function add_attachment($message, $name = "",
 $ctype = "application/octet-stream", $cid='', $encode='') {
        $this->parts [] = array (
                                "ctype" => $ctype,
                                "message" => $message,
                                "encode" => $encode,
                                "name" => $name,
                                "cid" => $cid
                                );
    }
 
    function build_message($part) {
        $message = $part["message"];
        if ($part["ctype"] == "image/jpeg") {
 
            $message = chunk_split(base64_encode($message));
            $encoding = "base64";
            $hdr = "Content-Type: ".$part["ctype"]."\n";
            $hdr .= "Content-Transfer-Encoding: $encoding\n";
            $hdr .= ($part["name"]? "Content-Disposition: attachment; filename = \""
                 .$part["name"]."\"\n" : "\n");
            $hdr .= "Content-ID: <".$part["cid"].">\n";
        }
        else {
            $hdr = "Content-Type: text/html; charset=windows-1251\n";
            $hdr.= "Content-Transfer-Encoding: Quot-Printed\n\n";
        }
        $hdr.= "\n$message\n";
        return $hdr;
    }
 
    function build_multipart() {
        $boundary = "--b".md5(uniqid(time()));
        $multipart = "Content-Type: multipart/mixed; boundary=\"$boundary\"\n\n--$boundary";
        for($i = sizeof($this->parts)-1; $i>=0; $i--) {
            $multipart .= "\n".$this->build_message($this->parts[$i]). "--$boundary";
        }
        return $multipart.=  "--\n";
    }
 
    function send() {
        $mime = "";
        if (!empty($this->from)) {
            $mime .= "From: ".$this->from. "\n";
        }
        if (!empty($this->headers)) {
            $mime .= $this->headers. "\n";
        }
        if (!empty($this->body)) {
            $this->add_attachment($this->body, "", "text/html;charset=windows-1251");
        }
        $mime .= "MIME-Version: 1.0\n".$this->build_multipart();
 
        foreach ($this->to as $value) {
            mail($value, $this->subject, "", $mime);
        }
    }
}
function num2word($num,$words) {
  $num=$num%100;
  if ($num>19) { $num=$num%10; }
  switch ($num) {
    case 1:  { return($words[0]); }
    case 2: case 3: case 4:  { return($words[1]); }
    default: { return($words[2]); }
  }
}
// Вспомогательная функция для ссылок
function get_domain()
{
$domain = "http://";
return $domain;
}
/* Функция для очистки массива от пустых строк */
function Clear_array($array)
{
	$c=sizeof($array);
	$tmp_array=array();
	for($i=0; $i<$c; $i++)
	{
	if (!(trim($array[$i])==""))
		{
		$tmp_array[]=$array[$i];
		}
	}
	return $tmp_array;
}
/* Функция обрезания текстовой строки до нужного кол-ва символов */
function substring($str,$count=150){
  $str=strip_tags($str);
  if (strlen($str)>$count) {
    $substr=substr($str,0,$count-1);
    return substr($substr,0,strlen($substr)-strlen(strrchr(substr($str,0,$count-1)," "))+1)."...";
  }else{
    return $str;
  }
}
/* Функция преобразования даты к нормальному виду */
function formatedpost($postdate, $time = true){ 
    $lastpostdate=date("d-m-Y", strtotime($postdate)); 
    $lastposttime=date("H:i", strtotime($postdate));     

    $das=explode("-", $lastpostdate); 
    if ($das[1]!="") {
    $last = $das[1]; 
        if ($last=="01") $last = " января "; 
        if ($last=="02") $last = " февраля ";  
        if ($last=="03") $last = " марта "; 
        if ($last=="04") $last = " апреля "; 
        if ($last=="05") $last = " мая "; 
        if ($last=="06") $last = " июня "; 
        if ($last=="07") $last = " июля "; 
        if ($last=="08") $last = " августа "; 
        if ($last=="09") $last = " сентября "; 
        if ($last=="10") $last = " октября "; 
        if ($last=="11") $last = " ноября "; 
        if ($last=="12") $last = " декабря "; 
    $lastpostdate = "$das[0]$last"; 

    } 
    $lastpost=$lastpostdate;
	if ($time!=false) $lastpost=$lastpost.", ".$lastposttime;  
    return $lastpost; 
}
/* Функция преобразования даты к нормальному виду для календаря */
function formatedpost_calendar($postdate, $time = true){ 
    $lastpostdate=date("d-m-Y", strtotime($postdate)); 
    $lastposttime=date("H:i", strtotime($postdate));     

    $das=explode("-", $lastpostdate); 
    if ($das[1]!="") {
    $last = $das[1]; 
        if ($last=="01") $last = "Январь"; 
        if ($last=="02") $last = "Февраль";  
        if ($last=="03") $last = "Март "; 
        if ($last=="04") $last = "Апрель "; 
        if ($last=="05") $last = "Май "; 
        if ($last=="06") $last = "Июнь "; 
        if ($last=="07") $last = "Июль "; 
        if ($last=="08") $last = "Август "; 
        if ($last=="09") $last = "Сентябрь "; 
        if ($last=="10") $last = "Октябрь "; 
        if ($last=="11") $last = "Ноябрь "; 
        if ($last=="12") $last = "Декабрь "; 
    $lastpostdate = "<p class='number'>$das[0]</p>$last$das[2]"; 

    } 
    $lastpost=$lastpostdate;
	if ($time!=false) $lastpost=$lastpost.", ".$lastposttime;  
    return $lastpost; 
}
/* Функция преобразования даты к нормальному виду */
function date_for_news($postdate, $time = true){ 
    $lastpostdate=date("d-m-Y", strtotime($postdate)); 
    $lastposttime=date("H:i", strtotime($postdate));     

    $das=explode("-", $lastpostdate); 
    if ($das[1]!="") {
    $last = $das[1]; 
        if ($last=="01") $last = "ЯНВ"; 
        if ($last=="02") $last = "ФЕВ";  
        if ($last=="03") $last = "МАРТ"; 
        if ($last=="04") $last = "АПР"; 
        if ($last=="05") $last = "МАЯ"; 
        if ($last=="06") $last = "ИЮНЬ"; 
        if ($last=="07") $last = "ИЮЛЬ"; 
        if ($last=="08") $last = "АВГ"; 
        if ($last=="09") $last = "СЕНТ"; 
        if ($last=="10") $last = "ОКТ"; 
        if ($last=="11") $last = "НОЯБ"; 
        if ($last=="12") $last = "ДЕК"; 
    $lastpostdate = "$last<br /><span>$das[0]</span><br />$das[2]"; 

    } 
    $lastpost=$lastpostdate;
    return $lastpost; 
}
/* Функция раскладывающая страницы по дереву */
function getTree(&$data, $parent)
{
	$out=array();
	if (!isset($data[$parent]))
		return $out;
	foreach ($data[$parent] as $row)
	{
		$chidls=getTree($data, $row['id_cat']);
		if ($chidls)
			$row['childs']=$chidls;
		$out[]=$row;
	}
	return $out;
}
function getTreeSmall(&$data, $parent)
{
	if (!isset($data[$parent]))
		return $out;
	foreach ($data[$parent] as $row)
	{
		$out.="'".$row['id_cat']."',";
		$out.=getTreeSmall($data, $row['id_cat']);
		if ($chidls) $row['childs']=$chidls;
	}
	return $out;
}
/* Функция возвращает список с подстраницами */
function cat_echo($data)
{ 
echo '<ul>';
foreach ($data as $k=>$v)
    {
        echo '<li><a href="/'.$v['alias'].'/">'.$v['name_cat'].'</a>';
        if (isset($v['childs'])) cat_echo($v['childs']);
		echo "</li>";
    }
echo "</ul>";
}
function sub_pages($data)
{ 
$sub = '<ul>';
foreach ($data as $k=>$v)
    {
        $sub.='<li><a href="/content/'.$v['anchor'].'.html">'.$v['name'].'</a>';
        if (isset($v['childs'])) sub_pages($v['childs']);
		$sub.="</li>";
    }
$sub.= "</ul>";
return $sub;
}
function in_multiarray($elem, $array)
     {
         $top = sizeof($array) - 1;
         $bottom = 0;
         while($bottom <= $top)
         {
             if($array[$bottom] == $elem)
                 return true;
             else 
                 if(is_array($array[$bottom]))
                     if(in_multiarray($elem, ($array[$bottom])))
                         return true;
                     
             $bottom++;
         }        
         return false;
     }
/* Функция возвращает список с категориями в древовидном виде */
function mod_catalog_cat($data, $current, $count, $id_cat = 0, $parent = 0)
{ 
if (!@$current) $current = array();

if ($count==0) $cat.='<ul id="services">'; else $cat.='<ul>';
foreach ($data as $k=>$v)
    {
		if (in_array($v['id_cat'],$current) or in_array($v['parent'],$current)) $open = ' class="cat_open"'; else $open = '';
		
		
       	$cat.='<li>';
		 if (isset($v['childs'])) $cat.='<span'.$open.'></span>';
		$cat.='<a href="/catalog/'.$v['id_cat'].'-'.$v['anchor_cat'].'/">'.$v['name_cat'].'</a>'; 
        if (isset($v['childs'])) $cat.=mod_catalog_cat($v['childs'],$current, 1, $v['id_cat'], $v['parent']);
		$cat.='</li>';
    }
$cat.='</ul>';

return $cat;
}
function cat_view_index ($data, $num, $limit, $count){
$cat='';
$step_count=0;
foreach ($data as $k=>$v)
    {
		
		if (($step_count==$limit)&&($limit!=0)) break;
		if ($num==0){
		($count[$v['id_cat']]!='') ? $cnt=$count[$v['id_cat']] : $cnt=0;
       	$cat.='<li><div>';
		$cat.='<img src="/upload/cat/'.$v["img_cat"].'.jpg" alt=""><a href="/catalog/'.$v['id_cat'].'-'.$v['anchor_cat'].'/" class="cat_name">'.$v['name_cat'].'</a><div class="product_number">Товаров: '.$cnt.'</div>';
        if (isset($v['childs'])) {
			$cat.='<ul>';
			$cat.=cat_view_index($v['childs'], 1, 0, $count);
			$cat.='</ul>';}
		$cat.='</div></li>';		
		}
		else {
			$cat.='<li><a href="/catalog/'.$v['id_cat'].'-'.$v['anchor_cat'].'/">'.$v['name_cat'].'</a></li>';
		}
		$step_count++;
    }
return $cat;
}

function cat($id){ // создаем функцию, которая вытащит из базы все категории и подкатегории по переданному ей id
$return=array(); // создаем массив, в который будем записывать категории
$m=mysql_query('SELECT id_cat,name_cat,anchor_cat,parent FROM mod_catalog_cat WHERE id_cat='.$id); // вытаскиваем имя категории, для переданного id
	if(mysql_num_rows($m)){ // проверяем, есть ли она в базе
	$m=mysql_fetch_assoc($m); // если есть, создаем массив
	$return[]=array($m['id_cat'],$m['name_cat'],$m['anchor_cat']); // заносим в массив id категории как ключ массива,и имя категории как значение массива
		if($m['parent']){ // проверяем, есть ли у категории доминирующие категории
		$return=array_merge($return,cat($m['parent'])); // если есть, то запускаем функцию заново, но уже с id домирующей категории, и полученный результат соединяем с уже имеющимся
		}
	}
	return $return;
}
function cat_print($id){ // основная функция, которую мы будем применять
$print=cat($id); // получаем список категорий в виде массва
$print=array_reverse($print); // переворачиваем массив, чтобы категории выводились начиная с главных
$what = '<span class="path">';
$what.= '<a href="/">Главная</a> / ';
for($i=0,$s=sizeof($print);$i<$s;$i++){ // выводим их в цикле
$what.= '<a href="/catalog/'.$print[$i][0].'-'.$print[$i][2].'/">'.$print[$i][1].'</a> / ';
}
return $what;
}

function cat_print_forcat($id){ // основная функция, которую мы будем применять для категорий
$print=cat($id); // получаем список категорий в виде массва
$print=array_reverse($print); // переворачиваем массив, чтобы категории выводились начиная с главных
$what = '<span class="path">';
$what.= '<a href="/">Главная</a> / ';
for($i=0,$s=sizeof($print)-1;$i<$s;$i++){ // выводим их в цикле
$what.= '<a href="/catalog/'.$print[$i][0].'-'.$print[$i][2].'/">'.$print[$i][1].'</a> / ';
}
$what.= '<span>'.$print[sizeof($print)-1][1].'</span>';
return $what;
}

/* Функция удаления директории с вложеными в нее файлами */
function clear($dir)  
{  
    $opdir=opendir($dir);  
    while ($a = readdir($opdir))  
    {  
        if ($a != "." && $a != ".." && !is_dir($dir .'/'.$a))    
        {unlink($dir .'/'.$a);}  
        elseif($a != "." && $a != ".." && is_dir($dir .'/'.$a))  
        {clear($dir .'/'.$a);}  
    }  
 closedir ($opdir);  
 if(rmdir($dir)){return TRUE;}else{return FALSE;}  
} 
function cat_select($data,$par,$style)
{ 
foreach ($data as $k=>$v)
    {
		echo '<option value="'.$v['id_cat'].'"';
		if ($v['id_cat']==$par) { echo " selected";}
		echo '>'.$style.$v['name_cat'].'</option>';
        if (isset($v['childs'])) cat_select($v['childs'],$par,$style."---");
    }
}
/* Функция шифровки пароля в md5 с солью */
function pass_solt($pass,$salt='tato86')  
{  
 $spec=array('~','!','@','#','$','%','^','&','*','?');  
 $crypted=md5(md5($salt).md5($pass));  
 $c_text=md5($pass);  
 for ($i=0;$i<strlen($crypted);$i++)  
 {  
 if (ord($c_text[$i])>=48 and ord($c_text[$i])<=57){  
  $temp.=$spec[$c_text[$i]];  
 } elseif(ord($c_text[$i])>=97 and ord($c_text[$i])<=100){  
  $temp.=strtoupper($crypted[$i]);  
 } else {  
  $temp.=$crypted[$i];  
 }  
 }  
 return md5($temp);  
} 
 
/* Функция определения расширения файла */
function what_ras($r,$t=null) 
{ 
$f=explode('.',$r); 
return strtolower($f[count($f)-1-$t]); 
} 

/* Транслит из анг в руский и наоборот */
function trans($str, $direction = 'ru_en')
{
        $ru = array('а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я', ' ', '/', '-', '.', ',', 
'"','?','!','#','№','\'','%','*','А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я','(',')','«','»',':');
        $en = array('a','b','v','g','d','e','e','zh','z','i','i','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','','y','','e','yu','ya','_', '_','_','','_','','','','','','','','','a','b','v','g','d','e','e','zh','z','i','i','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','','y','','e','yu','ya','','','_','_','_');
        if ($direction == 'en_ru')
                return str_replace($en, $ru, strtolower($str));
        else
                return str_replace($ru, $en, strtolower($str));
}
/* Функция определения реального IP */
function RealIP() 
{ 

   if( $_SERVER['HTTP_X_FORWARDED_FOR'] != '' ) 
   { 
      $client_ip = 
         ( !empty($_SERVER['REMOTE_ADDR']) ) ? 
            $_SERVER['REMOTE_ADDR'] 
            : 
            ( ( !empty($_ENV['REMOTE_ADDR']) ) ? 
               $_ENV['REMOTE_ADDR'] 
               : 
               "unknown" ); 
      $entries = split('[, ]', $_SERVER['HTTP_X_FORWARDED_FOR']); 

      reset($entries); 
      while (list(, $entry) = each($entries)) 
      { 
         $entry = trim($entry); 
         if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list) ) 
         { 

            $private_ip = array( 
                  '/^0\./', 
                  '/^127\.0\.0\.1/', 
                  '/^192\.168\..*/', 
                  '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/', 
                  '/^10\..*/'); 

            $found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]); 

            if ($client_ip != $found_ip) 
            { 
               $client_ip = $found_ip; 
               break; 
            } 
         } 
      } 
   } 
   else 
   { 
      $client_ip = 
         ( !empty($_SERVER['REMOTE_ADDR']) ) ? 
            $_SERVER['REMOTE_ADDR'] 
            : 
            ( ( !empty($_ENV['REMOTE_ADDR']) ) ? 
               $_ENV['REMOTE_ADDR'] 
               : 
               "unknown" ); 
   } 

   return $client_ip; 
} 
# Функция пробежки по массиву
function in_my_array($data,$par)
{ 
	$mychoice = '';
	foreach ($data as $k=>$v)
    {
		if (is_array($v)) { in_my_array($v,$par); }
		else 
			{
			if ($v==$par) $mychoice=$data["COUNT(mod_company.id_company)"];
			
			}
    }
	echo $mychoice;
}
# Функция для генерации случайной строки 
function generateCode($length=6) { 
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789"; 
    $code = ""; 
    $clen = strlen($chars) - 1;   
    while (strlen($code) < $length) { 
            $code .= $chars[mt_rand(0,$clen)];   
    } 
    return $code; 
}
# Функция проверки емаил на валидность
function is_email($email){
  if (function_exists("filter_var")){
    $s=filter_var($email, FILTER_VALIDATE_EMAIL);
    return !empty($s);
  }
  $p = '/^[a-z0-9!#$%&*+-=?^_`{|}~]+(\.[a-z0-9!#$%&*+-=?^_`{|}~]+)*';
  $p.= '@([-a-z0-9]+\.)+([a-z]{2,3}';
  $p.= '|info|arpa|aero|coop|name|museum|mobi)$/ix';
  return preg_match($p, $email);
}
# Функции по созданию "тумбочек"
function imgResize($src, $dest, $width, $height, $rgb = 0xFFFFFF, $quality = 100) {		//$src - Источник, $dest - картинка привью(Thumbnail), $width -ширина, $height -высота $rgb - фоновый цвет, накладываемый под картинкой, $quality - качество jpeg-каринок 
	if (! file_exists ( $src )) {
		return false;
	}
	$size = getimagesize ( $src );
	if ($size === false) {
		return false;
	}
	 $format = strtolower(substr($size['mime'], strpos($size['mime'], '/')+1));
	 $icfunc = "imagecreatefrom" . $format;
	if (! function_exists ( $icfunc )){
		return false;
	}
	
	$x_ratio = $width / $size [0];
	$y_ratio = $height / $size [1];
	
	$ratio = min ( $x_ratio, $y_ratio );
	$use_x_ratio = ($x_ratio == $ratio);
	
	$new_width = $use_x_ratio ? $width : floor ( $size [0] * $ratio );
	$new_height = ! $use_x_ratio ? $height : floor ( $size [1] * $ratio );
	$new_left = $use_x_ratio ? 0 : floor ( ($width - $new_width) / 2 );
	$new_top = ! $use_x_ratio ? 0 : floor ( ($height - $new_height) / 2 );
	
	$isrc = $icfunc ( $src );
	$idest = imagecreatetruecolor ( $width, $height );
	
	imagefill ( $idest, 0, 0, $rgb );
	imagecopyresampled ( $idest, $isrc, $new_left, $new_top, 0, 0, $new_width, $new_height, $size [0], $size [1] );
	
	imagejpeg ( $idest, $dest, $quality );
	
	imagedestroy ( $isrc );
	imagedestroy ( $idest );
	
	return true;

}
function create_thumbnail($orig_fname, $thum_fname, $thumb_width=100, $thumb_height=100, $do_cut=false)
{
    $rgb = 0xFFFFFF;
    $quality = 80;
    $size = @getimagesize($orig_fname);
    $src_x = $src_y = 0;

    if( $size === false) return false;

    $format = strtolower(substr($size['mime'], strpos($size['mime'], '/')+1));
    $icfunc = "imagecreatefrom" . $format;
    if (!function_exists($icfunc)) return false;

    $orig_img = $icfunc($orig_fname);
    if (($size[0] <= $thumb_width) && ($size[1] <= $thumb_height))
    {
        // use original size
        $width  = $size['0'];
        $height = $size['1'];
    }
    else
    {
        $width  = $thumb_width;
        $height = $thumb_height;

        // calculate fit ratio
        $ratio_width  = $size['0'] / $thumb_width;
        $ratio_height = $size['1'] / $thumb_height;

        if ($ratio_width < $ratio_height)
        {
            if ($do_cut)
            {
                $src_y = ($size['1'] - $thumb_height * $ratio_width) / 2;
                $size['1'] = $thumb_height * $ratio_width;
            }
            else
            {
                $width  = $size['0'] / $ratio_height;
                $height = $thumb_height;
            }
        } else {
            if ($do_cut)
            {
                $src_x = ($size['0'] - $thumb_width * $ratio_height) / 2;
                $size['0'] = $thumb_width * $ratio_height;
            }
            else
            {
                $width  = $thumb_width;
                $height = $size['1'] / $ratio_width;
            }
        }
    }

    $thum_img = imagecreatetruecolor($width, $height);
    imagefill($thum_img, 0, 0, $rgb);
    imagecopyresampled($thum_img, $orig_img, 0, 0, $src_x, $src_y, $width, $height, $size[0], $size[1]);

    imagejpeg($thum_img, $thum_fname, $quality);
    flush();
    imagedestroy($orig_img);
    imagedestroy($thum_img);
    return true;
}
# класс наложения водяного знака
class watermark2
{
function create_watermark( $main_img_obj, $watermark_img_obj, $alpha_level = 100 )
{
$watermark_width = imagesx($watermark_img_obj);
$watermark_height = imagesy($watermark_img_obj);

$dest_x = imagesx($main_img_obj) - $watermark_width - 45;
$dest_y = imagesy($main_img_obj) - $watermark_height - 25;
imagecopymerge($main_img_obj, $watermark_img_obj, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height, $alpha_level);

return $main_img_obj;
}
}
# функция определения горизонтали и вертикали картинки
function horizorvert($image_path) {
	
   $image_ext = end(explode(".", $image_path));
   if ($image_ext == 'png')
   {
      $image_src = imagecreatefrompng($image_path);
   }
   else if ($image_ext == 'gif')
   {
      $image_src = imagecreatefromgif($image_path);
   }
   else
   {
      $image_src = imagecreatefromjpeg($image_path);
   }
   $width_src = imagesx($image_src);
   $height_src = imagesy($image_src);
   if ($width_src > $height_src)
   {
      return 1;
   }
   else
   {
      return 0;
   }
   imagedestroy($image_src);
}
# проверка на правильность введенного урл
function check_url($url) {
    if (preg_match("/^((www.)?([\w, -]+.)(com|net|org|info|biz|spb\.ru|msk\.ru|com\.ru|org\.ru|net\.ru|ru|su|us|bz|ws))$/", $url)) {
        return true;
    }
    return false;
}
function arraytofile($array, $filename = 0, $file = 0)

  {

    $level = 1;

    if($file == 0)

    {

      $level = 0;

      $file = fopen($filename, "w");

      if(!$file)

      {

        return false;

      }

      fwrite($file, "<" . "?\n\$_array = ");

    }

 

    $cnt = count($array);

    $i = 0;

    fwrite($file, "\narray(\n");

    foreach($array as $key => $value)

    {

      if($i++ != 0)

      {

        fwrite($file, ",\n");

      }

      if(is_array($array[$key]))

      {

        fwrite($file, "'$key' => ");

        arraytofile($array[$key], 0, $file);

      }

      else 

      {

        $value = addcslashes($value, "'"."\\\\");

        fwrite($file, str_repeat(' ', ($level + 1) * 2) . "'$key' => '$value'");

      }

    }

    fwrite($file, ")");

 

    if($level == 0)

    {

      fwrite($file, ";\n?".">");

      fclose($file);

      return true;

    }

  }
  
    function news_oc_date($date)
	{
		$month = array(
                    1 => 'янв', 
                    2 => 'фев', 
                    3 => 'мар', 
                    4 => 'апр', 
                    5 => 'мая', 
                    6 => 'июн', 
                    7 => 'июл', 
                    8 => 'авг', 
                    9 => 'сент', 
                    10 => 'окт', 
                    11 => 'нояб', 
                    12 => 'дек');
		$date = date_parse($date);
				
		$asd['day'] = $date['day'];
		$asd['month'] = $month[$date['month']];
		$asd['year'] = $date['year'];
		return $asd;
	}
	
	function watermark_text($oldimage_name, $new_image_name, $water_mark_text, $font_size){
	$water_mark_text=iconv("WINDOWS-1251", "UTF-8", $water_mark_text);
	//$water_mark_text.= ' '.$water_mark_text.' '.$water_mark_text.' '.$water_mark_text;
	// получаем размеры исходного изображения
	list($owidth,$oheight) = getimagesize($oldimage_name);
	// задаем размеры для выходного изображения		
	$width = $owidth;
	$height = $oheight;
	// создаем выходное изображение размерами, указанными выше
	$image = imagecreatetruecolor($width, $height);
	$image_src = imagecreatefromjpeg($oldimage_name);
	// наложение на выходное изображение, исходного
	imagecopyresampled($image, $image_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
	// задаем цвет для накладываемого текста
	$blue = imagecolorallocatealpha($image, 0, 0, 0, 100);
	// определяем позицию расположения водяного знака 
	//$c=sqrt($width*$width + $height*$height);
	//$angle=asin($height/$c)/0.0175;
	
	$pos_x = 5;
	$pos_y = $height-5;
	// наложение текста на выходное изображение	

	imagettftext($image, $font_size, $angle, $pos_x, $pos_y, $blue, "arial.ttf", $water_mark_text);
	// сохраняем выходное изображение, уже с водяным знаком в формате jpg и качеством 100
	imagejpeg($image, $new_image_name, 100);
	// уничтожаем изображения
	imagedestroy($image);
	unlink($oldimage_name);
	return true;
	}
	
	// функция набора ошибочно данных 
	function error_login() {
								mysql_query("DELETE FROM `error_login` WHERE UNIX_TIMESTAMP() - UNIX_TIMESTAMP(date) > 900");
								$res = mysql_query("SELECT `col` FROM `error_login` WHERE `ip`='".RealIP()."'");
								$lRes=mysql_fetch_assoc($res);
								if ($lRes['col'] > 2) 
								{
										//если ошибок больше двух, т.е три, то выдаем сообщение.
										$error = 1;
										
								}      
								else
								{
									$error = 0;

									$res = mysql_query("SELECT ip FROM error_login WHERE ip='".RealIP()."'");
									$lRes=mysql_fetch_assoc($res);
										if (RealIP() == $lRes[ip]) 
										{
											//mysql_query("UPDATE `error_login` SET `col`=col+1,`date`=NOW() WHERE `ip`='".RealIP()."'");
										}          
											else 
										{
											//mysql_query("INSERT INTO error_login (ip,date,col) VALUES  ('".RealIP()."',NOW(),'1')");
										}   
								}
		return $error;
	}
	
/* Функция преобразования даты к нормальному виду */
function news_date($postdate, $time = true){ 
    $lastpostdate=date("d-m-Y", strtotime($postdate)); 
    $lastposttime=date("H:i", strtotime($postdate));     

    $das=explode("-", $lastpostdate); 
    if ($das[1]!="") {
    $last = $das[1]; 
        if ($last=="01") $last = "янв"; 
        if ($last=="02") $last = "фев";  
        if ($last=="03") $last = "мар"; 
        if ($last=="04") $last = "апр"; 
        if ($last=="05") $last = "мая"; 
        if ($last=="06") $last = "июнь"; 
        if ($last=="07") $last = "июль"; 
        if ($last=="08") $last = "авг"; 
        if ($last=="09") $last = "сент"; 
        if ($last=="10") $last = "окт"; 
        if ($last=="11") $last = "нояб"; 
        if ($last=="12") $last = "дек"; 
    $lastpostdate = "$das[0] $last $das[2]"; 

    } 
    $lastpost=$lastpostdate;
    return $lastpost; 
}

function buildChildTree(&$data)
{
	$out=array();
	foreach($data as $parent=>$child)
	{
		foreach ($child as $v)
		{
			$out[$parent][]=(int)$v;
			$current=(int)$v;
			if ($data[$current]!=NULL) foreach ($data[$current] as $vv)
			{
				if ((!in_array($vv,$data[$parent]))&&(!in_array($vv,$out[$parent]))) $out[$parent][]=(int)$vv;
			}			
		}
	}
	
	return $out;
}
	function Suma($inn, $stripkop=false) {
	$nol = 'ноль';
	$str[100]= array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот', 'восемьсот','девятьсот');
	$str[11] = array('','десять','одинадцать','дванадцать','тринадцать', 'четырнадцать','пятнадцать','шестнадцать','семнадцать', 'восемнадцать','деватнадцать','двадцать');
	$str[10] = array('','десять','двадцать','тридцать','сорок','пятьдесят', 'шестьдесят','семьдесят','восемьдесят','девяносто');
	$sex = array(
	array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),// m
	array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять') // f
	);
	$forms = array(
	array('копейка',  'копейки',   'копеек',     1), // 10^-2
	array('рубль',    'рубля',     'рублей',     0), // 10^ 0
	array('тысяч',   'тысячи',    'тысяч',      1), // 10^ 3
	array('миллион',  'миллиона',  'миллионов',  0), // 10^ 6
	array('миллиард', 'миллиарда', 'миллиардов', 0), // 10^ 9
	array('триллион', 'триллиона', 'триллионов', 0), // 10^12
	);
	$out = $tmp = array();
	$tmp = explode('.', str_replace(',','.', $inn));
	$rub = number_format($tmp[0],0,'','-');
	if ($rub==0) $out[] = $nol;
	// нормализация копеек
	$kop = isset($tmp[1]) ? substr(str_pad($tmp[1], 2, '0', я),0,2) : '00';
	$segments = explode('-', $rub);
	$offset = sizeof($segments);
	if ((int)$rub==0) {
	$o[] = $nol;
	$o[] = morph(0, $forms[1][0],$forms[1][1],$forms[1][2]);
	}
	else {
	foreach ($segments as $k=>$lev) {
	$sexi= (int) $forms[$offset][3];
	$ri  = (int) $lev;
	if ($ri==0 && $offset>1) {
	$offset--;
	continue;
	}
	$ri = str_pad($ri, 3, '0', STR_PAD_LEFT);
	$r1 = (int)substr($ri,0,1);
	$r2 = (int)substr($ri,1,1);
	$r3 = (int)substr($ri,2,1);
	$r22= (int)$r2.$r3;
	if ($ri>99) $o[] = $str[100][$r1];
	if ($r22>20) {// >20
	$o[] = $str[10][$r2];
	$o[] = $sex[ $sexi ][$r3];
	}
	else { // <=20
	if ($r22>9) $o[] = $str[11][$r22-9]; // 10-20
	elseif($r22>0)  $o[] = $sex[ $sexi ][$r3]; // 1-9
	}
	$o[] = morph($ri, $forms[$offset][0],$forms[$offset][1],$forms[$offset][2]);
	$offset--;
	}
	}
	if (!$stripkop) {
	$o[] = $kop;
	$o[] = morph($kop,$forms[0][0],$forms[0][1],$forms[0][2]);
	}
	return preg_replace("/\s{2,}/",' ',implode(' ',$o));
	}
	
	function morph($n, $f1, $f2, $f5) {
	$n = abs($n) % 100;
	$n1= $n % 10;
	if ($n>10 && $n<20)	return $f5;
	if ($n1>1 && $n1<5)	return $f2;
	if ($n1==1)	 return $f1;
	return $f5;
}
function bukvica ($text)
{

$bukva = strtolower(substr($text, 0, 1));
	$ru = array('а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','э','ю','я');
    $en = array(
'А',
'Б',
'В',
'Г',
'Д',
'Е',
'Ё',
'Ж',
'З',
'И',
'Й',
'К',
'Л',
'М',
'Н',
'О',
'П',
'Р',
'С',
'Т',
'У',
'Ф',
'Х',
'Ц',
'Ч',
'Ш',
'Щ',
'Э',
'Ю',
'Я',
);
return str_replace($ru, $en, $bukva).substr($text, 1);
}
?>