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
foreach($averageData as $data){
  foreach($data as $month){
    $tempData[] = $month['tempAvg'];
  }
}
?>
<!DOCTYPE html>
<meta charset="utf-8">
<style>

.chart rect {
  fill: steelblue;
}

.chart text {
  fill: white;
  font: 10px sans-serif;
  text-anchor: end;
}

</style>
<svg class="chart"></svg>
<script src="//d3js.org/d3.v3.min.js" charset="utf-8"></script>
<script>

var data = <?php echo("[" . implode(", ", $tempData) . "]"); ?>;

var width = 420,
    barHeight = 20;

var x = d3.scale.linear()
    .domain([0, d3.max(data)])
    .range([0, width]);

var chart = d3.select(".chart")
    .attr("width", width)
    .attr("height", barHeight * data.length);

var bar = chart.selectAll("g")
    .data(data)
  .enter().append("g")
    .attr("transform", function(d, i) { return "translate(0," + i * barHeight + ")"; });

bar.append("rect")
    .attr("width", x)
    .attr("height", barHeight - 1);

bar.append("text")
    .attr("x", function(d) { return x(d) - 3; })
    .attr("y", barHeight / 2)
    .attr("dy", ".35em")
    .text(function(d) { return d; });

</script>
