<!DOCTYPE html>
<html>
	<head>
		<title>The Recipe Program</title>
		<link rel="stylesheet"
				type="text/css"
				href="css/recipe.css" />
		<link type="text/javascript"
			  src="recipe.js" />
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
	include('recipeLib_1.php');

// Print the form for adding a recipe	
	print <<<HERE
		<form method = "post" 
			  action = "addRecipe.php">
			<fieldset>
			<legend>Main Recipe Info</legend>
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
	categoryMenu();
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
					<button>Continue</button>
				</p>
			</fieldset>
			<fieldset>
				<legend></legend>
			</fieldset>
		</form>
HERE;
?>
	</div>
		<div id = "footer">
			<?php include("footer.html"); ?>
		</div>
	</body>
</html>