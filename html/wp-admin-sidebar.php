<?php
/*
  Plugin developed by wordpress.menorcadev.com
 */
if (!$sidebar_init)
      die("You can't access this file directly");

echo $this->getChangelog();
?>

<div class="postbox">
      <h3 class="hndle">Support this plugin</h3>
      <div class="inside" style="font-size: 90%;text-align: center;">
            <h3 style="background:none;border:0;box-shadow:none;"><a href="http://wordpress.org/extend/plugins/<?php echo $sidebar_plugin; ?>">Rate this plugin</a></h3>
            <h3 style="background:none;border:0;box-shadow:none;"><a href="http://wordpress.menorcadev.com/plugin/<?php echo $sidebar_plugin; ?>">Write your feedback</a></h3>
      </div>
</div>

<div class="postbox">
      <h3 class="hndle">Credits</h3>
      <div class="inside">
            <ul>
                  <li><a href="http://wordpress.menorcadev.com/plugin/<?php echo $sidebar_plugin; ?>/" target="_blank">Official plugin page</a></li>
                  <li><a href="http://wordpress.menorcadev.com" target="_blank">More interesting WordPress plugins</a></li>
                  <li>Developed by <a href="http://www.jordisalord.com" target="_blank">Jordi Salord</a> at</li>
            </ul>
      </div>
      <center>
            <iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fmenorcadev&amp;width=250&amp;height=70&amp;colorscheme=light&amp;show_faces=false&amp;border_color&amp;stream=false&amp;header=false&amp;appId=231319580216245" scrolling="no" frameborder="0" style="border:none;background:transparent; overflow:hidden; width:250px; height:70px;" allowTransparency="true"></iframe>
            
            <a href="https://twitter.com/menorcadev" class="twitter-follow-button" data-show-count="false" data-size="large">Follow @JordiSalord</a>
            
            <iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fgraficlab&amp;width=250&amp;height=70&amp;colorscheme=light&amp;show_faces=false&amp;border_color&amp;stream=false&amp;header=false&amp;appId=231319580216245" scrolling="no" frameborder="0" style="border:none;background:transparent; overflow:hidden; width:250px; height:70px;" allowTransparency="true"></iframe>
            
            <a href="https://twitter.com/graficlab" class="twitter-follow-button" data-show-count="false" data-size="large">Follow @JordiSalord</a>
            
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
            
            <a href="http://www.menorcadev.com" target="_blank" title="Powered by #menorcadev"><img src="http://www.menorcadev.com/logo-powered.png" alt="Powered by #menorcadev" /></a>
      </center>
      <br />
</div>