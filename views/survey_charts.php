<!doctype html>
<html>
<head>
  <title>Survey Results</title>
  <?php include 'stylesheets.php'; ?>
  <link rel="stylesheet" type="text/css" href="vendor/jqplot/jquery.jqplot.min.css" />
  <?php include 'scripts.php'; ?>
  <script type="text/javascript" src="vendor/jquery/js/jquery-migrate-1.1.1.min.js"></script>
  <script type="text/javascript" src="vendor/jqplot/jquery.jqplot.min.js"></script>
  <script type="text/javascript" src="vendor/jqplot/plugins/jqplot.categoryAxisRenderer.min.js"></script>
  <script type="text/javascript" src="vendor/jqplot/plugins/jqplot.barRenderer.min.js"></script>
  <script type="text/javascript" src="vendor/jqplot/plugins/jqplot.pointLabels.min.js"></script>
  <script type="text/javascript">
    $(function()
    {
        <?php $i = 1; ?>
        <?php foreach ($survey->questions as $question): ?>
        var line<?php echo $i; ?> = <?php echo json_encode($question->choice_counts); ?>;
        var plot<?php echo $i; ?> = $('#chart<?php echo $i; ?>').jqplot([line<?php echo $i; ?>], {
            title: <?php echo json_encode($question->question_text); ?>,
            seriesDefaults: {
                renderer: $.jqplot.BarRenderer,
                rendererOptions: {
                    // Set the varyBarColor option to true to use different colors for each bar.
                    // The default series colors are used.
                    varyBarColor: true
                },
                pointLabels: {
                    show: true
                }
            },
            axes:{
                xaxis: {
                    renderer: $.jqplot.CategoryAxisRenderer
                },
                yaxis: {
                    min: 0, 
                    max: <?php echo $question->max_answer_count + 1; ?>
                }
            }
        });
        <?php ++$i; ?>
        <?php endforeach; ?>
    });
  </script>
</head>
<body>
  <div id="main">
    <?php include 'header.php'; ?>
    <div id="site_content">
      <h1><?php echo htmlspecialchars($survey->survey_name); ?></h1>
      <div id="content">
        <?php $i = 1; ?>
        <?php foreach ($survey->questions as $question): ?>
        <div style="margin-bottom: 20px;" id="chart<?php echo $i; ?>"></div>
        <?php ++$i; ?>
        <?php endforeach; ?>
      </div>
    </div>
    <?php include 'footer.php'; ?>
  </div>
</body>
</html>
