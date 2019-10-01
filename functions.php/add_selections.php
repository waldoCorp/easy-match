<? php
/**
 * Function to add a users name selection to the db
 * triggered when a user up/down votes a name
 * 
 * Use of function:
 * on.click() {
 * add_selections($uuid, $name, $selected);
 * }
 * 
 * @param $uuid : UUID of user making the request
 * @param $name : the current name to enter into the selections table
 * @param $selected : boolean, true if upvote, false if downvote
 * @return No return, silent execution
**/

function add_selections($uuid, $name, $selected) {
    // connect to database
    require_once '/srv/nameServer/functions.php/db_connect.php';
    $db = db_connect();

    $sql = "
         INSERT INTO $selections_table (uuid, name, selected) 
         VALUES (:uuid, :name, :selected);";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':uuid',$uuid);
    $stmt->bindValue(':name',$name);
    $stmt->bindValue(':selected',$selected);

}

