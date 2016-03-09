<?php
$page_title = "Popovers";
require_once("_resources/header.inc.php");
?>

<?php echo "<h1>$page_title</h1>"; ?>

<div class='well'>
  <a
    title=""
    href="javascript:void(0)"
    data-toggle="popover"
    data-trigger="focus"
    data-placement="right"
    data-content="
      Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
      Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. 
      Sed nisi. Nulla quis sem at nibh elementum imperdiet. 
      Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. 
      Mauris massa. Vestibulum lacinia arcu eget nulla. 
    " 
    data-original-title="Popover Header Text"
  >
    Click Here for Popover Text Box
  </a>
</div><!-- /.well -->

<?php require_once('_resources/footer.inc.php');?>

<script>$('[data-toggle="popover"]').popover()</script>
