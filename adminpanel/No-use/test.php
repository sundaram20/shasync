<?php include_once("../config/auto_loader.php");
// $current_cat_id: the current category id number
// $count: just a counter, call it as 0 in your function call and forget about it
/* GET THE DROP DOWN LIST OF CATEGORIES */

function get_cat_selectlist($current_cat_id, $count) {
	//echo"current_cat_id--$current_cat_id<br>";
	static $option_results;
	// if there is no current category id set, start off at the top level (zero)
	if (!isset($current_cat_id)) {
		$current_cat_id =0;
	}
	// increment the counter by 1
	$count = $count+1;

	// query the database for the sub-categories of whatever the parent category is
	$sql =  'SELECT id, name from '.TBL_LOCATION.' where parent_id =  '.$current_cat_id;
	$sql .=  ' order by name asc ';

	$get_options = mysql_query($sql);
	$num_options = mysql_num_rows($get_options);

	// our category is apparently valid, so go ahead €¦
	if ($num_options > 0) {
			while (list($cat_id, $cat_name) = mysql_fetch_row($get_options)) {

			// if its not a top-level category, indent it to
			//show that its a child category

				if ($current_cat_id!=0) {
						$indent_flag =  '';
						for ($x=2; $x<=$count; $x++) {
								$indent_flag .=  '&nbsp;&nbsp;';
						}
				}
				$cat_name = $indent_flag.$cat_name;
				$option_results[$cat_id] = $cat_name;
				// now call the function again, to recurse through the child categories
				get_cat_selectlist($cat_id, $count );
			}
	}
	return $option_results;
}


$myLocation = '<option value="">-- Select -- </option>';
$get_options = get_cat_selectlist(0, 0);
	if (count($get_options) > 0){
			$categories = $_POST['cat_id'];
			foreach ($get_options as $key => $value) {
					$options .="<option value=\"$key\"";
					// show the selected items as selected in the listbox
					if ($_POST['cat_id'] == "$key") {
						$options .=" selected=\"selected\"";
					}
					$options .=">$value</option>\n";
			}
	}
$myLocation .= $options;

echo '<select>'.$myLocation.'</select>';
?>