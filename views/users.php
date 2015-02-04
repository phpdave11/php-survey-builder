<!doctype html>
<html>
<head>
  <title>Users</title>
  <?php include 'stylesheets.php'; ?>
  <?php include 'scripts.php'; ?>
  <script type="text/javascript">
    var loginFields = <?php echo json_encode($loginFields); ?>;
  </script>
  <script type="text/javascript" src="js/users.js"></script>
</head>
<body>
  <div id="main">
    <?php include 'header.php'; ?>
    <div id="site_content">
      <h1>Users</h1>
      <div id="content">
        <table class="grid">
          <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>E-mail</th>
            <th>Edit</th>
          </tr>
          <?php if (empty($users)): ?>
            <tr>
              <td colspan="4"><em>No users</em></td>
            </tr>
          <?php endif; ?>
          <?php foreach ($users as $user): ?>
            <tr>
              <td><?php echo htmlspecialchars($user->first_name); ?></td>
              <td><?php echo htmlspecialchars($user->last_name); ?></td>
              <td><?php echo htmlspecialchars($user->email); ?></td>
              <td><button data-login_id="<?php echo htmlspecialchars($user->login_id); ?>" class="edit_user">Edit User</button></td>
            </tr>
          <?php endforeach; ?>
        </table>
        <button id="add_user_button">Add User</button>
        <div id="user_edit_dialog" title="Add User" style="display: none">
          <form id="user_edit_form" action="user_edit.php" method="post">
            <input type="hidden" id="action" name="action" value="edit_user" />
            <input type="hidden" id="login_id" name="login_id" value="" />
            <div class="input_form">
              <div>
                <label>First name:</label>
                <input type="text" id="first_name" name="first_name" spellcheck="false" value="" />
              </div>
              <div>
                <label>Last name:</label>
                <input type="text" id="last_name" name="last_name" spellcheck="false" value="" />
              </div>
              <div>
                <label>E-mail address:</label>
                <input type="text" id="email" name="email" spellcheck="false" value="" />
              </div>
              <div>
                <label>Password:</label>
                <input type="password" id="password" name="password" value="" />
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <?php include 'footer.php'; ?>
  </div>
</body>
</html>
