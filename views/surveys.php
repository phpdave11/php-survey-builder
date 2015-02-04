<!doctype html>
<html>
<head>
  <title>Surveys</title>
  <?php include 'stylesheets.php'; ?>
  <?php include 'scripts.php'; ?>
  <script type="text/javascript">
    $(function()
    {
        $('#add_survey_button').button();
        $('.edit_survey').button();
        $('.take_survey').button();
        $('.view_charts').button();
    });
  </script>
</head>
<body>
  <div id="main">
    <?php include 'header.php'; ?>
    <div id="site_content">
      <?php if (isset($statusMessage)): ?>
        <p class="error"><?php echo htmlspecialchars($statusMessage); ?></p>
      <?php endif; ?>
      <h1>Surveys</h1>
      <div id="content">
        <table class="grid">
          <tr>
            <th>Survey Name</th>
            <th>Edit</th>
            <th>Take Survey</th>
            <th>View Results</th>
            <th>View Charts</th>
          </tr>
          <?php if (empty($surveys)): ?>
            <tr>
              <td colspan="5"><em>No surveys</em></td>
            </tr>
          <?php endif; ?>
          <?php foreach ($surveys as $survey): ?>
            <tr>
              <td><?php echo htmlspecialchars($survey->survey_name); ?></td>
              <td><a class="edit_survey" href="survey_edit.php?survey_id=<?php echo htmlspecialchars($survey->survey_id); ?>">Edit Survey</a></td>
              <td><a class="take_survey" href="survey_form.php?survey_id=<?php echo htmlspecialchars($survey->survey_id); ?>" target="_blank">Take Survey</a></td>
              <td><a class="take_survey" href="survey_results.php?survey_id=<?php echo htmlspecialchars($survey->survey_id); ?>">View Results</a></td>
              <td><a class="view_charts" href="survey_charts.php?survey_id=<?php echo htmlspecialchars($survey->survey_id); ?>">View Charts</a></td>
            </tr>
          <?php endforeach; ?>
        </table>
        <a id="add_survey_button" href="survey_edit.php">Add Survey</a>
      </div>
    </div>
    <?php include 'footer.php'; ?>
  </div>
</body>
</html>
