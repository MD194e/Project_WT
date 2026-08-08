<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}
$user = $_SESSION['user'];
function fmt($n){return number_format($n,2);}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Dashboard — Bank Demo</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php">Bank Demo</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item"><a class="nav-link" href="transactions.php">Transactions</a></li>
        <li class="nav-item"><a class="nav-link" href="transfer.php">Transfer</a></li>
        <li class="nav-item"><a class="nav-link" href="beneficiaries.php">Beneficiaries</a></li>
        <li class="nav-item"><a class="nav-link" href="profile.php"><?=htmlspecialchars($user['name'])?></a></li>
        <li class="nav-item"><a class="btn btn-outline-secondary btn-sm ms-2" href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<header class="bg-primary text-white py-4 mb-4">
  <div class="container d-flex justify-content-between align-items-center">
    <div>
      <h4 class="mb-0">Welcome, <?=htmlspecialchars($user['name'])?></h4>
      <div class="small">Last login: <?=date('d M Y H:i')?></div>
    </div>
    <div class="text-end">
      <div class="small">Total balance</div>
      <?php
        $total = 0; foreach($user['accounts'] as $a) $total += $a['balance'];
      ?>
      <h3 class="mb-0">₹ <?=fmt($total)?></h3>
    </div>
  </div>
</header>

<div class="container">
  <div class="row g-4">
    <div class="col-lg-8">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5>Accounts</h5>
          <div class="row">
            <?php foreach($user['accounts'] as $acc): ?>
            <div class="col-md-6">
              <div class="p-3 border rounded mb-3">
                <div class="fw-bold"><?=htmlspecialchars($acc['type'])?></div>
                <div class="small text-muted"><?=htmlspecialchars($acc['no'])?></div>
                <div class="fs-5 mt-2">₹ <?=fmt($acc['balance'])?></div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>

          <hr>
          <h6>Recent transactions</h6>
          <table class="table table-sm">
            <thead><tr><th>Date</th><th>Description</th><th class="text-end">Amount</th></tr></thead>
            <tbody>
            <?php
            // demo transactions if not present
            $txs = $_SESSION['user']['transactions'] ?? [];
            foreach(array_slice($txs,0,6) as $t): ?>
              <tr>
                <td><?=htmlspecialchars($t['date'])?></td>
                <td><?=htmlspecialchars($t['desc'])?></td>
                <td class="text-end <?=($t['amount']<0)?'text-danger':'text-success'?>">
                  <?=($t['amount']<0?'- ':'+ ')?>₹ <?=number_format(abs($t['amount']),2)?>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
          <a href="transactions.php" class="btn btn-sm btn-outline-primary">View all transactions</a>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card shadow-sm mb-3">
        <div class="card-body">
          <h6>Quick actions</h6>
          <div class="d-grid gap-2">
            <a href="transfer.php" class="btn btn-primary">Transfer money</a>
            <a href="beneficiaries.php" class="btn btn-outline-secondary">Manage beneficiaries</a>
            <a href="profile.php" class="btn btn-outline-secondary">Profile & settings</a>
          </div>
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-body">
          <h6>Notifications</h6>
          <ul class="list-unstyled small mb-0">
            <li>• Your account statement is ready.</li>
            <li>• New beneficiary added recently.</li>
          </ul>
        </div>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/app.js"></script>
</body>
</html>
