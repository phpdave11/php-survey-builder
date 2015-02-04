<!doctype html>
<html>
<head>
  <title>Survey Results</title>
  <?php include 'stylesheets.php'; ?>
  <?php include 'scripts.php'; ?>
  <script type="text/javascript">
    $(function()
    {
        $('.download_csv').button({ icons: { primary: 'ui-icon-document' } });
        $('.view_charts').button({ icons: { primary: 'ui-icon-image' } });
    });
  </script>
</head>
<body>
  <div id="main">
    <?php include 'header.php'; ?>
    <div id="site_content">
      <div style="float: right; padding: 15px;">
        <a class="view_charts" href="survey_charts.php?survey_id=<?php echo htmlspecialchars($survey->survey_id); ?>">View Charts</a>
        <a class="download_csv" href="survey_results.php?survey_id=<?php echo htmlspecialchars($survey->survey_id); ?>&amp;action=download_csv">Download CSV</a>
      </div>
      <h1><?php echo htmlspecialchars($survey->survey_name); ?></h1>
      <div id="content">
        <div class="scrollgrid">
          <table class="grid">
            <tr>
              <?php foreach ($survey->questions as $question): ?>
              <th><?php echo htmlspecialchars($question->question_text); ?></th>
              <?php endforeach; ?>
            </tr>
            <?php if (empty($survey->responses)): ?>
              <tr>
                <td colspan="<?php echo count($survey->questions); ?>"><em>No surveys</em></td>
              </tr>
            <?php else: ?>
            <?php foreach ($survey->responses as $response): ?>
              <tr>
                <?php foreach ($survey->questions as $question): ?>
                <td><?php $field = 'question_' . htmlspecialchars($question->question_id); echo htmlspecialchars($response->$field); ?></td>
                <?php endforeach; ?>
              </tr>
            <?php endforeach; ?>
            <?php endif; ?>
          </table>
        </div>
      </div>
    </div>
    <?php include 'footer.php'; ?>
  </div>
</body>
</html>
