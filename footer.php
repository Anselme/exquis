<?php
$footer = <<<FOOTER
            </div><!-- #effects -->
        </div> <!-- #main-content -->

        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
        <script type="text/javascript" src="/js/jquery.tagcloud.js"></script>
        <script type="text/javascript" src="/js/bootstrap.min.js"></script>
        <script>
          /* nuage de tags */
          $.fn.tagcloud.defaults = {
            size: {start: 1, end: 3, unit: 'em'},
            color: {start: '#000', end: '#BAB'}
          };

          $(function () {
            $('#cloud_tags a').tagcloud();

          /* po up on hover sur bouton publier */
          $('#publier').popover({
            placement: 'right',
            trigger: 'hover',
            content: '... sera conservé en base et publié sur Twitter',
            title: 'Votre cadavre'
          });

          /* animation du nuage de tag */
          function test(){

          $($('.moving_cloud').children()[0]).delay(5000).fadeOut(1000, function() {
            $($('.moving_cloud').children()[0]).detach();
          });

          $.ajax({
            data: {action: 'getNewCadavre'},
            type: 'POST',
            url: 'aiku.php',
            success: function(output) {
              new_cadavre = '<a href="/" rel="' + Math.floor( Math.random() * 12) + '" >'+output + '.</a> '
              $('.moving_cloud').append(new_cadavre).children('a:last').hide().delay(5000).fadeIn(5000, test);
              $('#cloud_tags a').tagcloud();
            }
          })
          }
test();

          });
      </script>
    </body>
</html>
FOOTER;
