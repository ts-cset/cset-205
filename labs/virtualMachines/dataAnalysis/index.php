<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

<?php
function loadCsv($path) {
    $csvFile = file($path);
    $csv = array_map('str_getcsv', $csvFile);
    array_walk($csv, function(&$a) use ($csv) {
      $a = array_combine($csv[0], $a);
    });
    array_shift($csv); # remove column header
    return $csv;
}
function calculateAverages($data) {
  $averagedData = [];
  forEach($data as $dataPoint){
    $date = DateTime::createFromFormat("Y-m-d", $dataPoint['DATE']);

    $year = $date->format("Y");
    $month = $date->format("m");

    if(!$averagedData[$year]){
      $averagedData[$year] = [];
    }
    if(!$averagedData[$year][$month]){
       $averagedData[intval($year)][intval($month)] = [
         'tempTotal'=>intval($dataPoint['TAVG']),
         'prcpTotal'=> intval($dataPoint['PRCP']),
         'tempAvg'=>intval($dataPoint['TAVG']),
         'prcpAvg'=> intval($dataPoint['PRCP']),
         'numDays' => 1];
    } else {
       $existingMonthData = $averagedData[$year][$month];
       $averagedData[$year][$month]['numDays']++;

       $averagedData[$year][$month]['tempTotal'] = $averagedData[$year][$month]['tempTotal'] + intval($dataPoint['TAVG']);
       $averagedData[$year][$month]['prcpTotal'] = $averagedData[$year][$month]['prcpTotal'] + intval($dataPoint['PRCP']);
       $averagedData[$year][$month]['tempAvg'] = $averagedData[$year][$month]['tempTotal'] / $averagedData[$year][$month]['numDays'];
       $averagedData[$year][$month]['prcpAvg'] = $averagedData[$year][$month]['prcpTotal'] / $averagedData[$year][$month]['numDays'];

    }
  }
  return $averagedData;
}
$tempData = loadCsv('http://localhost/dataAnalysis/miami-temperature.csv');
$precipData = loadCsv('http://localhost/dataAnalysis/miami-precipitation.csv');
$mergedData = [];
$i = 0;
foreach($tempData as $temp) {
  $mergedData[] = array_merge($temp, $precipData[$i]);
  $i++;
}

$averageData = calculateAverages($mergedData);

?>
<h1>Miami</h1>
<table class="table">
  <tr>
    <th>Year</th>
    <th>Month</th>
    <th>Temp</th>
    <th>Precip</th>
  </tr>
<?php
foreach($averageData as $year => $data) {
  for($i = 01; $i <= 12; $i++){
    $temp = $data[$i]['tempAvg'];
    $precip = $data[$i]['prcpAvg'];
    echo("<tr>");
    echo("<td>$year</td>");
    echo("<td>$i</td>");
    echo("<td>$temp</td>");
    echo("<td>$precip</td>");
    echo("</tr>");
  }
}
?>
</table>
