function newContent( link, target) {
/*
 link - URL ����� ������������ ��������
 target - DIV � ������� �� ���������� �������
 */
 
var contaner = document.getElementById(target);
 contaner.innerHTML = '<br /><br /><center><img src="/images/loader.gif" /></center><br /><br />';
var resource = getRequest();
	  
if( resource )
 {
 resource.open('get', link);
 resource.onreadystatechange = function ()
 {
/*�������� ��������, ����������� ������� ��������� �������� ����������*/
if(resource.readyState == 4)
 {
 contaner.innerHTML = resource.responseText;
 }
 }
 resource.send(null);
 }
else
 {
document.location = link;
 }
}
/* ������� ��� ��������� ������ ��� ������ � ���������� */
function getRequest(){
try { return new XMLHttpRequest() }
catch(e)
 {
try { return new ActiveXObject('Msxml2.XMLHTTP') }
catch(e)
 {
try { return new ActiveXObject('Microsoft.XMLHTTP') }
catch(e) { return null; }z
 }
 }
}

