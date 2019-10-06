<?php

  //ensures link to database available
  include 'db.php';

  //links file storing validation functions
  require_once 'val.php';

  //initialises variables
  $error = "";
  $artID = NULL;
  $artname = "";
  $valfail = "";

  //checks if an artist id has been passed to form
  if (isset($_POST['partid'])) {

    //sets variable value
    $artID = $_POST['partid'];

    //writes SQL statement and prepares and executes the connection, binding results to variables
    $sql = "SELECT artName FROM tbl_artist WHERE artID = " . $artID . ";";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->bind_result($artname);
    $stmt->fetch();
  }

  //checks if details are to be saved
  if (isset($_POST['save'])) {

    //sets value of variables
    $artID = $_POST['aid'];
    $artname = fix_string($_POST['aname']);

    //runs validation on the variables
    $valfail = valartname($artname);

    //checks if input values were validated successfully
    if ($valfail == "") {

      //checks value of artist ID
      if ($artID == NULL) {

        //inserts details into table
        $query = "INSERT INTO tbl_artist(ArtName) VALUES (?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $artname);
        $result = $stmt->execute();

        //checks value of result to see if query run successfully
        if (!$result) {

          //sets error message
          $error = "Failed to insert record.";

        } else {

          //returns user to previous page
          header("Location: artists.php");
          exit;
        }
      } else {

        //updates artist details
        $query = "UPDATE tbl_artist SET artName=(?) WHERE artID=(?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $artname, $artID);
        $result = $stmt->execute();

        //checks value of result to see if query run successfully
        if (!$result) {

          //sets error message
          $error = "Failed to update record.";
        } else {

          //returns user to previous page
          header("Location: artists.php");
          exit;
        }

      }

    } else {

      //sets error message to say validation was failed
      $error = "PHP validation failed. The following errors were found:\n" . $valfail;

    }

    //checks if details are to be deleted
  } elseif (isset($_POST['delete'])) {

    //assigns value to variable
    $artID = $_POST['aid'];

    //checks value of artist ID
    if ($artID == NULL) {

      //sets error message
      $error =  "Cannot delete non-existent artist.";

    } else {

      //deletes record from table
      $query = "DELETE FROM tbl_artist WHERE artID=(?)";
      $stmt = $conn->prepare($query);
      $stmt->bind_param('s', $artID);
      $result = $stmt->execute();

      //checks value of result to see if query run successfully
      if (!$result) {

          //sets error message
          $error = "Failed to delete record.";

      } else {

        //returns user to previous page
        header("Location: artists.php");
        exit;
      }
    }
  }

?>

<!DOCTYPE html>
<html>

  <head>
    <title>Music Catalogue: Edit Artist</title>
    <link href="https://fonts.googleapis.com/css?family=Josefin+Slab|Roboto+Condensed" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="catalog.css">
    <script type="text/javascript" src="script.js"></script>
    <link rel="shortcut icon" type="image/png" href="favicon.png" />
  </head>

  <body>
    <div class="content">
      <!--holds the navigation bar-->
      <ul class="menu">
        <li><a href="home.php">Home</a></li>
        <li><a href="artists.php">Artists</a></li>
        <li><a href="albums.php">Albums</a></li>
        <li><a href="tracks.php">Tracks</a></li>
      </ul>
      <!--holds the main content for the webpage-->
      <div class="data">
        <h1>Edit Artist</h1>
        <hr>
        <div class="details">
          <!--holds form to edit artist details; runs js validation is js enabled-->
          <form action="edit_artist.php" method="post" onSubmit=" <?php if ($valfail) echo $valfail; else echo "return valart(this)"; ?>">
            <label>Artist Name: </label><input type="text" name="aname" value=<?php echo "\"" . htmlentities($artname) . "\""; ?> required>
            <!--hidden input storing artist ID-->
            <input type="hidden" name="aid" value=<?php echo "\"" . $artID ."\""; ?>><br>
            <!--holds save and delete buttons-->
            <div class="btnhold">
              <input type="submit" class="btn" name="save" value="Save">
              <input type="submit" class="btn" name="delete" value="Delete">
            </div>
          </form>
          <?php
            //checks if error is empty
            if ($error) {

              //displays error message
              echo "<div id=\"error\">";
              echo $error;
              echo "</div>";
            } ?>
          <!--returns user to prevous page-->
          <p class="back"><a href="artists.php">&#8701 return to artist listings</a></p>
        </div>
      </div>
    </div>
  </body>

</html>
