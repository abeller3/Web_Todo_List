
<? 
 $list = []; 
 $filename = "list.txt"; 
 $error = ''; 



function loadFile($filename) {
    if(filesize($filename) > 0) {
	$handle = fopen($filename, 'r');
	$contents = trim(fread($handle, filesize($filename)));
	$contents_array = explode("\n", $contents);
	fclose($handle);

	return $contents_array;

    } 
}

function saveFile($list, $filename) {

	$handle = fopen($filename, 'w');
	$string = implode("\n", $list);
	$contents = fwrite($handle, $string);
	fclose($handle);
    
}

$list = loadFile($filename);
?>

<!DOCTYPE html>
<html>
<head>
	<title>TODO List</title>
    <link rel="stylesheet" href="todo_style.css">
</head>
<body>
    <div class="center">

    <?php

       

        // Verify there were uploaded files and no errors
        if (count($_FILES) > 0 && $_FILES['file1']['error'] == 0) :
            // Set the destination directory for uploads
            $upload_dir = '/vagrant/sites/todo.dev/public/uploads/';
            // Grab the filename from the uploaded file by using basename
            $Up_filename = basename($_FILES['file1']['name']);
            // Create the saved filename using the file's original name and our upload directory
            $saved_filename = $upload_dir . $Up_filename;
            // Move the file from the temp location to our uploads directory
            move_uploaded_file($_FILES['file1']['tmp_name'], $saved_filename);
            $textfile = $saved_filename;
            $newfile = loadFile($textfile);
            $list = array_merge($list, $newfile);
            saveFile($list, $filename);
          
         else :
            $errorMessage = "Not a valid file. Please use only a plain text file";
    endif;
?>

<h1>Upload File</h1>

<form method="POST" enctype="multipart/form-data" action="/new.php">
    <p>
        <label for="file1">File to upload: </label>
        <input type="file" id="file1" name="file1">
    </p>
    <p>
        <input type="submit" value="Upload">
    </p>
</form>

<h1>TODO List</h1>

<form method="POST" action='/new.php'>
    <!-- Add item to the list here -->
    <p>
     <label for="item_to_add">Add item: </label>
     <input id="item_to_add" name="item_to_add" type="text" autofocus>
    </p>
        <input type="submit">
</form>

<ul class="flower">
    	<? if (!empty($_POST)) : ?>
    		<? $list[] = $_POST['item_to_add']; ?>
       <? endif; ?>

		<? if (isset($_GET['removeIndex'])) : ?>
    		<? $remove = $_GET['removeIndex']; ?>
			<? unset($list[$remove]); ?>
			<? $list = array_values($list); ?>
		<? endif; ?>
    	
		
    	<? foreach ($list as $index => $value) : ?>
    		<li>
                <?= htmlspecialchars(strip_tags($value)); ?>
                <?="<a href=\"new.php?removeIndex={$index}\">Remove Item</a>";?>
            </li>
    	<? endforeach; ?>
       <? saveFile($list, $filename); ?>
    
	</ul>
        </div>
</body>
</html>