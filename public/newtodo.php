<?php

// Get new instance of PDO object
$dbc = new PDO('mysql:host=127.0.0.1;dbname=TODO', 'amanda', 'mysecretpassword');
// Tell PDO to throw exceptions on error
$dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$error = '';


if (!empty($_GET)) 
{
    $page = $_GET['page'];
} 
else 
{
    $page = 1;
}

$pageNext = $page + 1;
$pagePrev = $page - 1;

try 
{
    if (isset($_POST['todoitem'])) 
    {
        $stringLength = strlen(($_POST['todoitem']));
        if ($stringLength == 0) 
        {
            throw new InvalidInputException('An item must be entered!');
        }
        if ($stringLength >= 240) 
        {
            throw new InvalidInputException('Item must be less than 240 characters. Try again!');
        } 
        else 
        {
        //inputing new items into database
            $stmt = $dbc->prepare('INSERT INTO todo_list (todo) VALUES (:todo)');
            $stmt->bindValue(':todo', $_POST['todoitem'], PDO::PARAM_STR);
            $stmt->execute();
        }
    }
    if (isset($_POST['remove'])) 
    {
            $idToRemove = $_POST['remove'];
            $stmt = $dbc->prepare('DELETE FROM todo_list WHERE id = ?');
            $stmt->execute(array($idToRemove));

    }
} 
catch(InvalidInputException $e) 
{
    $msg = $e->getMessage() . PHP_EOL;
}

$limit = 10;
$offset = (($limit * $page) - $limit); 
$count = $dbc->query('SELECT * FROM todo_list');
$stmt = $dbc->prepare("SELECT * FROM todo_list LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$newitems = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Todo List</title>
    <link rel="stylesheet" href="todo_style.css">
</head>
<body>
    
    <? if (isset($msg)): ?>  
        <p> <?= $msg;?> </p>
    <? endif; ?>
    
    <h1>Todo List</h1>

    <? if (isset($error)) : ?>  
        <?= "<p>{$error}</p>";?>
    <? endif; ?>

    <table class='flower'>
        <? foreach ($newitems as $index => $items) :?>
            <tr>
                <td>
                    <?= htmlspecialchars(strip_tags($items['todo']));?>
                </td>
                <td>
                    <button class='btn-remove' data-todo="<?= $items['id']; ?>">Remove</button>
                </td>
            </tr>
        <? endforeach; ?>
    </table>

    <ul>
        <? if ($pagePrev > 0) : ?> 
            <li>
                <?= "<a href='?page=$pagePrev'>Previous</a>";?>
            </li>
        <? endif ?> 
        <? if ($count->rowCount() > ($offset + $limit)) : ?> 
            <li>
                <?= "<a href='?page=$pageNext'>Next</a>";?>
            </li>
        <? endif ?>
    </ul>

    <h1>Add an item to do the todo list:</h1>

    <form method="POST" action="#">
        <p>
            <label for="todoitem">Add Todo Item</label>
            <input id="todoitem" name="todoitem" type="text" placeholder="Enter Your Item">
        </p>
            <button type="submit">Submit</button>
        </p>
    </form>
    
    <form id="remove-form" action="#" method="post">
         <input id="remove-id" type="hidden" name="remove" value="">
    </form>
    
    <h1>Upload File</h1>

    <form method="POST" enctype="multipart/form-data" action="#">
        <p>
            <label for="file1">File to upload: </label>
            <input type="file" id="file1" name="file1">
        </p>
        <p>
            <input type="submit" value="Upload">
        </p>
    </form>

    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script>

        $('.btn-remove').click(function () 
        {
            var todoId = $(this).data('todo');
            if (confirm('Are you sure you want to remove item ' + todoId + '?')) 
            {
                $('#remove-id').val(todoId);
                $('#remove-form').submit();
            }
        });
    </script>
</body>
</html>