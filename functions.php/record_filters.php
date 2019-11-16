<?php
/**
 * Function to set a user's filter preferences
 *
 * @param $uuid : UUID of user making the request
 * @param $preferences : Associative array containing filter choices
 * Expected form is:
 * 		array(
 * 		gender=>array("boys","girls","neutral20","neutral40"),
 * 		first_letter=>null/"A-Z",
 *		last_letter=>null/"A-Z",
 * 		popularity=>null/"popular/unusual" )
 *
 * Gender array can contain none/any/all of those entries indicating
 *
 * @return No return, silent execution
**/

function record_filters($uuid, $preferences) {

    // Require table variables:
    require __DIR__ . '/table_variables.php';


    // connect to database
    require_once __DIR__ . '/db_connect.php';
    $db = db_connect();

    // Parse input gender preferences:
    $gender_text = '';

    if( count($preferences['gender']) == 4 || count($preferences['gender']) == 0  ) {
      // We have selected everything or nothing, which is the same as no preference:
      $gender_text = null;
    } else {
      foreach( $preferences['gender'] as $pref ) {
        if( !empty($pref) ) {
          $gender_text = $gender_text .'-'. $pref;
        }
      }
    }

    // First Letter filters:
    $first_let_text = null;
    if( !is_null($preferences['first_letter']) ) {
      $first_let_text = $preferences['first_letter'];
    }

    // Last Letter:
    $last_let_text = null;
    if( !is_null($preferences['last_letter']) ) {
      $last_let_text = $preferences['last_letter'];
    }

    // Popularity filter:
    $popularity_text = null;
    if( !is_null($preferences['popularity']) ) {
      $pop_text = $preferences['popularity'];
    }

    $sql = "INSERT INTO $preferences_table
            (uuid, gender, first_letter, last_letter, popularity)
            VALUES (:uuid, :gender_text, :first_let_text, :last_let_text, :pop_text)

            -- If the name selected is already here, update to new true/false:
	    ON CONFLICT (uuid)
            DO UPDATE SET
            gender = EXCLUDED.gender,
            first_letter = EXCLUDED.first_letter,
            last_letter = EXCLUDED.last_letter,
            popularity = EXCLUDED.popularity;";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':uuid',$uuid);
    $stmt->bindValue(':gender_text',$gender_text);
    $stmt->bindValue(':first_let_text',$first_let_text);
    $stmt->bindValue(':last_let_text',$last_let_text);
    $stmt->bindValue(':pop_text',$pop_text);
    $stmt->execute();
}

