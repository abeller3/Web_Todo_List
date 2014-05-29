<?php
var_dump($_GET);


function loadFile($filename = './list.txt') {

    $handle = fopen($filename, 'r');
    $contents = trim(fread($handle, filesize($filename)));
    $contents_array = explode("\n", $contents);
    fclose($handle);

    return $contents_array;
}

function saveFile($list, $filename = './list.txt') {

    $handle = fopen($filename, 'w');
    $string = implode("\n", $list);
    $contents = fwrite($handle, $string);
    fclose($handle);

}

?>

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
     <label for="item_to_add">Add item: </label>
     <input id="item_to_add" name="item_to_add" type="text">
    </p>
        <input type="submit">
    </form>

    <ul>

    <?php

        // Load file data into list array
        $list = loadFile();

        // var_dump($_POST);
        

        if (!empty($_POST)) {
            //equally list to array push
            $list[] = "{$_POST['item_to_add']}";
        }
        if (isset($_GET['removeIndex'])) {
            $remove = $_GET['removeIndex'];
            unset($list[$remove]);
            $list = array_values($list);
        }
        
        // Output List
        foreach ($list as $index => $value) {
            echo "<li>{$value} <a href=\"new.php?removeIndex={$index}\">Remove Item</a></li>";
        }
        saveFile($list);
    ?>
    </ul>
</body>
</html>