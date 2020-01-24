<?php
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

function record_selection($uuid, $name, $selected) {

    // Require table variables:
    require __DIR__ . '/table_variables.php';


    // connect to database
    require_once __DIR__ . '/db_connect.php';
    $db = db_connect();


    // My ideal query:
    /*
    $sql = "INSERT INTO $selections_table (uuid, name, selected)
            VALUES (:uuid, :name, :selected)

            -- If the name selected is already here, update to new true/false:
	    ON CONFLICT ON CONSTRAINT selections_uuid_name_key
            DO UPDATE SET selected = EXCLUDED.selected

            -- If the name isn't in the names table, someone is doing something bad
            ON CONFLICT ON CONSTRAINT selections_name_fkey
            DO NOTHING;";
     */

    // Maybe check if the name is allowed?

    // What we have to do, since multiple ON CONFLICT's are not allowed:
    $sql = "INSERT INTO $selections_table (uuid, name, selected, n_changes, date_changed)
            VALUES (:uuid, :name, :selected, 0, NULL)

            -- If the name selected is already here, update to new true/false, increment counter add date_changed:
	    ON CONFLICT ON CONSTRAINT selections_uuid_name_key
            DO UPDATE SET selected = EXCLUDED.selected,
                          date_changed = CURRENT_TIMESTAMP,
                          n_changes = selections.n_changes + 1;";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':uuid',$uuid);
    $stmt->bindValue(':name',$name);
    $stmt->bindValue(':selected',$selected,PDO::PARAM_BOOL);
    $stmt->execute();
}

