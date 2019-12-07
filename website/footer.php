<div class="navbar fixed-bottom navbar-light">
  <span class="navbar-text">
    Find a bug? Want a new feature? Like the site? <a href='mailto:ben@waldocorp.com,lief@waldocorp.com'>Drop us a line!</a>
  </span>
</div>

<!-- Script to bold current page in navbar -->
<script>
$(document).ready(function() {
  $('li.active').removeClass('active');
  const url = window.location.pathname;
  const filename = url.substring(url.lastIndexOf('/')+1);
  $('a[href="' + filename + '"]').closest('li').addClass('active');
});
</script>
