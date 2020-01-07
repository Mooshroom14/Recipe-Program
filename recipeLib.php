<?php
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
?>