<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/27/2015
 * Time: 3:42 PM
 */
header('Location: feed.php');
function getNewUrl()
{

    $giphy = new Giphy();
    $result = $giphy->random('internet');
    $img =  $result->data->image_original_url;
    return $img;
}
function getLine()
{
    return '$("#body").css("background", "url(\'' . getNewUrl() . '\')");
            $("#body").css("background-size", "cover");
    ';
}
?>
<!DOCTYPE HTML>
<HTML>
    <head>
        <title>westbvlb | Home</title>
        <link rel="stylesheet" type="text/css" href="styles.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
        <script src="beta/jquery.waypoints.min.js"></script>
        <script src="beta/shortcuts/sticky.min.js"></script>
        <script>
            function isScrolledIntoView(elem)
            {
                var $elem = $(elem);
                var $window = $(window);

                var docViewTop = $window.scrollTop();
                var docViewBottom = docViewTop + $window.height();

                var elemTop = $elem.offset().top;
                var elemBottom = elemTop + $elem.height();

                return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
            }
            setInterval(function() {
                <?php echo getLine(); ?>
            }, 100);

            $(document).ready(function(){
                var sticky = new Waypoint.Sticky({
                    element: $('.basic-sticky-example')[0]
                });
                var inview = new Waypoint.Inview({
                    element: $('#hello')[0],
                    enter: function(direction) {
                        notify('Enter triggered with direction ' + direction)
                    },
                    entered: function(direction) {
                        notify('Entered triggered with direction ' + direction)
                    },
                    exit: function(direction) {
                        notify('Exit triggered with direction ' + direction)
                    },
                    exited: function(direction) {
                        notify('Exited triggered with direction ' + direction)
                    }
                });

            });

        </script>

    </head>
    <body style="height: 100%; padding: 0px;" onscroll="" id="body">

    <div id="top"> </div>

    <div class="post_container">
        <div style="margin: 0px; padding: 1px; background-color: rgba(0,0,0,.8); color: white;">
        </div>
        <div class="post">
            <div style="margin-top: 5000px" id="hello"><h1>Hello World</h1></div>
        </div>
    </div>





    </body>
</HTML>
