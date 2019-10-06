<?php

  //ensures link to database available
  require_once 'db.php';

  //forms SQL query
  $squery = "SELECT COUNT(*) FROM tbl_artist;";
  $squery .= "SELECT COUNT(*) FROM tbl_cd;";
  $squery .= "SELECT COUNT(*) FROM tbl_track;";
  $data = array();

  //checks if multiquery works
  if ($conn->multi_query($squery)) {

    //initialises variable
    $i = 0;

    //enters loop
    while (true) {

      //checks if result available
      if ($result = $conn->store_result()) {

        //fetches row
        while ($row = $result->fetch_row()) {

          //sets array object at variable index to be result of query
          $data[$i] = $row[0];

        }

        //frees result
        $result->free();
      }

      //increments value by 1
      $i = $i + 1;

      //checks if there are more results
      if ($conn->more_results()) {

        //goes to next result
        $conn->next_result();

      } else {

        //breaks while loop
        break;
      }
    }
  }

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Music Catalogue: Home</title>
    <link href="https://fonts.googleapis.com/css?family=Josefin+Slab|Roboto+Condensed" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="catalog.css">
    <script type="text/javascript" src="script.js"></script>
    <link rel="shortcut icon" type="image/png" href="favicon.png" />
  </head>
  <body>
    <div class="content">
      <!--holds the navigation bar-->
      <ul class="menu">
        <li><a href="home.php" class="active">Home</a></li>
        <li><a href="artists.php">Artists</a></li>
        <li><a href="albums.php">Albums</a></li>
        <li><a href="tracks.php">Tracks</a></li>
      </ul>
      <!--holds the main content for the webpage-->
      <div class="data">
        <h1>Home</h1>
        <hr>
        <!--holds database details-->
        <div class="details">
          <p>The database contains:</p>
          <p><b><?php echo $data[0] ?></b> artist records</p>
          <p><b><?php echo $data[1] ?></b> album records</p>
          <p><b><?php echo $data[2] ?></b> track records</p>
        </div>
      </div>
    </div>
  </body>
</html>
