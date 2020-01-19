<?php
	// adds the form for the recipe steps
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
		
	// Adds actual steps
		function addSteps($recipeID, $stepNum, $step, $conn) {
			$IsqlS = "INSERT INTO steps VALUES ('NULL', '$recipeID', '$stepNum', '$step')";
			$IresultS = mysqli_query($conn, $IsqlS) or die(mysqli_error($conn));
		}
		
	// Adds the form for the ingredients
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
		
	// Adds ingredients
		function addIngredients($id, $name, $amount, $unit, $conn) {
			$Isql = "INSERT INTO ingredients VALUES ('NULL', '$id', '$name', '$amount', '$unit')";
			$Iresult = mysqli_query($conn, $Isql) or die(mysqli_error($conn));
			
			$numSteps = $_REQUEST["numSteps"];
			
			addStepsForm($id, $numSteps);
		}
		
	// Adds main recipe info
		function addRecipe($name, $time, $numServings, $category, $numIngredients, $conn) {
			
			$Isql = "INSERT INTO recipeinfo VALUES ('NULL', '$name', '$time', '$numServings', '$category')";
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

	// Shows the recipe based off of a recipeID
		function showRecipe($recipeID, $conn) {
			$sqlri = "SELECT * FROM recipeinfo WHERE recipeID = $recipeID";
			$sqlst = "SELECT * FROM steps WHERE recipeID = $recipeID";
			$sqlig = "SELECT * FROM ingredients WHERE recipeID = $recipeID"
		}

	// Form for searching the recipe database
		function searchRecipeForm() {
			print <<<HERE
			<form method = "post" action = "showRecipe.php">
				<fieldset>
					<p>
						<label>Search for </label>
						<input type = "text"
							   name = "srchVal" />
						<label> in </label>
						<select name = "srchField">
							<option value = "name">Recipe Name</option>
							<option value = "ingredient">Ingredient</option>
							<option value = "category">Category</option>
							<option value = "servings">Servings</option>
							<option value = "time">Cooking Time</option>
						</select>
					</p>
					<p>	
						<input type = "submit"
							   name = "search"
							   value = "Search" />
					</p>
				</fieldset>
			</form>
HERE;
		}
		
	// Show the results of a search
		function showResults($srchVal, $srchField, $conn) {
			$table, $field;
			switch ($srchField) {
				case 'name':
					$table = "recipeinfo";
					$field = "recipeName";
					break;
				case 'ingredient':
					$table = "ingredients";
					$field = "name";
					break;
				case 'category':
					$table = "recipeinfo";
					$field = "categoryID";
					break;
				case 'servings':
					$table = "recipeinfo";
					$field = "numServings";
					break;
				case 'time':
					$table = "recipeinfo";
					$field = "recipeTime";
					break;
			}
			
			$sql1 = "SELECT recipeID FROM $table WHERE $field LIKE $srchVal"; 
			$results = mysqli_query($conn, $sql1) or die(mysqli_error($conn));
			
			while($row = mysqli_fetch_array($sql1, MYSQL_ASSOC)) {
				$ID = $row["recipeID"];
				$sqlIng = "SELECT * FROM recipinfo WHERE recipeID = $ID";
				$query = mysqli_query($conn, $sqlIng) or die(mysqli_error($conn));
				while($row = mysqli_fetch_array($sqlIng, MYSQL_ASSOC)) {
					$recipeName = $row["recipeName"];
					$recipeTime = $row["recipeTime"];
					$numServings = $row["numServings"];
					
					// get the category information
					$categoryID = $row["categoryID"];
					$sqlC = "SELECT categoryName FROM categories WHERE categoryID = $categoryID";
					$result = mysqli_query($conn, $sqlC) or die(mysqli_error($conn));
					while($row = mysqli_fetch_array($sqlC, MYSQL_ASSOC)) {
						$category = $row["categoryName"];
					}
					// print list of recipes in a form
					print<<<HERE
						<form method = "post" action = "showRecipe.php">
							<fieldset>
								<label>$recipeName</label>
								<input class = "recipeLink"
									   type = "submit"
									   name = "recipe"
									   value = "Go to recipe" />
								<input type = "hidden"
									   name = "whatRecipe"
									   value = "$ID" />
								<p>$recipeTime		$numServings		$category</p>
							</fieldset>
						</form>							
HERE;
				}
			}			
		}
		
?>
