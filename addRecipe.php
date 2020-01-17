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
	
	// print("Connected");
		
	$submit = $_REQUEST["submit"];
	$ingredients = $_REQUEST["formIng"];
	$steps = $_REQUEST["steps"];
		
	if (isset($submit) and !isset($ingredients)) {
		$name = $_REQUEST["recipeName"];
		$time = $_REQUEST["cookTime"];
		$numServings = $_REQUEST["numServings"];
		$category = $_REQUEST["category"];
		$numIngredients = $_REQUEST["numIngredients"];
		
		addRecipe($name, $time, $category, $numIngredients, $dbc);
		
	} 
	elseif(isset($ingredients)) {
		$numIngredients = $_REQUEST["numIngredients"];
		for ($i = 1; $i <= $numIngredients; $i++) {
			$recipeID = $_REQUEST["recipeID$i"];
			$name = $_REQUEST["ingredientName$i"];
			$amount = $_REQUEST["ingredientAmount$i"];
			$unit = $_REQUEST["unit$i"];
			addIngredients($recipeID, $name, $amount, $unit, $dbc);
		}
		
	} 
	elseif(isset($steps)) {
		$numSteps = $_REQUEST["numSteps"];
		for ($i = 1; $i <= $numSteps; $i++) {
			$recipeID = $_REQUEST["recipeID$i"];
			$stepNum = $i;
			$step = $_REQUEST["step$i"];
			
			addSteps($recipeID, $stepNum, $step, $dbc);
		}
		print("<h1 class = \"finish\">Recipe Has Been Added</h1>");
		print("<a href = \"index.php\">Home</a>");
	} 
	else {
		print <<<HERE
		<form method = "post" 
			  action = "addRecipe.php">
			<fieldset>
				<p>
					<label>Recipe Name:</label>
					<input type = "text"
						   name = "recipeName" />
				</p>
				<p>
					<label>Cooking Time:</label>
					<input type = "text"
						   name = "cookTime" />
				</p>
				<p>
					<label>Category:</label>
					<select name = "category">
HERE;
			$sql = "SELECT categoryID, categoryName FROM categories";
			$query = mysqli_query($dbc, $sql);
			while($row = mysqli_fetch_array($query, MYSQL_ASSOC)) {
							$ID = $row["categoryID"];
							$name = $row["categoryName"];
							print("<option value=\"$ID\">");
							print($name);
							print("</option>");
			}
		print <<<HERE
					</select>
				</p>
				<p>
					<label>Number of Servings</label>
					<input type = "text"
						   name = "numServings" />
				</p>
				<p
					<label>Number of ingredients:</label>
					<input type = "text"
						   name = "numIngredients" />
				</p>
				<p>
					<input type = "submit"
						   name = "submit"
						   value = "Continue" />
				</p>
			</fieldset>
		</form>
HERE;
		}

?>
		</div>
		<div id = "footer">
			<?php include("footer.html"); ?>
		</div>
	</body>
</html>