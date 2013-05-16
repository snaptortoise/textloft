<?php


class TextLoft {	
	public static $title = "TextLoft"; 
	public static $path = "pages";
	public static $files;
	public static $home;
	public static $writeable = true;

	function go() {
		
		TextLoft::$home = substr($_SERVER["REQUEST_URI"], 0, strrpos($_SERVER["REQUEST_URI"], "/")+1);

		$title = $_GET["page"];
		$page = urldecode($title);

		if (!$page) $page = "index";
		if (!$title) $title = TextLoft::$title;

		$filename = TextLoft::$path . "/" . $page . ".html";
		$edit = stristr($_SERVER["REQUEST_URI"], "?edit");
		$delete = stristr($_SERVER["REQUEST_URI"], "?delete");
		$rename = stristr($_SERVER["REQUEST_URI"], "?rename");
		
		if (TextLoft::$writeable === true):
			if ($rename) {
				$new_page = $_POST["rename-page-title"];			
				file_put_contents(TextLoft::$path . "/" . $new_page . ".html", file_get_contents($filename));
				unlink($filename);
				header("Location:$new_page");
				exit();

			}
		
			if ($delete && file_exists($filename)) {
				unlink($filename);
				header("Location:" . TextLoft::$home);
				exit();
			}
		endif;

		if (!file_exists(TextLoft::$path)) mkdir(TextLoft::$path);
		$files = scandir(TextLoft::$path);		

		foreach($files as $key=>$file) { 									
			if ($file == "." || $file == "..") {				
				unset ($files[$key]);
			}else{
				$files[$key] = str_replace(".html", "", $file);
			}
		}

		TextLoft::$files = $files;		

		if ((!file_exists($filename) || $edit) && TextLoft::$writeable) {			
			// File empty; show editor

			if ($_POST) { 
				$content = $_POST["wiki-content"];
				TextLoft::create($filename, $content);
				header("Location:$page");
			}else{
				$current_content = $edit ? file_get_contents($filename) : "# ". ucwords($title) . "\n";
				TextLoft::header("$page", $edit);
				TextLoft::editor($current_content);	
				TextLoft::footer($edit);	
			}			
			

		}elseif(file_exists($filename)){
			require("markdown_extended.php");
			$contents = file_get_contents($filename);
			TextLoft::header($page);
			echo MarkDownExtended($contents);			
			TextLoft::footer($edit);
		}else{
			header(' ', true, 404);
			exit();
		}
	
	}


	function editor($current_content) {
		?>
		<form class="editor" action="" method="post">			
			<textarea name="wiki-content" id="wiki-content" cols="30" rows="10"><?= $current_content ?></textarea> <br/>
			<input type="submit" value="Save">
			<br/> <a href="#" id="wiki-cancel">Cancel</a>
		</form>
		<?php
	}

	function create ($filename, $content) {				
		$content = stripcslashes($content);
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
			<meta name="apple-mobile-web-app-capable" content="yes" />
			<meta name="apple-mobile-web-app-status-bar-style" content="black" />  
			<meta name="viewport" content = "width = device-width, initial-scale = 1, user-scalable = no" /> 
			<link rel="shortcut icon" href="<?= TextLoft::$home ?>favicon.png">
			<link rel="apple-touch-icon-precomposed" href="<?= TextLoft::$home ?>apple-touch-icon-precomposed.png">
			<link rel="apple-touch-startup-image" href="apple-touch-startup-image.png">
		</head>
		<body>
			<div class="wrap">
				<header>
					<a class="home" href="<?= TextLoft::$home ?>"><?= TextLoft::$title ?></a>
						&raquo; 
					<?php if ($title != "index" && !$edit): ?>
						<form action="?rename" id="rename-page" method="post">
						<input type="text" value="<?= $title ?>" name="rename-page-title" data-old="<?= $title ?>" id="rename-page-title" />
						</form>
					<?php else:?>
						<?= $title ?>
					<?php endif ?>
					
					<?php if(count(TextLoft::$files)):  ?>					
					<select id="wiki-jump">
						<option value="#" >&rarr; Jump</option>
					<?php foreach(TextLoft::$files as $file): ?>
						<option value="<?= $file ?>"><?= $file ?></option>
					<?php endforeach; ?>					
					</select>
					<?php endif; ?>
				</header>
				<section role="main">			
		<?php
	}

	function footer($edit) {
		?>
		</section>
		<footer>
			<div class="group">
			<?php if (!$edit && TextLoft::$writeable): ?> <a href="#" id="wiki-edit">Edit</a> | <a href="#" id="wiki-delete">Delete</a> 
			</div>
			<?php endif ?>
			<form class="wiki-new-page"><input type="text" size=10 name="wiki-page" placeholder="Enter New Page"/> <input type="submit" value="Add"></form>
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
