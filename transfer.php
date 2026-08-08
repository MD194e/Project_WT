<?php
session_start();
if (!isset($_SESSION['user'])) { header('Location: index.php'); exit; }
$user = &$_SESSION['user'];
$err = $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
        $err = 'Invalid form submission.';
    } else {
        $from = $_POST['from'] ?? '';
        $toAcc = preg_replace('/[^0-9]/','',$_POST['to_acc'] ?? '');
        $amount = (float)($_POST['amount'] ?? 0);

        // simplistic validation and apply to first matching from account
        $foundKey = null;
        foreach ($user['accounts'] as $k => $a) {
            if ($a['no'] === $from) { $foundKey = $k; break; }
        }
        if ($foundKey === null) {
            $err = 'Select a valid source account.';
        } elseif ($amount <= 0) {
            $err = 'Enter a valid amount.';
        } elseif ($user['accounts'][$foundKey]['balance'] < $amount) {
            $err = 'Insufficient funds.';
        } else {
            // debit
            $user['accounts'][$foundKey]['balance'] -= $amount;
            // record transaction (demo)
            array_unshift($user['transactions'], [
              'date'=>date('Y-m-d'),
              'desc'=>"Transfer to $toAcc",
              'amount'=>-round($amount,2),
              'balance'=>$user['accounts'][$foundKey]['balance']
            ]);
            $success = 'Transfer completed (demo).';
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Transfer — Bank Demo</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include 'partials/topnav.php' ?? null; // optional reuse ?>
<div class="container py-4">
  <h4>Make a transfer</h4>
  <?php if ($err): ?>
    <div class="alert alert-danger small"><?=htmlspecialchars($err)?></div>
  <?php elseif ($success): ?>
    <div class="alert alert-success small"><?=htmlspecialchars($success)?></div>
  <?php endif; ?>

  <form method="post" class="row g-3">
    <input type="hidden" name="csrf" value="<?=htmlspecialchars($_SESSION['csrf'])?>">
    <div class="col-md-6">
      <label class="form-label">From account</label>
      <select name="from" class="form-select">
        <?php foreach($user['accounts'] as $a): ?>
          <option value="<?=htmlspecialchars($a['no'])?>"><?=htmlspecialchars($a['type'].' — '.$a['no'].' (₹'.number_format($a['balance'],2).')')?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label">To account number</label>
      <input name="to_acc" class="form-control" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">Amount (₹)</label>
      <input name="amount" type="number" step="0.01" class="form-control" required>
    </div>
    <div class="col-12">
      <button class="btn btn-primary">Submit transfer</button>
      <a href="dashboard.php" class="btn btn-link">Cancel</a>
    </div>
  </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
