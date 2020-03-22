<?php
require './login_script.php';
?>

<?php
// Region to set up PHP stuff
require_once $function_path . 'get_matching_names_list.php';
require_once $function_path . 'get_uuid.php';
require_once $function_path . 'download_match_list.php';

$uuid = $_SESSION['uuid'];

$partner_email = $_SESSION['partner_email'];
$partner_uuid = get_uuid($partner_email);

$names = get_matching_names_list($uuid,$partner_uuid);
download_match_list($names);

//header('Location: ./match_list.php');

?>
