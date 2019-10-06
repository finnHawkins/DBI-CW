<?php

//validates input artist name
function valartname($data) {

  //checks if string is empty
  if ($data == "") {

    //returns error message
    return "The artist name cannot be empty.\n";

  } else if (strlen($data) > 255) {

    //returns error message
    return "The artist name is too long.\n";

  } else {

    //returns empty string as input is valid
    return "";
  }

}

//validates input cd name
function valcdname($data) {

  //checks if string is empty
  if ($data == "") {

    //returns error message
    return "The cd name cannot be empty.\n";

  //checks length of cd name
  } else if (strlen($data) > 255) {

    //returns error message
    return "The cd name is too long.\n";

  } else {

    //returns empty string as input is valid
    return "";
  }

}

//validates input cd price
function valprice($data) {

  //checks if input is a number between 0 and 250
  if (is_numeric($data) && $data > 0 && $data < 250) {

    //returns empty string as input is valid
    return "";

  } else {

    //returns error message
    return "Please ensure the price is a positive number between 1 and 250.\n";
  }

}

//validates input cd genre
function valgenre($data) {

  //checks if string is empty
  if ($data == "") {

    //returns error message
    return "The genre cannot be empty.\n";

  //checks length of genre input
  } else if (strlen($data) > 255) {

    //returns error message
    return "The input genre is too long.\n";

  } else {

    //returns empty string as input is valid
    return "";
  }

}

//validates input track count for album
function valtc($data) {

  //checks if input is a number between 0 and 100
  if (is_numeric($data) && $data > 0 && $data < 100) {

    //returns empty string as input is valid
    return "";

  } else {

    //returns error message
    return "Please ensure the track count is a positive number between 1 and 100.\n";
  }
}

//validates input track name
function valtrname($data) {

  //checks if string is empty
  if ($data == "") {

    //returns error message
    return "The track name cannot be empty.\n";

  //checks length of track name input
  } else if (strlen($data) > 255) {

    //returns error message
    return "The track name is too long.\n";

  } else {

    //returns empty string as input is valid
    return "";
  }

}

//validates input track length
function valtrlen($data) {

  //checks if input is a number between 0 and 1000
  if (is_numeric($data) && $data > 0 && $data < 1000) {

    //returns empty string as input is valid
    return "";

  } else {

    //returns error message
    return "Please ensure the track length is a positive number between 1 and 1000.\n";
  }
}

//ensures malicious code cannot be used
function fix_string($data) {

  //returns the data with any html elements as string
  return htmlentities($data);
}

 ?>
