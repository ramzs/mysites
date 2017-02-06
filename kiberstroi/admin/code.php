<? 
require_once($_SERVER['DOCUMENT_ROOT']."/mod/mod_config.php");
$Db->query="SELECT `id_goods`,`code`,`name_goods` FROM `mod_catalog`";
$Db->query();
while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) $data[$lRes['id_goods']] = $lRes['code'];
foreach ($data as $key=>$value) if (count(array_keys($data, $value)) > 1) $error_code[]= $value;
	$Result = array();
    foreach ($error_code as $key => $value) {
        if ($value != '')
            $Result[] = $value;
    }
echo "Дубли кодов. Всего ".count($Result)."<br /><br />";
foreach ($Result as $key => $value) echo $value."<br />";
?>