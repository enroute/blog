<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
  
    <link rel="stylesheet" href="/res/css/common.css">
    <script type="text/javascript" src="/res/js/common.js"></script>
    <script type="text/javascript" src="/res/js/showdown/dist/showdown.min.js"></script>
    <script type="text/javascript" src="/res/js/showdown/table-extension-1.0.1/dist/showdown-table.min.js"></script>
</head>

<body>
<div id="content"></div>
</body>
<script type="text/javascript">
 function showMarkDown(text) {
 var converter = new showdown.Converter({extensions: ['table']});
     var html = converter.makeHtml(text);
     document.getElementById("content").innerHTML = html;
 }

 window.onload = function() {
     $.ajax({
         url:"{{URL}}",
         type:"get",
         success:function(res){
             showMarkDown(res);
         },
         error: function (XMLHttpRequest, textStatus, errorThrown) {
             console.log(XMLHttpRequest.status);
             console.log(XMLHttpRequest.readyState);
             console.log(textStatus);
         }
     });
 }
</script>
</html>
