<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<style>

rect {
  fill: steelblue;
}

text {
  fill: white;
  font: 10px sans-serif;
  text-anchor: end;
}

</style>
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
         'tempTotal'=>intval($dataPoint['TMAX']),
         'prcpTotal'=> intval($dataPoint['PRCP']),
         'tempAvg'=>intval($dataPoint['TMAX']),
         'prcpAvg'=> intval($dataPoint['PRCP']),
         'numDays' => 1];
    } else {
       $existingMonthData = $averagedData[$year][$month];
       $averagedData[$year][$month]['numDays']++;

       $averagedData[$year][$month]['tempTotal'] = $averagedData[$year][$month]['tempTotal'] + intval($dataPoint['tmax']);
       $averagedData[$year][$month]['prcpTotal'] = $averagedData[$year][$month]['prcpTotal'] + intval($dataPoint['PRCP']);
       $averagedData[$year][$month]['tempAvg'] = $averagedData[$year][$month]['tempTotal'] / $averagedData[$year][$month]['numDays'];
       $averagedData[$year][$month]['prcpAvg'] = $averagedData[$year][$month]['prcpTotal'] / $averagedData[$year][$month]['numDays'];

    }
  }
  return $averagedData;
}
$tempData = loadCsv('http://localhost/dataAnalysis/miami-max.csv');
// $tempData = loadCsv('http://localhost/dataAnalysis/miami-temperature.csv');
$precipData = loadCsv('http://localhost/dataAnalysis/miami-precipitation.csv');
$mergedData = [];
$i = 0;
foreach($tempData as $temp) {
  $mergedData[] = array_merge($temp, $precipData[$i]);
  $i++;
}

$averageData = calculateAverages($mergedData);
$tempData = [];
// $tempData = "[";
foreach($averageData as $data){
  foreach($data as $month){
    $tempData[] = $month['tempAvg'];
    // $tempAvg = $month['tempAvg'];
    // if($tempData == "[") {
    //   $tempData = $tempData . "$tempAvg";
    // } else {
    //   $tempData = $tempData . ", $tempAvg";
    // }
  }
}
// echo("<input id='temp' type='hidden' value='".implode(", ", $tempData)."' />");

// $tempData = $tempData . "]";
// var_dump($tempData);
// $tempData = '[80, 100, 56, 120, 180, 30, 40, 120, 160]';
// $jsonTempData = json_encode($tempData);
?>
<h1>Miami</h1>
<html>
  <head>
    <link rel="stylesheet" href="index.css">
  </head>
  <body>
    <svg></svg>
    <script src="https://d3js.org/d3.v4.min.js"></script>
    <script>
    var svgWidth = 5000;
    var svgHeight = 300;
    var svg = d3.select('svg')
        .attr("width", svgWidth)
        .attr("height", svgHeight)
        .attr("class", "bar-chart");

        var dataset = <?php echo("[" . implode(", ", $tempData) . "]"); ?>;
        // var dataset = document.getElementById('temp').value;
        console.log(dataset);
        var barPadding = 5;
        var barWidth = (svgWidth / dataset.length);
        var barChart = svg.selectAll("rect")
            .data(dataset)
            .enter()
            .append("rect")
            .attr("y", function(d) {
                return svgHeight - d
            })
            .attr("height", function(d) {
                return d;
            })
            .attr("width", barWidth - barPadding)
            .attr("transform", function (d, i) {
                 var translate = [barWidth * i, 0];
                 return "translate("+ translate +")";
            });
      </script>
  </body>
</html>

<!-- <table class="table">
  <tr>
    <th>Year</th>
    <th>Month</th>
    <th>Temp</th>
    <th>Precip</th>
  </tr>
<?php
// foreach($averageData as $year => $data) {
//   for($i = 01; $i <= 12; $i++){
//     $temp = $data[$i]['tempAvg'];
//     $precip = $data[$i]['prcpAvg'];
//     echo("<tr>");
//     echo("<td>$year</td>");
//     echo("<td>$i</td>");
//     echo("<td>$temp</td>");
//     echo("<td>$precip</td>");
//     echo("</tr>");
//   }
// }
?>
</table> -->
