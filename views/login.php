<!doctype html>
<html>
<head>
  <title>Survey Builder Login</title>
  <?php include 'stylesheets.php'; ?>
  <?php include 'scripts.php'; ?>
  <script type="text/javascript">
    $(function()
    {
        $('#submitButton').button();

        <?php if (!empty($_POST['email'])): ?>
            $('#password').focus();
        <?php else: ?>
            $('#email').focus();
        <?php endif; ?>
    });
  </script>
</head>
<body>
  <div id="main">
    <?php include 'header.php'; ?>
    <div id="site_content">
      <h1>Login</h1>
      <div id="content">
        <?php if (isset($statusMessage)): ?>
            <p class="error"><?php echo htmlspecialchars($statusMessage); ?></p>
        <?php endif; ?>
        <form method="post" action="login.php">
          <div class="input_form">
            <div>
              <label>E-mail address:</label>
              <input type="text" id="email" name="email" spellcheck="false" value="<?php if (isset($_POST['email'])) echo htmlspecialchars($_POST['email']); ?>" />
            </div>
            <div>
              <label>Password:</label>
              <input type="password" id="password" name="password" value="" />
            </div>
            <div class="submit_button">
              <input type="submit" id="submitButton" name="submitButton" value="Login" />
            </div>
          </div>
        </form>
      </div>
    </div>
    <?php include 'footer.php'; ?>
  </div>
</body>
</html>
