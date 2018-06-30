<!DOCTYPE html>
<html>
<head>
	<title>fancyBox - iframe demo</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style type="text/css">
body {
	background: url(images/bg.png);
	font-family: "Myriad Pro", Arial, Tahoma;
	color: #333333;
	margin: 0;
	padding:0;
}
h1 {
 font-family: font-family: "Myriad Pro", Arial, Tahoma;
 color: #fff;
 font-size:16px;
 padding: 10px;
 background:#54041D;
 text-align:center;
 margin: auto auto 10px;
 width: 300px;
}
p.item {
	background-color: #E8E8E8;
    font-family: "Myriad Pro", Arial, Tahoma;
	font-size:14px;
	color: #54041D;
	padding: 5px;
	margin: 3px 10px;
}
.total {
	background-color: #E4F0E1;
	border: #E4F0E1 1px solid;
    font-family: "Myriad Pro", Arial, Tahoma;
	font-size:14px;
	color:#54041D;
	padding: 5px;
	margin: 3px 10px;
}
</style>
</head>
<body>
	<h1>Lista de compras</h1>

	<p class="item">O Melhor Bacalhau do Mundo</p>
<p class="item">O Melhor Bacalhau do Mundo</p>
<p class="item">O Melhor Bacalhau do Mundo</p>
<p>&nbsp;</p>
<p>Valor total: <span class="total"> R$ 120.00 </span></p>
<p><a href="http://www.fullwiz.com.br" target="_blank" onclick="javascript:parent.jQuery.fancybox.close();"><img src="images/comprar.gif" width="211" height="35" alt="Comprar"></a><br>
  *Você será redirecionado ao nosso E-commerce.
</p>
</body>
</html>