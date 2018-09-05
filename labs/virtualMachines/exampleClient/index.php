<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<?php

//Initialize cURL.
$ch = curl_init();

//Set the URL that you want to GET by using the CURLOPT_URL option.
curl_setopt($ch, CURLOPT_URL, 'http://localhost/firstSlim/people');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
$people = json_decode($response, true);
?>
<h1>People</h1>
      <table class='table'>
      <tr><th>Name</th><th>Age</th><th>Occupation<th>Actions</th></th>
<?php
foreach($people as $person) {
  echo "<tr>
        <td>".$person["name"]."</td><td>".$person["age"]."</td><td>".$person["occupation"]."</td>
        <td><a href='http://localhost:8080/exampleClient/person.php?id=".$person["id"]."'>Details</a></td>
        </tr>";
}
echo "</table>";
