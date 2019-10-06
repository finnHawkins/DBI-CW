//clears textbox
function clearText(val) {

  //sets value of passed in html element to empty string
  val.value = "";
}

//validates edit artist form
function valart(frm) {

  //stores error message
  err = valartname(frm.aname.value);

  //if error message is empty, returns true
  //else shows error message and returns false
  if (err == "") {
    return true;
  } else {
    alert(err);
    return false;
  }
}

//validates edit album form
function valcd(frm) {

  //stores and creates error message
  err = valcdname(frm.cdname.value);
  err += valprice(frm.cdprice.value);
  err += valgenre(frm.cdgenre.value);
  err += valtc(frm.cdtcount.value);

  //if error message is empty, returns true
  //else shows error message and returns false
  if (err == "") {
    return true;
  } else {
    alert(err);
    return false;
  }
}

//validates edit track page
function valtr(frm) {

  //stores and creates error message
  err = valtrname(frm.tname.value);
  err += valtrlen(frm.tlength.value);

  //if error message is empty, returns true
  //else shows error message and returns false
  if (err == "") {
    return true;
  } else {
    alert(err);
    return false;
  }
}


//validates input artist name
function valartname(data) {

  //checks if string is empty
  if (data == "") {

    //returns error message
    return "The artist name cannot be empty.\n";

    //checks length of artist name
  } else if (data.length > 255) {

    //returns error message
    return "The artist name is too long\n";

  } else {

    //returns empty string as input is valid
    return "";
  }

}

//validates input cd name
function valcdname(data) {

  //checks if string is empty
  if (data == "") {

    //returns error message
    return "The cd name cannot be empty.\n";

  //checks length of cd name
  } else if (data.length > 255) {

    //returns error message
    return "The cd name is too long\n";

  } else {

    //returns empty string as input is valid
    return "";
  }

}

//validates input cd price
function valprice(data) {

  //checks if input is a number between 0 and 255
  if (!isNaN(data) && data > 0 && data < 250) {

    //returns empty string as input is valid
    return "";

  } else {

    //returns error message
    return "Please ensure the price is a positive number between 1 and 250.\n";
  }

}

//validates input cd genre
function valgenre(data) {

  //checks if input is empty string
  if (data == "") {

    //returns error message
    return "The genre cannot be empty.\n";

  //checks length of genre input
  } else if (data.length > 255) {

    //returns error message
    return "The input genre is too long\n";

  } else {

    //returns empty string as input is valid
    return "";
  }

}

//validates input track count for album
function valtc(data) {

  //checks if input is a number between 0 and 255
  if (!isNaN(data) && data > 0 && data < 100) {

    //returns empty string as input is valid
    return "";

  } else {

    //returns error message
    return "Please ensure the track count is a positive number between 1 and 100.\n";
  }
}

//validates input track name
function valtrname(data) {

  //checks if input is empty string
  if (data == "") {

    //returns error message
    return "The track name cannot be empty.\n";

  //checks length of track name input
  } else if (data.length > 255) {

    //returns error message
    return "The track name is too long\n";

  } else {

    //returns empty string as input is valid
    return "";
  }

}

//validates input track length
function valtrlen(data) {

  //checks if input is a number between 0 and 255
  if (!isNaN(data) && data > 0 && data < 1000) {

    //returns empty string as input is valid
    return "";

  } else {

    //returns error message
    return "Please ensure the track length is a positive number between 1 and 1000.";
  }
}
