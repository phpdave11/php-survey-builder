<!doctype html>
<html>
<head>
  <title>Thank You</title>
  <?php include 'stylesheets.php'; ?>
  <?php include 'scripts.php'; ?>
</head>
<body>
  <div id="main">
    <?php $title = htmlspecialchars($survey->survey_name); ?>
    <?php $subtitle = 'Survey Response Submitted'; ?>
    <?php include 'public_header.php'; ?>
    <div id="site_content">
      <h1>Thank you for completing the survey!</h1>
      <div id="content">
        <p>Thank you for taking the time to complete the survey. Your feedback is very valuable to us.</p>
      </div>
    </div>
    <?php include 'footer.php'; ?>
  </div>
</body>
</html>
