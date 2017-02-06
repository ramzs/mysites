<?
  // ���������� ������� ��������� ������ 
  error_reporting(E_ALL & ~E_NOTICE);

  // ������ �����������
  $width = 150;
  // ������ �����������
  $height = 50;
  // ���������� �������� � ����
  $sign = 5;
  // �������� ���
  $code = "";
  
  // ���������� ������
  session_start();
  
  // �������, ������������ � ����
  $letters = array('1','2',
                   '3','4','5','6','7','8','9','0');
  // ���������� ��� RGB-�����
  $figures = array('50','70','90','110',
                   '130','150','170','190','210');
  
  // ������� ������ �����������
  $img = imagecreatetruecolor($width, $height);
  
  // �������� ��� ����� ������
  $fon = imagecolorallocate($img, 255, 255, 255);
  imagefill($img, 0, 0, $fon);
  
 
  // ����������� �������� ���
  for($i=0; $i<$sign; $i++)
  {
    //��������
    $h = 1;
    //������
    $color = imagecolorallocatealpha(
                    $img,
                    $figures[rand(0,count($figures)-1)],
                    $figures[rand(0,count($figures)-1)],
                    $figures[rand(0,count($figures)-1)],
                    rand(10,30)); 

    // ���������� ��������� ������
    $letter = $letters[rand(0,sizeof($letters)-1)];

    // ��������� ���������� ��� ������ �������
    if(empty($x)) $x = $width*0.08;
    else $x = $x + ($width*0.8)/$sign+rand(0,$width*0.01);

    if($h == rand(1,2)) $y = (($height*1)/4) + rand(0,$height*0.1);
    else $y = (($height*1)/4) - rand(0,$height*0.1);

    // ���������� ������ � ���������� $code
    $code .= $letter;
    // �������� ������� �������
    if($h == rand(0,1)) $letter = strtoupper($letter);
    // ������� ������ �� �����������
    imagestring($img, 6 ,$x, $y, $letter, $color);
  }
 
  // �������� �������� ��� � ������
  $_SESSION['code'] = $code;
 
  // ������� �����������
  header ("Content-type: image/jpeg"); 
  imagejpeg($img);
?>