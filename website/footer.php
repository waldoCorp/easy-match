<footer class="footer">
 <div class="container-fluid">
  <span class="text-muted">
    Find a bug? Want a new feature? Like the site? <a href='mailto:ben@waldocorp.com,lief@waldocorp.com'>Drop us a line!</a>
     |  <a href='privacy.php'>Privacy policy</a>
     |  <a href='about.php'> About </a>
  </span>
 </div>
</footer>


<!-- Script to bold current page in navbar -->
<script>
$(document).ready(function() {
  $('li.active').removeClass('active');
  const url = window.location.pathname;
  const filename = url.substring(url.lastIndexOf('/')+1);
  $('a[href="' + filename + '"]').closest('li').addClass('active');
});
</script>

<?php if( $_SESSION['new_matches'] ) { ?>
<!-- Script to turn on Feather Icons -->
<script>
feather.replace({
  stroke:"#D4AC0D",
  'style':'float:right;margin-left:-100px;margin-top:-7px;',
  class:'tooltip-star',
  'data-toggle':'tooltip',
  'data-placement':'bottom',
  'title':'New Matches!'
});

$('.tooltip-star').tooltip();

</script>
<?php } ?>
