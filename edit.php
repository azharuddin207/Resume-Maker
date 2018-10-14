<?php
 require_once 'pdo.php';
require_once 'util.php';


session_start();


if(! isset($_SESSION['user_id'])){
	echo "ACCESS DENIED";
  return;
}


if(isset($_POST['cancel'])){
  header('location:index.php');
  return;
}

if(! isset($_REQUEST['profile_id'])){
  $_SESSION['error']='Missing profile_id';
header('location:index.php');
return;
}



$stmt=$pdo->prepare('SELECT * FROM Profile
WHERE profile_id=:prof AND user_id=:uid');
$stmt->execute(array(
':prof'=>$_REQUEST['profile_id'],
':uid'=>$_SESSION['user_id']

));
$profile=$stmt->fetch(PDO::FETCH_ASSOC);
if($profile===false){
  $_SESSION['error']='could not load profile';
  header('location:index.php');
  return;
}


 if (isset($_POST['first_name'])&&
    isset($_POST['last_name']) &&
   isset($_POST['email']) &&
     isset($_POST['headline']) &&
      isset($_POST['summary'])
     ){


       $msg=validateProfile();
       if(is_string($msg)){
         $_SESSION['error']=$msg;
        header("location: edit.php?profile_id="
        .$_REQUEST['profile_id']);
         return;
       }
       $msg=validatePos();
       if(is_string($msg)){
         $_SESSION['error']=$msg;
        header("location: edit.php?profile_id="
        .$_REQUEST['profile_id']);
         return;
       }
//validae education;

     $stmt=$pdo->prepare ('UPDATE Profile SET  first_name=:fn,
   last_name=:ln,email=:em, headline=:he , summary=:su
    WHERE profile_id=:pid AND user_id=:uid') ;
     $stmt->execute(array(
     ':pid'=>$_REQUEST['profile_id'],
     ':uid'=>$_SESSION['user_id'],
     ':fn' => $_POST['first_name'],
     ':ln' => $_POST['last_name'],
     ':em' => $_POST['email'],
     ':he' => $_POST['headline'],
     ':su' => $_POST['summary']

   ));

   //clear out the old position entries
$stmt=$pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
$stmt->execute(array(':pid'=>$_REQUEST['profile_id']));
//insert the position entries
insertPositions($pdo,$_REQUEST['profile_id']);


//clear out the old education entries
$stmt=$pdo->prepare('DELETE FROM Education
WHERE profile_id=:pid');
$stmt->execute(array(':pid'=>$_REQUEST['profile_id']));

//insert the education entries
insertEducations($pdo,$_REQUEST['profile_id']);


     $_SESSION['success']='profile updated';
     header('location:index.php');
 }

$positions=loadPos($pdo,$_REQUEST['profile_id']);
$schools=loadEdu($pdo,$_REQUEST['profile_id']);
 ?>





<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Azharuddin Khan</title>
    <?php require_once 'head.php'; ?>
  </head>
  <body>
    <div class="container">
    <h1>Editing Profile for <?php echo  htmlentities($_SESSION['name']);?></h1>
      <?php
      flashMessages();
      ?>

         <form method="post" action="edit.php" >
          <input type="hidden" name="profile_id"
          value="<?=htmlentities($_GET['profile_id']);?>"/>
         <p>First Name:
         <input type="text" name="first_name" size="60"
         value="<?= htmlentities($profile['first_name']);?>"
         /></p>
         <p>Last Name:
         <input type="text" name="last_name" size="60"
         value="<?= htmlentities($profile['last_name']);?>"
         /></p>
         <p>Email:
         <input type="text" name="email" id="email" size="30"
         value="<?= htmlentities($profile['email']);?>"
         /></p>
         <p>Headline:<br/>
         <input type="text" name="headline" size="80"
         value="<?= htmlentities($profile['headline']);?>"
         /></p>
         <p>Summary:<br/>
         <textarea name="summary" rows="8" cols="80">
         <?= htmlentities($profile['summary']);?>
        </textarea>



  <?php

$countEdu=0;
echo ('<p>Education: <input
 type="submit" id="addEdu" value="+">'."\n");
echo('<div id="edu_fields">'."\n");
if(count($schools>0)){
  foreach($schools as $school){
    $countEdu++;
    echo('<div id="edu'.$countEdu.'">');
    echo
    '<p>Year: <input type="text"
     name="edu_year'.$countEdu.'
     "value="'.$school['year'].'"/>';
     echo ('<input type="button" value="-"');
     echo ('onclick="$(\'#edu'.$countEdu.'\'
     .remove();return false;">'."\n");
     echo ('</p>'."\n");
     echo('<p>school: <input type="text"
     size="90" name="edu_school'.$countEdu.'" class="school"
     value="'.htmlentities($school['name']).'"/>');
     echo "\n<div>\n";
  }

}
echo ("</div></p>\n");
$pos=0;
echo ('<p>Position: <input type="submit"
id="addPos" value="+">'."\n");
echo ('<div id="position_fields">'."\n");
foreach($positions as $position){
  $pos++;
  echo ('<div id="position'.$pos.'">'."\n");
  echo ('<p>Year: <input type="text" name="year'.$pos.'"' );
  echo ('value="'.htmlentities($position['year']).'"/>'."\n");
  echo ('<input type="button" value="-"');
  echo ('onclick="$(\'#position'.$pos.'\'
  .remove();return false;">'."\n");


  echo ('</p>'."\n");
  echo ('<textarea name="desc'.$pos.'"
  rows="8" cols="80">'."\n");
  echo (htmlentities($position['description'])."\n");
  echo ("\n</textarea>\n</div>\n");
}
echo ("</div></p>\n");

          ?>
         <p>
           <input type="submit" value="Save"></input>
        <input type="submit" value="cancel" name="cancel"></input>

         </p>
         </form>
         <script>
 countPos = <?=$pos?>;
 countEdu = <?=$countEdu?>;

 // http://stackoverflow.com/questions/17650776/add-remove-html-inside-div-using-javascript
 $(document).ready(function(){
     window.console && console.log('Document ready called');

     $('#addPos').click(function(event){
         // http://api.jquery.com/event.preventdefault/
         event.preventDefault();
         if ( countPos >= 9 ) {
             alert("Maximum of nine position entries exceeded");
             return;
         }
         countPos++;
         window.console && console.log("Adding position "+countPos);

         $('#position_fields').append(
             '<div id="position'+countPos+'"> \
             <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
             <input type="button" value="-" \
                 onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
             <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
             </div>');
     });

     $('#addEdu').click(function(event){
         event.preventDefault();
         if ( countEdu >= 9 ) {
             alert("Maximum of nine education entries exceeded");
             return;
         }
         countEdu++;
         window.console && console.log("Adding education "+countEdu);

         // Grab some HTML with hot spots and insert into the DOM
         var source  = $("#edu-template").html();
         $('#edu_fields').append(source.replace(/@COUNT@/g,countEdu));

         // Add the even handler to the new ones
         $('.school').autocomplete({
             source: "school.php"
         });

     });

     $('.school').autocomplete({
         source: "school.php"
     });

 });

 </script>
 <!-- HTML with Substitution hot spots -->
 <script id="edu-template" type="text">
   <div id="edu@COUNT@">
     <p>Year: <input type="text" name="edu_year@COUNT@" value="" />
     <input type="button" value="-" onclick="$('#edu@COUNT@').remove();return false;"><br>
     <p>School: <input type="text" size="80" name="edu_school@COUNT@" class="school" value="" />
     </p>
   </div>
 </script>
 </div>

       </body>
       </html>
