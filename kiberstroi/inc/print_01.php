<?
if (!@$_GET["id"] or !@$_GET["key"]) die ("Access denied");
require_once($_SERVER['DOCUMENT_ROOT']."/mod/mod_config.php");
$filter = new filter;
$key = $filter->html_filter($_GET["key"]);  
$order = base64_decode($filter->html_filter($_GET["id"]));
$c = $filter->html_filter($_GET["c"]);

if (!empty($key) && !empty($order) && $c==base64_encode($_SESSION["hash"]."|".$_SESSION['access']))	// 								
	{								
					
					$Db->query="SELECT * FROM `mod_order` WHERE `id_order`='".$order."' LIMIT 1";
					$Db->query();
					$lRes=mysql_fetch_assoc($Db->lQueryResult);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//ru">

<HTML><HEAD><TITLE>����������� ��������� �� ��������. </TITLE>
<META http-equiv=Content-Type content="text/html; charset=windows-1251">
</HEAD>
<BODY onLoad="window.print();">
<STYLE type=text/css>.border {
	MARGIN-BOTTOM: -0.2em; PADDING-BOTTOM: 0px; BORDER-BOTTOM: black 1px solid
}
</STYLE>

<DIV align=center><p align=justify>
<b>��������� ������������� ��� ����������, ����������� <font color=red>�� ���������� ��</font></b>
<br>
<br>
</p><br>
<TABLE 
style="BORDER-RIGHT: black 1px solid; BORDER-TOP: black 1px solid; BORDER-LEFT: black 1px solid; ; WIDTH: 750px" 
cellSpacing=0 cellPadding=0 border=0>
  <TBODY>
  <TR vAlign=top>
    <TD 
    style="PADDING-RIGHT: 5px; PADDING-LEFT: 5px; FONT-SIZE: 11pt; PADDING-BOTTOM: 5px; PADDING-TOP: 5px" 
    align=middle>
      <DIV>���������</DIV>
      <DIV style="MARGIN-TOP: 12em">������</DIV></TD>
    <TD style="BORDER-RIGHT: black 1px solid" width=1 bgColor=black 
      height=250><DIV style="WIDTH: 1px; HEIGHT: 250px"><IMG height=1 
      src="����2.files/black.gif" width=1></DIV></TD>
    <TD 
    style="PADDING-RIGHT: 5px; PADDING-LEFT: 5px; FONT-SIZE: 10pt; PADDING-BOTTOM: 5px; PADDING-TOP: 5px">
      <DIV style="FONT-SIZE: 6pt; FLOAT: right">����� ���-4</DIV><BR clear=all>
      <DIV class=border style="FONT-SIZE: 11pt" align=center><?=$config["order"]["compname"]?><br><small><i>(� ��������� ��������� ��� � ��������� ������������ ���������� ��������� ��������� ��� ����������!!!) </i></small><BR>���&nbsp;&nbsp;<?=$config["order"]["inn"]?> &nbsp;&nbsp;&nbsp;���&nbsp;&nbsp;<?=$config["order"]["kpp"]?><BR></DIV>
      <DIV style="FONT-SIZE: 7pt; PADDING-TOP: 1px" align=center>���������� 
      �������</DIV>
      <DIV class=border style="FONT-SIZE: 10pt" align=center><?=$config["order"]["bankname"]?></DIV>
      <DIV style="FONT-SIZE: 7pt; PADDING-TOP: 1px" align=center>������������ �����</DIV>
      <DIV class=border style="FONT-SIZE: 11pt" align=center>�/��&nbsp;&nbsp;<?=$config["order"]["schet"]?>&nbsp;&nbsp;&nbsp;�/��&nbsp;&nbsp;<?=$config["order"]["korschet"]?>&nbsp;&nbsp;&nbsp;���&nbsp;&nbsp;<?=$config["order"]["bik"]?></DIV>
      <DIV style="FONT-SIZE: 7pt; PADDING-TOP: 1px" align=center>����� ����� � ��������� ����������</DIV>
      <BR clear=all>
            <DIV class=border><?=$lRes["name"]?></DIV>
      <DIV style="FONT-SIZE: 7pt; PADDING-TOP: 1px" align=center>�.�.�. �����������</DIV>
<DIV class=border><?=$lRes["adress"]?></DIV>
      <DIV style="FONT-SIZE: 7pt; PADDING-TOP: 1px" align=center>����� �����������</DIV><br>
      <DIV class=border style="FONT-SIZE: 12pt" align=center>������ ������ �� ������ � <?=$lRes["id_order"]?></DIV>
      <DIV style="FONT-SIZE: 7pt; PADDING-TOP: 1px" align=center>������������ ������� (����� ������ ������)</DIV>
      <DIV align=right><SPAN style="FONT-SIZE: 10pt">�����&nbsp;�������&nbsp;</SPAN><SPAN 
      class=border><I><?=$lRes["summ"]?> ���.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</I></SPAN> </DIV>
      <DIV align=right><SPAN style="FONT-SIZE: 10pt">����&nbsp;�������&nbsp;</SPAN><SPAN 
      class=border><I>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</I></SPAN> </DIV>
      <TABLE style="HEIGHT: 45px" cellSpacing=0 cellPadding=10 width=330 
      align=right border=0>
        </TABLE><BR>
        <DIV align=left><SPAN style="FONT-SIZE: 10pt">�������&nbsp;�����������:&nbsp;</SPAN><SPAN class=border><I>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</I></SPAN> </DIV>
      </TD></TR>
  <TR>
    <TD style="BORDER-TOP: black 1px solid" align=right width=190 
      bgColor=black><DIV style="WIDTH: 190px; HEIGHT: 1px"><IMG height=1 
      src="����2.files/black.gif" width=1></DIV></TD>
    <TD><IMG height=1 src="����2.files/black.gif" width=1></TD>
    <TD style="BORDER-TOP: black 1px solid" bgColor=black>
      <DIV style="WIDTH: 200px; HEIGHT: 1px"><IMG height=1 
      src="����2.files/black.gif" width=1></DIV></TD></TR>
  <TR>
    <TD 
    style="PADDING-RIGHT: 5px; PADDING-LEFT: 5px; FONT-SIZE: 11pt; PADDING-BOTTOM: 5px; PADDING-TOP: 5px" 
    vAlign=bottom align=middle>
      <DIV>���������</DIV>
      <DIV style="MARGIN-TOP: 1em">������</DIV></TD>
    <TD style="BORDER-RIGHT: black 1px solid" width=1 bgColor=black 
      height=250><DIV style="WIDTH: 1px; HEIGHT: 250px"><IMG height=1 
      src="����2.files/black.gif" width=1></DIV></TD>
    <TD 
    style="PADDING-RIGHT: 5px; PADDING-LEFT: 5px; FONT-SIZE: 10pt; PADDING-BOTTOM: 5px; PADDING-TOP: 5px">
      <DIV style="FONT-SIZE: 6pt; FLOAT: right">����� ���-4</DIV><BR clear=all>
      <DIV class=border style="FONT-SIZE: 11pt" align=center><?=$config["order"]["compname"]?><br><small><i>(� ��������� ��������� ��� � ��������� ������������ ���������� ��������� ��������� ��� ����������!!!) </i></small><BR>���&nbsp;&nbsp;<?=$config["order"]["inn"]?> &nbsp;&nbsp;&nbsp;���&nbsp;&nbsp;<?=$config["order"]["kpp"]?><BR></DIV>
      <DIV style="FONT-SIZE: 7pt; PADDING-TOP: 1px" align=center>���������� 
      �������</DIV>
      <DIV class=border style="FONT-SIZE: 10pt" align=center><?=$config["order"]["bankname"]?></DIV>
      <DIV style="FONT-SIZE: 7pt; PADDING-TOP: 1px" align=center>������������ �����</DIV>
      <DIV class=border style="FONT-SIZE: 11pt" align=center>�/��&nbsp;&nbsp;<?=$config["order"]["schet"]?>&nbsp;&nbsp;&nbsp;�/��&nbsp;&nbsp;<?=$config["order"]["korschet"]?>&nbsp;&nbsp;&nbsp;���&nbsp;&nbsp;<?=$config["order"]["bik"]?></DIV>
      <DIV style="FONT-SIZE: 7pt; PADDING-TOP: 1px" align=center>����� ����� � ��������� ����������</DIV>
      <BR clear=all>
            <DIV class=border><?=$lRes["name"]?></DIV>
      <DIV style="FONT-SIZE: 7pt; PADDING-TOP: 1px" align=center>�.�.�. �����������</DIV>
<DIV class=border><?=$lRes["adress"]?></DIV>
      <DIV style="FONT-SIZE: 7pt; PADDING-TOP: 1px" align=center>����� �����������</DIV><br>
      <DIV class=border style="FONT-SIZE: 12pt" align=center>������ ������ �� ������ � <?=$lRes["id_order"]?></DIV>
      <DIV style="FONT-SIZE: 7pt; PADDING-TOP: 1px" align=center>������������ ������� (����� ������ ������)</DIV>
      <DIV align=right><SPAN style="FONT-SIZE: 10pt">�����&nbsp;�������&nbsp;</SPAN><SPAN 
      class=border><I><?=$lRes["summ"]?> ���.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</I></SPAN> </DIV>
      <DIV align=right><SPAN style="FONT-SIZE: 10pt">����&nbsp;�������&nbsp;</SPAN><SPAN 
      class=border><I>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</I></SPAN> </DIV>
      </TD></TR>
  <TR>
    <TD style="BORDER-TOP: black 1px solid" align=right width=190 
      bgColor=black><DIV style="WIDTH: 190px; HEIGHT: 1px"><IMG height=1 
      src="����2.files/black.gif" width=1></DIV></TD>
    <TD><IMG height=1 src="����2.files/black.gif" width=1></TD>
    <TD style="BORDER-TOP: black 1px solid" bgColor=black>
      <DIV style="WIDTH: 200px; HEIGHT: 1px"><IMG height=1 
      src="����2.files/black.gif" width=1></DIV></TD></TR>
      </TBODY></TABLE>
            <BR><BR>
<b><i>������� �� �������!</i><br>      
      
      </DIV>
      
      
      <BR><BR></BODY></HTML>

<? 
	}
	else echo '�������� ���������.';
	?>