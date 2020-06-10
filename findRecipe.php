<!DOCTYPE html>
<html>
	<head>
		<title>The Recipe Program</title>
		<link rel="stylesheet"
				type="text/css"
				href="css/recipe.css" />
	</head>
	<body>
		<div id = "heading">
			<?php include("header.php"); ?>
		</div>
		<div id = "menu">
			<?php include("menu.html"); ?>
		</div>
		<div id = "main">
<?php
	include('../../../htconfig/recipeConfig.php'); 
	include('recipeLib.php');
	
	$dbc = mysqli_connect($db['hostname'],$db['username'],$db['password'], $db['database']);
	
	$search = false;
	
	searchRecipeForm();
	
	$whatRecipe = $_POST["whatRecipe"];
	$whatCategory = $_POST["whatCategory"];
	
	if ($search) {
		// Get the values from the search form
		$searchValue = mysqli_real_escape_string($_REQUEST["srchVal"], $dbc);
		$searchField = $_REQUEST["srchField"];
		
		// Show the results as a form
		showResults($searchValue, $searchField, $dbc);
	}
	
	if (isset($whatRecipe)) {
		showRecipe($whatRecipe, $whatCategory, $dbc);
	}
?>
		</div>
		<div id = "footer">
			<?php include("footer.html"); ?>
		</div>
	</body>
</html>