<?php

  //ensures link to database available
  require_once 'db.php';

  //initialises variable
  $query = "";

  //checks if search has been performed
  if (isset($_POST['search'])) {

    //gets value of search query
    $query = $_POST['search'];
  }

  //writes SQL statement and prepares and executes the connection, binding results to variables
  $sql = "SELECT * FROM tbl_artist a NATURAL JOIN tbl_cd c WHERE cdTitle LIKE \"%" . $query . "%\" AND a.artID = c.artID";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $stmt->bind_result($artID, $artname, $cdID, $cdname, $cdprice, $cdgenre, $cdtracks);

?>

<!DOCTYPE html>
<html>

  <head>
    <title>Music Catalogue: Albums</title>
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
        <li><a href="albums.php" class="active">Albums</a></li>
        <li><a href="tracks.php">Tracks</a></li>
      </ul>
      <!--holds the main content for the webpage-->
      <div class="data">
          <h1>Albums</h1>
          <hr>
        <!--holds search form and add album button-->
        <div class="btnhold">
          <!-- search box to filter albums -->
          <form id="filter" action="albums.php" method="post">
            <input type="text" value="search" class="input" name="search" onclick="clearText(this)"/>
          </form>
          <!--link to edit album page to create new album-->
          <a href="edit_album.php" class="btn">Add Album</a>
        </div>
        <div class="details">
          <!-- table holding details for CDs in database -->
          <table id="tbl">
            <tr>
              <th>Title</th>
              <th>Artist</th>
              <th>Genre</th>
              <th>Tracks</th>
              <th>Price</th>
              <th></th>
            </tr>
            <?php
              //populates table with rows from database matching query in PHP above
              while ($stmt->fetch()) {
                echo "<tr>";
                //displays album details in row cells
                echo "<td>" . htmlentities($cdname) . "</td> ";
                echo "<td>" . htmlentities($artname) . "</td> ";
                echo "<td>" . htmlentities($cdgenre) . "</td> ";
                echo "<td>" . htmlentities($cdtracks) . "</td> ";
                echo "<td>" . htmlentities($cdprice) . "</td> ";
                //declares new form to edit an album
                echo "<td><form action=\"edit_album.php\" method=\"post\" class=\"edit\">";
                //sets hidden form element to be the album ID of album in row
                echo "<input type=\"hidden\" name=\"pcdid\" value=\"" . htmlentities($cdID) . "\" readonly>";
                //displays links to edit an album and view tracks
                echo "<input type=\"submit\" class=\"ebtn\" value=\"Edit Album\"></form> || <a href=\"tracks.php\">View Tracks</a></td>";
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
