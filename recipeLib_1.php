<?php
	// Creates the Category dropdown menu
	function categoryMenu(){
		$sql = "SELECT categoryID, categoryName FROM categories";
		$query = mysqli_query($dbc, $sql);
		while($row = mysqli_fetch_array($query, MYSQL_ASSOC)) {
						$ID = $row["categoryID"];
						$name = $row["categoryName"];
						print("<option value=\"$ID\">");
						print($name);
						print("</option>");
		}
	}

?>