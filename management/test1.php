<!DOCTYPE html>
<html>
<head>
  <script type="text/javascript" src="jquery.js"></script>
</head>

<body>

  <div><p>Hello1</p></div>
  <div><p>1</p><p>2</p></div>

<script>
  $("p").parent(".selected").css("background", "yellow");
  alert($("p").parent("div:eq(0)").text());
</script>

</body>
</html>