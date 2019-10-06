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
  $sql = "SELECT * FROM tbl_artist WHERE artName LIKE \"%" . $query . "%\"";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $stmt->bind_result($artID, $artname);

?>

<!DOCTYPE html>
<html>

  <head>
    <title>Music Catalogue: Artists</title>
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
        <li><a href="artists.php" class="active">Artists</a></li>
        <li><a href="albums.php">Albums</a></li>
        <li><a href="tracks.php">Tracks</a></li>
      </ul>
      <!--holds the main content for the webpage-->
      <div class="data">
        <h1>Artists</h1>
        <hr>
        <!--holds search form and add artist button-->
        <div class="btnhold">
          <!-- search box to filter artists -->
          <form id="filter" action="artists.php" method="post">
            <input type="text" value="search" class="input" name="search" onclick="clearText(this)" />
          </form>
          <!--link to edit artist page to create new artist-->
          <a href="edit_artist.php" class="btn">Add Artist</a>
        </div>
        <div class="details">
          <!-- table holding details for artists in database -->
          <table id="tbl">
            <tr>
              <th>Artist Name</th>
              <th></th>
            </tr>
            <?php
              //populates table with rows from database matching query in PHP above
              while ($stmt->fetch()) {
                echo "<tr>";
                //displays artist's name in cell
                echo "<td>" . htmlentities($artname) . "</td> ";
                //sets hidden form element to be the artist ID of artist in row
                echo "<td>";
                echo "<form action=\"edit_artist.php\" method=\"post\" class=\"edit\">";
                echo "<input type=\"hidden\" name=\"partid\" value=\"" . htmlentities($artID) . "\">";
                //displays links to edit an artist and view albums
                echo "<input type=\"submit\" class=\"ebtn\" value=\"Edit Artist\"></form> || <a href=\"albums.php\">View Albums</a></td>";
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
