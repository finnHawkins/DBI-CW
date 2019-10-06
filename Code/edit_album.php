<?php

  //ensures link to database available
  include 'db.php';

  //links file storing validation functions
  require_once 'val.php';

  //selects all artists from table to populate select box of form
  $lform = "SELECT * FROM tbl_artist";
  $stmt = $conn->prepare($lform);
  $stmt->execute();
  $stmt->store_result();
  $stmt->bind_result($artID, $artname);

  //initialises variables
  $error = "";
  $cdID = NULL;
  $cdname = $cdaid = $cdgenre = $cdtcount = $cdcost = $cdID = "";
  $valfail = "";

  //checks if an album id has been passed to form
  if (isset($_POST['pcdid'])) {

    //sets variable value
    $cdID = $_POST['pcdid'];

    //writes SQL statement and prepares and executes the connection, binding results to variables
    $sql = "SELECT * FROM tbl_cd WHERE cdID = " . $cdID . ";";
    $stmt1 = $conn->prepare($sql);
    $stmt1->execute();
    $stmt1->bind_result($cdID, $cdaid, $cdname, $cdcost, $cdgenre, $cdtcount);
    $stmt1->fetch();
  }

  //checks if details are to be saved
  if (isset($_POST['save'])) {

    //sets value of variables
    $cdname = fix_string($_POST['cdname']);
    $cdaid = fix_string($_POST['cdartist']);
    $cdgenre = fix_string($_POST['cdgenre']);
    $cdtcount = fix_string($_POST['cdtcount']);
    $cdcost = fix_string($_POST['cdprice']);
    $cdID = $_POST['cdid'];

    //runs validation on the variables
    $valfail = valcdname($cdname);
    $valfail .= valprice($cdcost);
    $valfail .= valgenre($cdgenre);
    $valfail .= valtc($cdtcount);

    //checks if input values were validated successfully
    if ($valfail == "") {

      //checks value of album ID
      if ($cdID == NULL) {

        //inserts details into table
        $query = "INSERT INTO tbl_cd(artID, cdTitle, cdPrice, cdGenre, cdNumTracks) VALUES (?,?,?,?,?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('isdsi', $cdaid, $cdname, $cdcost, $cdgenre, $cdtcount);
        $result = $stmt->execute();

        //checks value of result to see if query run successfully
        if (!$result) {

          //sets error message
          $error = "Failed to insert record.";

        } else {

          //returns user to previous page
          header("Location: albums.php");
          exit;
        }

      } else {

        //updates album details
        $query = "UPDATE tbl_cd SET artID=(?), cdTitle=(?), cdPrice=(?), cdGenre=(?), cdNumTracks=(?) WHERE cdID=(?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('isdsii', $cdaid, $cdname, $cdcost, $cdgenre, $cdtcount, $cdID);
        $result = $stmt->execute();

        //checks value of result to see if query run successfully
        if (!$result) {

          //sets error message
          $error = "Failed to update record.";

        } else {

          //returns user to previous page
          header("Location: albums.php");
          exit;
        }

      }
    }  else {

      //sets error message to say validation was failed
      $error = "PHP validation failed. The following errors were found:\n" . $valfail;

    }

    //checks if details are to be deleted
  } elseif (isset($_POST['delete'])) {

    //assigns value to variable
    $cdID = $_POST['cdid'];

    //checks value of album ID
    if ($cdID == NULL) {

      //sets error message
      $error =  "Cannot delete non-existent album.";

    } else {

      //deletes record from table
      $query = "DELETE FROM tbl_cd WHERE cdID=(?)";
      $stmt = $conn->prepare($query);
      $stmt->bind_param('i', $cdID);
      $result = $stmt->execute();

      //checks value of result to see if query run successfully
      if (!$result) {

        //sets error message
        $error = "Failed to delete record.";

      } else {

        //returns user to previous page
        header("Location: albums.php");
        exit;
      }
    }
  }

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Music Catalogue: Edit Album</title>
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
          <h1>Edit Album</h1>
          <hr>
        </div>
        <div class="details">
          <!--holds form to edit album details; runs js validation is js enabled-->
          <form action="edit_album.php" method="post" onSubmit=" <?php if ($valfail) echo $valfail; else echo "return valcd(this)"; ?>">
            <!--hidden input storing album ID-->
            <input type="hidden" name="cdid" value=<?php echo "\"" . $cdID . "\""; ?>><br>
            <label>Album Name: </label><input type="text" name="cdname" value=<?php echo "\"" . htmlentities($cdname) . "\""; ?> required>
            <label>Album Artist: </label><select name="cdartist" required>
            <?php
              //loops through results returned by selecting artists
              while ($stmt->fetch()) {
                //sets item value to the artist ID
                echo "<option value=\"" . htmlentities($artID) ."\"";
                //if the artist ID is that for the cd, makes it selected
                if ($artID == $cdaid) echo "selected";
                echo ">";
                //outputs artist name
                echo htmlentities($artname);
                echo "</option>";
              }
              ?>
            </select><br>
            <label>Genre: </label><input type="text" name="cdgenre" value=<?php echo "\"" . htmlentities($cdgenre) . "\""; ?>><br>
            <label>Track Count: </label><input type="text" name="cdtcount" value=<?php echo "\"" . htmlentities($cdtcount) . "\""; ?>><br>
            <label>Price: </label><input type="text" name="cdprice" value=<?php echo "\"" . htmlentities($cdcost) . "\""; ?>><br>
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
          <p class="back"><a href="albums.php">&#8701 return to album listings</a></p>
        </div>
      </div>
    </div>
  </body>
</html>
