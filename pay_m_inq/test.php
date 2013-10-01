<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<title>用jquery实现可编辑的表格(table)-php自学网</title> 
<script type="text/jscript" src="jquery.js"></script> 
<script type="text/jscript"> 
//简化的ready写法:页面加载完成时候调用 
$(function() { 
//将tbody内的偶数tr的背景颜色设置为#ECE9D8 
$("tbody tr:even").css("background-color", "#ECE9D8"); 
//将tbody内的偶数td设置为numTd 
var numTd = $("tbody td:even"); 
//给这些单元格注册鼠标点击的事件 
numTd.click(function() { 
//取点击到元素的jquery对象 
var tdObj = $(this); 
//如果点击的元素包含input控件则不执行click处理 
if (tdObj.children("input").length > 0) { 
return false; 
} 
//取td内容附值到text 
var text = tdObj.html(); 
//清空td中的内容 
tdObj.html(""); 
//创建一个文本框，去掉文本框的边框，设置文本框中的文字字体大小是16px 
//使文本框的宽度和td的宽度相同，设置文本框的背景色，需要将当前td中的内容放到文本框中 
//将文本框插入到td中 
var inputObj = $("<input type='text'>").css("border-width", "0") 
.css("font-size", "16px").width(tdObj.width()) 
.css("background-color", tdObj.css("background-color")) 
.val(text).appendTo(tdObj); 
//设置触发器先触发focus事件再触发select事件 
inputObj.trigger("focus").trigger("select"); 
//是文本框插入之后就被选中 
inputObj.click(function() { 
return false; 
}); 
//注册keyup事件 
inputObj.keyup(function(event) { 
//获取当前按下键盘的键值 
var keycode = event.which; 
//处理回车的情况 
if (keycode == 13) { 
//获取当当前文本框中的内容 
var inputtext = $(this).val(); 
//将td的内容修改成文本框中的内容 
tdObj.html(inputtext); 
} 
//处理esc的情况 
if (keycode == 27) { 
//将td中的内容还原成text 
tdObj.html(text); 
} 
}); 
}); 
}); 
</script> 
<style type="text/css"> 
table{ 
border: 1px solid black; 
border-collapse: collapse; 
width: 400px; 
} 
table td{ 
border: 1px solid black; 
width: 50%; 
} 
table th{ 
border: 1px solid black; 
width: 50%; 
} 
tbody th { 
background-color: #A3BAE9; 
} 
</style> 
</head> 
<body> 
<form id="form1"> 
<table> 
<thead> 
<tr> <th colspan="2"> 
鼠标点击表格项就可以编辑 
</th> </tr> 
</thead> 
<tr> 
<th> 学号 </th> 
<th> 姓名 </th> 
</tr> 
<tr> 
<td> 000001 </td> 
<td> 张三 </td> 
</tr> 
<tr> 
<td> 000002 </td> 
<td> 李四 </td> 
</tr> 
<tr> <td> 000003 </td> 
<td> 王五 </td> 
</tr> 
</table> 
</form> 
</body> 
</html> 