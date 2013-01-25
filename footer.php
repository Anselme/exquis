<?php
$footer = <<<FOOTER
            </div><!-- #effects -->
        </div> <!-- #main-content -->


<div id="footer" class="">
    <div class="row">
    <div class="span1">
    <a href="https://twitter.com/MDQAngersCentre" class="" title="Compte Twitter de la Maison de Quartier Angers Centre">
    <img src="img/twitter.png" alt="Compte Twitter de la Maison de Quartier Angers Centre">
    </a>
    </div>
    <div class="span2 offset4">
    <a href="http://www.angers-centre-animation.fr/" class="thumbnail" title="Maison de Quartier Angers Centre">
    <img src="img/mdq.png" alt="Maison de Quartier Angers Centre">
    </a>
    </div>
    <div class="span1">
    <a href="http://www.caf.fr" class="thumbnail" title="Caisses d'Allocations Familiales">
    <img src="img/caf.jpg" alt="Caisses d'Allocations Familiales de l'Anjou">
    </a>
    </div>
    <div class="span1">
    <a href="http://www.angers.fr" class="thumbnail" title="Site de la Ville d'Angers">
    <img src="img/angers.jpg" alt="Logo de la Ville d'Angers">
    </a>
    </div>
    <div class="span1">
    <a href="http://www.printempsdespoetes.com/" class="thumbnail" title="15e printemps des poetes">
    <img src="img/pdp.jpg" alt="15e pritemps des poetes">
    </a>
    </div>
</div>



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
            content: '... sera conservé en base et publié sur <a href="https://twitter.com/MDQAngersCentre" target="_blank">twitter.com/MDQAngersCentre</a>',
            title: 'Votre cadavre'
          });

          /* po up on hover sur bouton retour home */
          $('#annuler').popover({
            placement: 'left',
            trigger: 'hover',
            content: "La phrase ne me convient pas, j'essaie encore",
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
