<?php

require_once 'pdo.php';
require_once 'util.php';
session_start();
$stmt=$pdo->query("SELECT * FROM Profile");
$stmt->execute();

?>
<html>

  <head>
    <title>Azharuddin Khan 's Resume Registry</title>
  <?php  require_once 'head.php'; ?>
  </head>

  <body>
    <div class="container">
    <h1>Azharuddin Khan 's Resume Registry</h1>
    <?php
flashMessages();
if (isset($_SESSION['user_id'])) {
    echo '  <a href="logout.php">Log out</a>';
} else {
    echo  ' <a href="login.php">Please log in</a>';
}
echo('<table border="1">'."\n");

if (isset($_SESSION['user_id'])) {
echo '<tr><th>';
echo "Name";
echo '</th><th>';
echo "Heading";
echo '</th><th>';
echo "Action";
echo '</th></tr>';
}
else{
  echo '<tr><th>';
  echo "Name";
  echo '</th><th>';
  echo "Headline";
  echo '</tr>';
}
while ($profiles=$stmt->fetch(PDO::FETCH_ASSOC)) {


    echo " <tr><td>     ";

    echo    '<a href="view.php?profile_id=';
      echo $profiles['profile_id'];

      echo '">';

    echo $profiles['first_name'];
    echo "  ";
    echo $profiles['last_name'] ;


    echo   ' </a>';

    echo "</td><td>    ";
    echo($profiles['headline']);

    echo "</td><td>";
    if (isset($_SESSION['user_id'])) {
        echo('<a href="edit.php?profile_id='
        .htmlentities($profiles['profile_id'])
  .'">Edit</a>      ');
        echo('<a href="delete.php?profile_id='
        .htmlentities($profiles['profile_id'])
  .'">Delete</a>');
    }
    echo("\n</form>\n");
    echo("</td></tr>\n");
}
echo '</table>';
if (isset($_SESSION['user_id'])) {
    echo '<a href="add.php">Add New Entry</a>';
}


?>
    </div>
  </body>

</html>
