
<?php
session_start();
	include("check.php"); 
	include("style.php");
	
function get_all($sql,$result_type = MYSQL_ASSOC) {
	global $mysqli;
	//echo $sql;
	mysqli_query($mysqli,"set names utf8");
	$query = mysqli_query($mysqli,$sql) ;
	$i = 0;
	$rt = array();
	while($row = mysqli_fetch_assoc($query)) {
		$rt[$i]=$row;
		$i++;
	}
	return $rt;
}
if(isset($_GET['id']) && !empty($_GET['id'])){
	$sql = "delete from wraith_products_contents where id=$_GET[id]";
	mysqli_query($mysqli,$sql);
}
$a=@$_SESSION['sscode'];
$sscode = mt_rand(0,1000000);
$_SESSION['sscode'] = $sscode;
if(@$_POST['originator'] == $a ){
	if(isset($_POST['pid']) && !empty($_POST['text'])){
		$pid = $_POST['pid'];
		$content = trim($_POST['text']);
		
		$sql = "insert into wraith_products_contents(pid,content) values($pid,'$content')";;
		mysqli_query($mysqli,"set names utf8");
		if(!mysqli_query($mysqli,$sql)) echo "<script>alert('添加失败')</script>";
	}
}
if(isset($_GET['pid']) && !empty($_GET['content'])){
	$pid = $_GET['pid'];
	$content = trim($_GET['content']);
	
	$sql = "update wraith_products_contents set content='$content' where id='$pid'";;
	mysqli_query($mysqli,"set names utf8");
	if(!mysqli_query($mysqli,$sql)) echo "<script>alert('修改失败')</script>";
}
$result = get_all("select id,sp_number,message from wraith_products");
$results = get_all("select * from wraith_products_contents");
echo "<table>";
foreach($result as $k=>$v){
	
	echo "<tr><td width='15%'>".$v['sp_number'].'+'.$v['message']."&nbsp;<a id='add' _id='".$v['id']."' href='javascript:void(0)'>添加</a></td><td>";
	echo '<table width=100% border="1" cellpadding="3" cellspacing="0"  align="center" style="border-color:white ;">';
	foreach($results as $vs){
		
		if($vs['pid'] == $v['id']){
			echo "<tr sytle='border: 1px solid #ED9F9F;'><td onclick='edit(this,".$vs['id'].")'>".$vs['content']."</td><td width='10%'><a id='del' _id='".$vs['id']."' href='javascript:void(0)'>删除</a></td></tr>";
		}
	}
	echo "</table>";
}
echo "</td></tr></table>";
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>
<script>
$('#del').live("click", function(){	
	var id = $(this).attr('_id');
	//var del = $(this);
	if(confirm('确实要删除该内容吗?')=="1"){
	 $.get("product_contents.php", { id: id },
	  function(data){
		// del.parent().parent().remove();
	  });
	}
	$(this).parent().parent().remove();
});
$('#add').live("click", function(){

	$('#adds').css("display","block");
	$('#bg').css("display","block");
	$('#pid').val($(this).attr('_id'));
});
$('#addss').live("click", function(){
	$('#adds').css("display","none");
});
$('#quxiao').live("click", function(){
	$('#adds').css("display","none");
	$('#bg').css("display","none");
});
function edit(obj,id){
  var tag = obj.firstChild.tagName;
  if(typeof(tag) != "undefined" && tag.toLowerCase() == "input"){
	return;
  }
  var org = obj.innerHTML;
  var txt = document.createElement("INPUT");
  txt.value = org;
  txt.style.width = (obj.offsetWidth + 20) + "px" ;
  obj.innerHTML = "";
  obj.appendChild(txt);
  txt.focus();
  txt.onblur = function(){
	var tt = txt.value;
	obj.removeChild(txt);
	obj.innerHTML = tt;
	$.get("product_contents.php", { pid: id,content:tt },
	  function(data){

	  });
  }
}
</script>
<style type="text/css">
/*弹出层的STYLE*/
html,body {height:100%; margin:0px; font-size:12px;}
#adds {
background-color: #A9A9A9;
text-align: center;
line-height: 40px;
font-size: 12px;
font-weight: bold;
z-index:99;
width: 300px;
height: 140px;
left:50%;/*FF IE7*/
top: 50%;/*FF IE7*/
margin-left:-150px!important;/*FF IE7 该值为本身宽的一半 */
margin-top:-60px!important;/*FF IE7 该值为本身高的一半*/
margin-top:0px;
position:fixed!important;/*FF IE7*/
position:absolute;/*IE6*/
_top:       expression(eval(document.compatMode &&
            document.compatMode=='CSS1Compat') ?
            documentElement.scrollTop + (document.documentElement.clientHeight-this.offsetHeight)/2 :/*IE6*/
            document.body.scrollTop + (document.body.clientHeight - this.clientHeight)/2);/*IE5 IE5.5*/

}

.bg {
background-color: #ccc;
width: 100%;
height: 100%;
left:0;
top:0;/*FF IE7*/
filter:alpha(opacity=50);/*IE*/
opacity:0.5;/*FF*/
z-index:1;

position:fixed!important;/*FF IE7*/
position:absolute;/*IE6*/

_top:       expression(eval(document.compatMode &&
            document.compatMode=='CSS1Compat') ?
            documentElement.scrollTop + (document.documentElement.clientHeight-this.offsetHeight)/2 :/*IE6*/
            document.body.scrollTop + (document.body.clientHeight - this.clientHeight)/2);/*IE5 IE5.5*/

}
</style>
<DIV id="adds" style="display:none;" align='center'>
	<form action="product_contents.php" method="post" name="myform">
		<textarea NAME='text' cols='39' rows='6'></textarea>
		<br/>
		<input type='hidden' id='pid' name='pid' value=''>
		<input type="hidden" name="originator" value="<?php echo $sscode;?>">
		<input type='button' id='quxiao' value='取消'>
		<input type='submit' id='addss' value='提交'>
	</form>
</DIV>
<div id="bg" class="bg" style="display:none;"></div>