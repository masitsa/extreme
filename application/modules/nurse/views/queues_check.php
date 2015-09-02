
<div id="wrapper">
    <div id="content"></div>
    <img src="loading.gif" id="loading" alt="loading" style="display:none;" />
</div>
 <button onclick="playSound1()">Sound 1</button><br />
<button id="stop_sound" onclick="playSound2()">Stop sound</button><br />

<script type="text/javascript">
var audio1 = document.getElementById('sound1');
var audio2 = document.getElementById('sound2');
function playSound1(){
if (audio1.paused !== true){
    audio1.pause();
    }
else{
    audio1.play();
    }
}
function playSound2(){
    audio1.pause();
   
}
</script>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script>
(function($)
{
    $(document).ready(function()
    {
        $.ajaxSetup(
        {
            cache: false,
            beforeSend: function() {
                $('#content').hide();
                $('#loading').show();
            },
            complete: function() {
                $('#loading').hide();
                $('#content').show();
            },
            success: function() {
                $('#loading').hide();
                $('#content').show();
            }
        });
        var $container = $("#content");
        $container.load("rss-feed-data.php");
        var refreshId = setInterval(function()
        {
            $container.load('rss-feed-data.php');
        }, 9000);
    });
})(jQuery);
</script>