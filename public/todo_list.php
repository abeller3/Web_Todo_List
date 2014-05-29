<!DOCTYPE html>
<html>
<head>
	<title>TODO List</title>
</head>
<body>
<h1>TODO List</h1>

    <form method="POST">
    <!-- Add item to the list here -->
    <p>
     <label for="Subject">Add item: </label>
     <input id="Subject" name="Subject" type="text">
    </p>
        <input type="submit">
    </form>

    <?php

    function loadFile($fileName = './list.txt') {
        if(is_readable($fileName)) {
        $filesize = filesize($fileName);
        $handle = fopen($fileName, "r");
        $contents = trim(fread($handle, $filesize));
        $contents_array = explode("\n", $contents);

        fclose($handle);
        return $contents_array;
         }

    }

    function saveFile($list, $fileName){
          //
    }

        // var_dump($_POST);

        // Loads file and assigns to variable list as an array.
        $list = loadFile();



        // Append new item ($_POST) to existing array of list items.
        $item_add = implode("", $_POST);
        array_push($list, $item_add);

        echo "<ul>";
            foreach($list as $value) {
                echo "<li>{$value}</li>";
            }
        echo "</ul>";

        // var_dump($list);

        // Save new list to file



    ?>

</body>
</html>