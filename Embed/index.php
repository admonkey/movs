<?php

include_once('_resources/credentials.inc.php');
//$page_title = "Home Page";
require_once('_resources/header.inc.php');

echo "<h1>Responsive $section_title</h1>"; ?>

<p class='lead'>Some excellent videos and courses on application development.</p>

<div class='well'>
<h2>16:9 aspect ratio</h2>
<div class="embed-responsive embed-responsive-16by9">
  <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/119453133" allowfullscreen></iframe>
</div>
</div><!-- /.well -->

<div class='well'>
<h2>16:9 aspect ratio</h2>
<div class="embed-responsive embed-responsive-16by9">
  <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/Ytux4IOAR_s?list=PLAwxTw4SYaPk8_-6IGxJtD3i2QAu5_s_p" allowfullscreen></iframe>
</div>
<br/>
<p><a href='https://www.udacity.com/courses/ud775' class='btn btn-primary'>Free Course on Udacity</a></p>
</div><!-- /.well -->

<div class='well'>
<h2>4:3 aspect ratio</h2>
<p>Because this video is not natively 4:3, it is letter-boxed, although you get the picture it would fit one.</p>
<div class="embed-responsive embed-responsive-4by3">
  <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/18MP17wq54E?list=PLK9sPEYTATw8Mgl2UecilwXujPnDr8DDX"></iframe>
</div>
</div><!-- /.well -->

<?php require_once('_resources/footer.inc.php');?>
