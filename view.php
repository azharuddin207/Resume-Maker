<?php
require_once 'pdo.php';

?>
<!DOCTYPE html>
<html>
<head>
<title>Azharuddin Khan's Profile View</title>
<!-- bootstrap.php - this is HTML -->
<?php require_once 'head.php'; ?>
</head>
<body>
  <?php

  $stmt = $pdo->prepare("SELECT * FROM Profile
    join
     Position on Profile.profile_id = Position.profile_id
      where Profile.profile_id = :pid");
  $stmt->execute(array(":pid" => $_REQUEST['profile_id']));

?>

<div class="container">
<h1>Profile information</h1>
  <?php $profiles=$stmt->fetch(PDO::FETCH_ASSOC) ?>
<p>First Name:
<?php echo $profiles['first_name'] ;?></p>
<p>Last Name:
<?php echo $profiles['last_name'] ;?></p>
<p>Email:
<?php echo $profiles['email'] ;?></p>
<p>Headline:<br/>
<?php echo $profiles['headline'] ;?></p>
<p>Summary:<br/>
<?php echo $profiles['summary'] ;
?></p>

<?php

$row=$pdo->prepare('SELECT * FROM Education
join Institution on
Education.institution_id=Institution.institution_id
  where profile_id = :pid');
    $row->execute(array(":pid" => $_REQUEST['profile_id']));
echo('<p>Education</p>');
while($rows=$row->fetch(PDO::FETCH_ASSOC)){
echo '<ul>';
  echo '<li>';
   echo $rows['year'].':'.$rows['name'];
   echo '</li>';
echo '</ul>';
}
echo('<p>Position</p>');
while($rows=$stmt->fetch(PDO::FETCH_ASSOC)){
  echo '<ul>';
    echo '<li>';
     echo $rows['year'].':'.$rows['description'];
     echo '</li>';
  echo '</ul>';
  }
?>
<a href="index.php">Done</a>

</div>
<script data-cfasync="false">

src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js">

</script>
</body>
</html>
