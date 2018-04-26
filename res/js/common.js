// <script src="//code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
var head = document.getElementsByTagName('head')[0];
var script = document.createElement('script');
script.type = 'text/javascript';
script.src = "//code.jquery.com/jquery-3.2.1.min.js";

// Then bind the event to the callback function.
// There are several events for cross browser compatibility.
script.onreadystatechange = handler;
script.onload = handler;

// Fire the loading
head.appendChild(script);

function handler(){
    console.log('jquery added :)');
    $(function(){
        defaultFooterStyle = "margin-top:50px; text-align:center; color:#999; font-family:PingFangSC-Medium, Microsoft YaHei, sans-serif;font-size:14px;";
        $("body").append('<div id="footer" style="' + defaultFooterStyle + '"><a href="http://www.miitbeian.gov.cn/">粤ICP备18025838</a><span class="hspace20">&nbsp;&nbsp;&nbsp;&nbsp;</span>Copyright © <a href="/">ZTFUN.COM</a> 2018</div>');

        if($(document.body).height() < $(window).height()){
            $("#footer").attr("style", defaultFooterStyle + "position: fixed!important; bottom: 0px; left:50%; transform:translateX(-50%);");
        }
    });
}