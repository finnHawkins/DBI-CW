<?php

  //ensures link to database available
  require_once 'db.php';

  //initialises variable
  $query = "";

  //checks if search has been performed
  if (isset($_GET['search'])) {
    $query = $_GET['search'];
  }

  //writes SQL statement and prepares and executes the connection, binding results to variables
  $sql = "SELECT t.trID, t.trName, c.cdTitle, a.artName, t.trLen FROM (tbl_artist a NATURAL JOIN tbl_cd c NATURAL JOIN tbl_track t) WHERE t.trName LIKE \"%" . $query . "%\"";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $stmt->bind_result($tid, $tname, $cname, $aname, $tlen);

?>

<!DOCTYPE HTML>
<html>

  <head>
    <title>Music Catalogue: Tracks</title>
    <link href="https://fonts.googleapis.com/css?family=Josefin+Slab|Roboto+Condensed" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="catalog.css">
    <script src="script.js"></script>
    <link rel="shortcut icon" type="image/png" href="favicon.png"/>
  </head>

  <body>
    <div class="content">
      <!--holds the navigation bar-->
      <ul class="menu">
        <li><a href="home.php">Home</a></li>
        <li><a href="artists.php">Artists</a></li>
        <li><a href="albums.php">Albums</a></li>
        <li><a href="tracks.php" class="active">Tracks</a></li>
      </ul>
      <!--holds the main content for the webpage-->
      <div class="data">
          <h1>Tracks</h1>
          <hr>
        <!--holds search form and add track button-->
        <div class="btnhold">
          <!-- search box to filter tracks -->
          <form id="filter" action="tracks.php">
            <input type="text" value="search" class="input" name="search" onclick="clearText(this)"/>
          </form>
          <!--link to edit track page to create new track-->
          <a href="edit_track.php" class="btn">Add Track</a>
        </div>
        <div class="details">
          <!-- table holding details for tracks in database -->
          <table id="tbl">
            <tr>
              <th>Title</th>
              <th>Album</th>
              <th>Artist</th>
              <th>Duration</th>
              <th></th>
            </tr>
            <?php
              //populates table with rows from database matching query in PHP above
              while ($stmt->fetch()) {
                echo "<tr>";
                //displays track details in row cells
                echo "<td>" . htmlentities($tname) . "</td> ";
                echo "<td>" . htmlentities($cname) . "</td> ";
                echo "<td>" . htmlentities($aname) . "</td> ";
                echo "<td>" . htmlentities($tlen) . "</td> ";
                //declares new form to edit an track
                echo "<td><form action=\"edit_track.php\" method=\"post\" class=\"edit\">";
                //sets hidden form element to be the track ID of track in row
                echo "<input type=\"hidden\" name=\"ptid\" value=\"" . htmlentities($tid) . "\" readonly>";
                //displays links to edit an track
                echo "<input type=\"submit\" class=\"ebtn\" value=\"Edit Track\"></form></td>";
                echo "</tr>";
              }
              ?>
          </table>
          <br>
        </div>
      </div>
    </div>
  </body>
</html>
