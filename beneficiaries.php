<?php
session_start();
if (!isset($_SESSION['user'])) { header('Location: index.php'); exit; }
$user = &$_SESSION['user'];
$err = $msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
        $err = 'Invalid request';
    } else {
        $name = trim($_POST['name'] ?? '');
        $bank = trim($_POST['bank'] ?? '');
        $acc  = preg_replace('/[^0-9]/','',$_POST['acc'] ?? '');
        if (!$name || !$bank || !$acc) {
            $err = 'All fields required';
        } else {
            $user['beneficiaries'][] = ['name'=>$name,'bank'=>$bank,'acc'=>$acc];
            $msg = 'Beneficiary added (demo).';
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Beneficiaries — Bank Demo</title>
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
  <h4>Beneficiaries</h4>
  <?php if ($err): ?><div class="alert alert-danger small"><?=htmlspecialchars($err)?></div><?php endif; ?>
  <?php if ($msg): ?><div class="alert alert-success small"><?=htmlspecialchars($msg)?></div><?php endif; ?>
  <ul class="list-group mb-3">
    <?php foreach($user['beneficiaries'] as $b): ?>
      <li class="list-group-item"><?=htmlspecialchars($b['name'])?> — <?=htmlspecialchars($b['bank'])?> (<?=htmlspecialchars($b['acc'])?>)</li>
    <?php endforeach; ?>
  </ul>

  <form method="post" class="row g-3">
    <input type="hidden" name="csrf" value="<?=htmlspecialchars($_SESSION['csrf'])?>">
    <div class="col-md-4"><input name="name" placeholder="Name" class="form-control"></div>
    <div class="col-md-4"><input name="bank" placeholder="Bank" class="form-control"></div>
    <div class="col-md-4"><input name="acc" placeholder="Account number" class="form-control"></div>
    <div class="col-12"><button class="btn btn-primary">Add beneficiary</button></div>
  </form>
</div>
</body>
</html>
