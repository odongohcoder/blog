<form autocomplete="off" method="post" enctype="multipart/form-data" name="contact">
  <input id="naam" name="naam" type="text" value="Naam" maxlength="255" onclick="if(this.value == this.defaultValue) this.value = ''" onfocus="if(this.value == this.defaultValue) this.value = ''" onblur="this.value=!this.value?'Naam':this.value;" />
  <input id="voornaam" name="voornaam" type="text" value="Voornaam" maxlength="255" onclick="if(this.value == this.defaultValue) this.value = ''" onfocus="if(this.value == this.defaultValue) this.value = ''" onblur="this.value=!this.value?'Voornaam':this.value;" />
  <input id="email" name="email" type="email" value="Email" maxlength="255" onclick="if(this.value == this.defaultValue) this.value = ''" onfocus="if(this.value == this.defaultValue) this.value = ''" onblur="this.value=!this.value?'Email':this.value;"/>
  <input id="telefoon" name="telefoon" type="text" value="Telefoon" maxlength="255" onclick="if(this.value == this.defaultValue) this.value = ''" onfocus="if(this.value == this.defaultValue) this.value = ''" onblur="this.value=!this.value?'Telefoon':this.value;"/>
  <textarea id="bericht" name="bericht" onclick="if(this.value == this.defaultValue) this.value = ''" onfocus="if(this.value == this.defaultValue) this.value = ''" onblur="this.value=!this.value?'Bericht':this.value;" >Bericht</textarea>
  <input type="submit" value="Verstuur" class="submit"/>
<?php
if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
  $naam = $_REQUEST['naam'];
  $email = $_REQUEST['email'];
  $telefoon = $_REQUEST['telefoon'];
  $bericht = $_REQUEST['bericht'];
  $voornaam = $_REQUEST['voornaam'];

  $message = $naam . "\r\n \r\n" . $email . "\r\n \r\n" . $telefoon . "\r\n \r\n" . "Bericht:" . "\r\n" . $bericht;
  $header = "From:" . $email . "\r\n" . "Reply-To:" . $email . "\r\n" . "X-Mailer: PHP/" . phpversion();

  if ($_POST['naam'] == "" || $_POST['email'] == "" || $_POST['bericht'] == "" || $_POST['naam'] == "Naam" || $_POST['email'] == "Email" || $_POST['bericht'] == "Bericht" || $_POST['voornaam'] != "Voornaam") {
    echo '<strong>Uw bericht kon niet verstuurt worden.</strong>';
  } else if ($_POST['voornaam'] == "Voornaam") {
    mail("studiomik@gmail.com", "Bericht via het contactformulier", $bericht, $header);
    echo '<strong>Bedankt voor uw bericht!</strong>';
  }
}
?>
</form>
