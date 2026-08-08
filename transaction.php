<?php
session_start();
if (!isset($_SESSION['user'])) { header('Location: index.php'); exit; }
$txs = $_SESSION['user']['transactions'] ?? [];
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Transactions — Bank Demo</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav class="navbar navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php">Bank Demo</a>
    <div>
      <a href="dashboard.php" class="btn btn-outline-secondary btn-sm">Back</a>
    </div>
  </div>
</nav>

<div class="container py-4">
  <h4>Transaction history</h4>
  <table class="table table-striped">
    <thead><tr><th>Date</th><th>Description</th><th class="text-end">Amount</th><th class="text-end">Balance</th></tr></thead>
    <tbody>
    <?php foreach($txs as $t): ?>
      <tr>
        <td><?=htmlspecialchars($t['date'])?></td>
        <td><?=htmlspecialchars($t['desc'])?></td>
        <td class="text-end <?=($t['amount']<0)?'text-danger':'text-success'?>"><?=($t['amount']<0?'- ':'+ ')?>₹ <?=number_format(abs($t['amount']),2)?></td>
        <td class="text-end"><?=isset($t['balance'])?('₹ '.number_format($t['balance'],2)):'—'?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>
