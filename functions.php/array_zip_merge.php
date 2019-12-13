/**
 * Function to perform array zipping:
 * Array1[0],Array2[0],...,ArrayN[0],Array1[1],...
 *
 * see: https://stackoverflow.com/questions/1860490/interleaving-multiple-arrays-into-a-single-array
 * for an exaplanation of how it functions
 *
 *
 * input: N 1D arrays
 *
 * output: 1 1D array with values zipped together
 *
**/

function array_zip_merge() {
  $output = array();
  // The loop incrementer takes each array out of the loop as it gets emptied by array_shift().
  for ($args = func_get_args(); count($args); $args = array_filter($args)) {
    // &$arg allows array_shift() to change the original.
    foreach ($args as &$arg) {
      $output[] = array_shift($arg);
    }
  }
  return $output;
}

