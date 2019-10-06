<?php

  //ensures link to database available
  include 'db.php';

  //links file storing validation functions
  require_once 'val.php';

  //selects all albums from table to populate select box of form
  $lform = "SELECT cdID, cdTitle FROM tbl_cd";
  $stmt = $conn->prepare($lform);
  $stmt->execute();
  $stmt->store_result();
  $stmt->bind_result($cdID, $cdName);

  //initialises variables
  $error = "";
  $trID = NULL;
  $trname = $trcdid = $trlen = "";
  $valfail = "";

  //checks if a track id has been passed to form
  if (isset($_POST['ptid'])) {

    //sets variable value
    $trID = $_POST['ptid'];

    //writes SQL statement and prepares and executes the connection, binding results to variables
    $sql = "SELECT * FROM tbl_track WHERE trID = " . $trID . ";";
    $stmt1 = $conn->prepare($sql);
    $stmt1->execute();
    $stmt1->bind_result($trID, $trname, $trcdid, $traid, $trlen);
    $stmt1->fetch();
  }

  //checks if details are to be saved
  if (isset($_POST["save"])) {

    //sets value of variables
    $trID = $_POST['tid'];
    $trname = fix_string($_POST['tname']);
    $trcdid = fix_string($_POST['talbum']);
    $trlen = fix_string($_POST['tlength']);

    //runs validation on the variables
    $valfail = valtrname($trname);
    $valfail .= valtrlen($trlen);

    //checks if input values were validated successfully
    if ($valfail == "") {

      //checks value of track ID
      if ($trID == NULL) {

        //inserts details into table
        $query = "INSERT INTO tbl_track(trName, cdID, artID, trLen) VALUES (?, ?, (SELECT artID FROM tbl_cd WHERE cdID = ?), ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('siii', $trname, $trcdid, $trcdid, $trlen);
        $result = $stmt->execute();

        //checks value of result to see if query run successfully
        if (!$result) {

          //sets error message
          $error = "Failed to insert record.";

        } else {

          //returns user to previous page
          header("Location: tracks.php");
          exit;
        }

      } else {

        //updates track details
        $query = "UPDATE tbl_track SET trName=(?), cdID=(?), artID=(SELECT artID FROM tbl_cd WHERE cdID = ?), trLen=(?) WHERE trID=(?);";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('siiii', $trname, $trcdid, $trcdid, $trlen, $trID);
        $result = $stmt->execute();

        //checks value of result to see if query run successfully
        if (!$result) {

          //sets error message
          $error = "Failed to update record.";

        } else {

          //returns user to previous page
          header("Location: tracks.php");
          exit;
        }

      }
    } else {

      //sets error message to say validation was failed
      $error = "PHP validation failed. The following errors were found:\n" . $valfail;

    }

    //checks if details are to be deleted
  } elseif (isset($_POST["delete"])) {

    //assigns value to variable
    $trID = $_POST['tid'];

    //checks value of track ID
    if ($trID == NULL) {

      //sets error message
      $error =  "Cannot delete non-existent track.";

    } else {

      //deletes record from table
      $query = "DELETE FROM tbl_track WHERE trID=(?)";
      $stmt = $conn->prepare($query);
      $stmt->bind_param('s', $trID);
      $result = $stmt->execute();

      //checks value of result to see if query run successfully
      if (!$result) {

        //sets error message
        $error = "Failed to delete record.";

      } else {

        //returns user to previous page
        header("Location: tracks.php");
        exit;
      }
    }
  }

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Music Catalogue: Edit Track</title>
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
        <div class="title">
          <h1>Edit Track</h1>
          <hr>
        </div>
        <div class="details">
          <!--holds form to edit track details; runs js validation is js enabled-->
          <form action="edit_track.php" method="post" onSubmit=" <?php if ($valfail) echo $valfail; else echo "return valtr(this)"; ?>">
            <!--hidden input storing track ID-->
            <input type="hidden" name="tid" value=<?php echo "\"" . $trID . "\""; ?>><br>
            <label>Track Name: </label><input type="text" name="tname" value=<?php echo "\"" . $trname . "\""; ?> required><br>
            <label>Album: </label><select name="talbum" required>
            <?php
              //loops through results returned by selecting albums
              while ($stmt->fetch()) {
                //sets item value to the album ID
                echo "<option value=\"" . htmlentities($cdID) ."\"";
                //if the album ID is that for the track, makes it selected
                if ($cdID == $trcdid) echo "selected";
                echo ">";
                //outputs artist name
                echo htmlentities($cdName);
                echo "</option>";
              }
              ?>
            </select><br>
            <label>Track Duration: </label><input type="number" name="tlength" value=<?php echo "\"" . $trlen . "\""; ?>><br>
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
          <p class="back"><a href="tracks.php">&#8701 return to track listings</a></p>
        </div>
      </div>
    </div>
  </body>
</html>
