<?php
	$con = mysql_connect("localhost","root","");
	mysql_select_db("crawler");
	$itr = 1;
	$initial_query = mysql_query("SELECT * FROM crawl_contents");
	$num_of_follows = 0;
	while($check = mysql_fetch_array($initial_query)){
		
		$query = mysql_query("SELECT * FROM result_table where crawl_id = '$itr'");
		$number_of_rows = mysql_num_rows($query);
		if($number_of_rows!=0){
		while($row = mysql_fetch_array($query)){
			if($row['dofollow']==1){
				$num_of_follows++;
			}	
		}
		$insert_query = mysql_query("INSERT into processed_result values('$itr','$number_of_rows','$num_of_follows')");
		if($number_of_rows == $num_of_follows){
			echo "We got a do follow link. Try posting somewhere in the below link </br>";
			$query_find_link = mysql_query("SELECT * FROM crawl_contents where id = '$itr'");
			$row_find_link = mysql_fetch_array($query_find_link);
			echo "<a href=".$row_find_link['target_link'].">".$row_find_link['target_link']."</a></br></br>";

		}

		}
		$itr++;
		$num_of_follows = 0;
	}

?>