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
			<?php include("menu.php"); ?>
		</div>
		<div id = "main">	
<?php
	include('../../htconfig/recipeConfig.php'); 
	
	$dbc = mysqli_connect($db['hostname'],$db['username'],$db['password'], $db['database']);
	
	// print("Connected");
		
		$submit = $_REQUEST["submit"];
		$ingredients = $_REQUEST["formIng"];
		$steps = $_REQUEST["steps"];
		
		function addStepsForm($recipeID, $numSteps) {
			print("<form method = \"post\" action = \"addRecipe.php\">");
			for ($i = 1; $i <= $numSteps; $i++) {
				print <<<HERE
				<fieldset>
					<legend>Step #$i</legend>
					<p>
						<textarea name = "step"
								  rows = 8
								  cols = 50></textarea>
					</p>
				</fieldset>
HERE;
			}
			print <<<HERE
			<input type = "hidden"
				   name = "recipeID"
				   value = "$recipeID" />
			<input type = "hidden"
				   name = "numSteps"
				   value = "$numSteps" />
			<input type = "submit"
				   name = "steps"
				   value = "Finish" />
HERE;
			print("</form>");
		}
		
		function addSteps($recipeID, $stepNum, $step, $conn) {
			$IsqlS = "INSERT INTO steps VALUES ('NULL', '$recipeID', '$stepNum', '$step')";
			$IresultS = mysqli_query($conn, $IsqlS) or die(mysqli_error($conn));
		}
		
		function addIngredientsForm($recipeID, $numIngredients) {
			// print("Add Ingredients");
			// print($numIngredients);
			print("<form method = \"post\" action = \"addRecipe.php\">");
			for ($i = 1; $i <= $numIngredients; $i++) {
				print <<<HERE
				<fieldset>
					<legend>Ingredient #$i</legend>
					<p>
						<label>Name of Ingredient:</label>
						<input type = "text"
							   name = "ingredientName$i" />
					</p>
					<p>
						<label>Amount of Ingredient:</label>
						<input type = "number"
							   name = "ingredientAmount$i" />
					</p>
					<p>
						<label>Unit:</label>
						<input type = "text"
							   name = "unit$i" />
					</p>
					<p>
						<input type = "hidden"
							   name = "recipeID$i"
							   value = "$recipeID" />
					</p>
				</fieldset>
HERE;
			}
				print <<<HERE
				 <input type = "hidden" 
						name = "numIngredients" 
						value = "$numIngredients" />
				<p>
					<label>Number of steps:</label>
					<input type = "text"
						name = "numSteps" />
				</p>
				 <input type = "submit" 
						name = "formIng" 
						value = "Add Ingredients" />
			</form>
HERE;
		}
		
		function addIngredients($id, $name, $amount, $unit, $conn) {
			$Isql = "INSERT INTO ingredients VALUES ('NULL', '$id', '$name', '$amount', '$unit')";
			$Iresult = mysqli_query($conn, $Isql) or die(mysqli_error($conn));
			
			$numSteps = $_REQUEST["numSteps"];
			
			addStepsForm($id, $numSteps);
		}
		
		function addRecipe($name, $time, $category, $numIngredients, $conn) {
			
			$Isql = "INSERT INTO recipeinfo VALUES ('NULL', '$name', '$time', '$category')";
			$Iresult = mysqli_query($conn, $Isql) or die(mysqli_error($conn));
			
			if ($Iresult){
				$Ssql1 = "SELECT recipeID FROM recipeinfo WHERE recipeName = '$name'";
				$Sresult1 = mysqli_query($conn, $Ssql1) or die (mysqli_error($conn));
				
				while($row = mysqli_fetch_array($Sresult1, MYSQL_ASSOC)) {
					$ID = $row["recipeID"];
					
					addIngredientsForm($ID, $numIngredients);
				}
			}
		}
		
		if (isset($submit) and !isset($ingredients)) {
			$name = $_REQUEST["recipeName"];
			$time = $_REQUEST["cookTime"];
			$category = $_REQUEST["category"];
			$numIngredients = $_REQUEST["numIngredients"];
			
			addRecipe($name, $time, $category, $numIngredients, $dbc);
			
		} elseif(isset($ingredients)) {
			$numIngredients = $_REQUEST["numIngredients"];
			for ($i = 1; $i <= $numIngredients; $i++) {
				$recipeID = $_REQUEST["recipeID$i"];
				$name = $_REQUEST["ingredientName$i"];
				$amount = $_REQUEST["ingredientAmount$i"];
				$unit = $_REQUEST["unit$i"];
				addIngredients($recipeID, $name, $amount, $unit, $dbc);
			}
			
		} elseif(isset($steps))	{
			$numSteps = $_REQUEST["numSteps"];
			for ($i = 1; $i <= $numSteps; $i++) {
				$recipeID = $_REQUEST["recipeID$i"];
				$stepNum = $i;
				$step = $_REQUEST["step$i"];
				
				addSteps($recipeID, $stepNum, $step, $dbc);
			}
			print("<h1 class = \"finish\">Recipe Has Been Added</h1>");
			print("<a href = \"index.php\">Home</a>")
		} else {
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
			<?php include("footer.php"); ?>
		</div>
	</body>
</html>