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
echo "<h1>People</h1>";
echo "<table class='table'>";
echo "<tr><th>Name</th><th>Age</th><th>Occupation<th>Actions</th></th>";
foreach($people as $person) {
  echo "<tr>";
  echo "<td>".$person["name"]."</td><td>".$person["age"]."</td><td>".$person["occupation"]."</td>";
  echo "<td><a href='http://192.168.33.10/slimExampleClient/person.php?id=".$person["id"]."'>Details</a></td>";
  echo "</tr>";
}
echo "</table>";
