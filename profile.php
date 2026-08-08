<?php
session_start();
if (!isset($_SESSION['user'])) { header('Location: index.php'); exit; }
$user = &$_SESSION['user'];
$notice = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Only demo: allow changing display name
    $name = trim($_POST['name'] ?? '');
    if ($name) {
        $user['name'] = $name;
        $notice = 'Profile updated (demo).';
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Profile — Bank Demo</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav class="navbar navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php">Bank Demo</a>
  </div>
</nav>
<div class="container py-4">
  <h4>Profile & settings</h4>
  <?php if ($notice): ?><div class="alert alert-success small"><?=htmlspecialchars($notice)?></div><?php endif; ?>
  <form method="post" class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Display name</label>
      <input name="name" class="form-control" value="<?=htmlspecialchars($user['name'])?>">
    </div>
    <div class="col-md-6">
      <label class="form-label">Email</label>
      <input class="form-control" value="<?=htmlspecialchars($user['email'])?>" disabled>
    </div>
    <div class="col-12"><button class="btn btn-primary">Save</button></div>
  </form>
</div>
</body>
</html>
