<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <body>
        <div id="countdown">3 seconds until closing</div>
        <script>
            var timeleft = 2;
            var downloadTimer = setInterval(function(){
                if(timeleft <= 0){
                    clearInterval(downloadTimer);
                    window.close();
                } else {
                    document.getElementById("countdown").innerHTML = timeleft + " seconds until closing";
                }
                timeleft -= 1;
            }, 1000);
        </script>
    </body>
</html>
