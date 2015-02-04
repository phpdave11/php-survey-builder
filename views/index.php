<!doctype html>
<html>
<head>
  <title>Home</title>
  <?php include 'stylesheets.php'; ?>
  <?php include 'scripts.php'; ?>
</head>
<body>
  <div id="main">
    <?php include 'header.php'; ?>
    <div id="site_content">
      <h1>Welcome <?php echo $user->first_name, ' ', $user->last_name; ?></h1>
      <div id="content">
        <p>This application lets you build a customized survey which you can use to send to people and record their responses. The responses can be viewed and downloaded into an Excel spreadsheet, and you can also view charts of the results.</p>
      </div>
    </div>
    <?php include 'footer.php'; ?>
  </div>
</body>
</html>
