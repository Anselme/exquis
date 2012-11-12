<?php
$footer = <<<FOOTER
            </div><!-- #effects -->
        </div> <!-- #main-content -->

        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
        <script type="text/javascript" src="/js/jquery.tagcloud.js"></script>
        <script type="text/javascript" src="/js/bootstrap.min.js"></script>
        <script>
          $.fn.tagcloud.defaults = {
            size: {start: 1, end: 3, unit: 'em'},
            color: {start: '#000', end: '#BAB'}
          };

          $(function () {
            $('#cloud_tags a').tagcloud();
          });

          $('#publier').popover({
            placement: 'right',
            trigger: 'hover',
            content: '... sera conservé en base et publié sur Twitter',
            title: 'Votre cadavre'
          });
      </script>
    </body>
</html>
FOOTER;
