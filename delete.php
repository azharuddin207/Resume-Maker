<?php
require_once 'pdo.php';
$pid=$_GET['profile_id'];
if(isset($_POST['cancel'])){
  header('location:index.php');
  return;
}

if (isset($_POST['profile_id']) &&
  isset($_POST['delete'])) {
    $stmt=$pdo->query('SELECT * FROM Profile WHERE profile_id=:xyz');
    $stmt->execute(array(':xyz'=>$_REQUEST['profile_id']));
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    if ($row===false) {
        echo $_SESSION['error']="bad value for id";
        header("location:index.php");
        return;
    }
    $stmt=$pdo->prepare('DELETE FROM Profile WHERE profile_id=:zip');

    $stmt->execute(array(
            ':zip'=>$_REQUEST['profile_id']));
        $_SESSION['success']='profile deleted';
        header("location:index.php");



    echo '<h1>Deleteing Profile</h1></br>';
    echo 'First Name:'.htmlentities($row['first_name']);
    echo '</br>';
    echo 'Last Name:'.htmlentities($row['Last_name']);
    echo '</br>';
}
echo '<h1>Deleteing Profile</h1></br>';
echo 'First Name:'.htmlentities($row['first_name']);
echo '</br>';
echo 'Last Name:'.htmlentities($row['Last_name']);
echo '</br>';
echo('<form method="post"><input type="hidden" ');
echo('name="profile_id" value="'.$_GET['profile_id'].'">'."\n");
echo('<input type="submit" value="Del" name="Delete">');
echo('<input type="submit" value="cancel" name="cancel">');
echo("\n</form<\n");

  ?>
  </body>
</html>
