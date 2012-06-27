<?php


class TextLoft {
	
	public static $path = "pages";
	public static $home;
	function go() {
		
		TextLoft::$home = substr($_SERVER["REQUEST_URI"], 0, strrpos($_SERVER["REQUEST_URI"], "/")+1);

		$title = $_GET["page"];
		$page = urldecode($title);

		if (!$page) $page = "index";
		if (!$title) $title = "Home";

		$filename = TextLoft::$path . "/" . $page . ".html";
		$edit = stristr($_SERVER["REQUEST_URI"], "?edit");
		
		if (!file_exists($filename) || $edit) {			
			// File empty; show editor

			if ($_POST) { 
				$content = $_POST["wiki-content"];
				TextLoft::create($filename, $title, $content);
				header("Location:$page");
			}else{
				$current_content = $edit ? file_get_contents($filename) : "#$title\n";
				TextLoft::header("Editing $page");
				TextLoft::editor($title, $current_content);	
				TextLoft::footer($edit);	
			}			
			

		}else{
			require("markdown_extended.php");
			$contents = file_get_contents($filename);
			TextLoft::header($page);
			echo MarkDownExtended($contents);			
			TextLoft::footer($edit);
		}
	
	}


	function editor($title, $current_content) {
		?>
		<form class="editor" action="" method="post">
			<input type="text" name="title" id="title" value="<?= $title ?>"/> <br/>
			<textarea name="wiki-content" id="wiki-content" cols="30" rows="10"><?= $current_content ?></textarea> <br/>
			<input type="submit" value="Save">
		</form>
		<?php
	}

	function create ($filename, $title, $content) {		
		
		$content = $content;
		file_put_contents($filename, $content);
	}

	function header($title) {
		?>
		<!DOCTYPE HTML>
		<html lang="en-US">
		<head>
			<meta charset="UTF-8">
			<title><?= $title ?></title>
			<link rel="stylesheet" href="<?= TextLoft::$home ?>css/style.css">
		</head>
		<body>
			<div class="wrap">
				<header>
					<a class="home" href="<?= TextLoft::$home ?>">&laquo; Home</a>
				</header>
				<section role="main">			
		<?php
	}

	function footer($edit) {
		?>
		</section>
		<footer>
			<?php if (!$edit): ?> <a href="#" id="wiki-edit">Edit</a> | <?php endif ?>New page: <input type="text" name="wiki-page"/> <input id="wiki-new-page" type="submit" value="Go">
		</footer>
		</div>
		<script src="<?= TextLoft::$home ?>js/jquery.js"></script>
		<script src="<?= TextLoft::$home ?>js/textloft.js"></script>
		</body>
		</html>
		<?php
	}

}



TextLoft::go();


