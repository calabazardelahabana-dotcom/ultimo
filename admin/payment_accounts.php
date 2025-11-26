<?php
// admin/payment_accounts.php - list and add platform payment accounts
require_once __DIR__ . '/../includes/init.php';
require_login();
if (!is_superadmin()) die("Acceso restringido");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['_csrf'] ?? '')) die("CSRF token inválido.");
    $provider = trim($_POST['provider'] ?? '');
    $account_identifier = trim($_POST['account_identifier'] ?? '');
    $is_default = !empty($_POST['is_default']) ? 1 : 0;
    if ($is_default) $pdo->prepare("UPDATE platform_payment_accounts SET is_default = 0")->execute();
    $stmt = $pdo->prepare("INSERT INTO platform_payment_accounts (provider, account_identifier, metadata, is_default, created_at) VALUES (:p,:aid,NULL,:d,NOW())");
    $stmt->execute([':p'=>$provider, ':aid'=>$account_identifier, ':d'=>$is_default]);
    header('Location: /admin/payment_accounts.php'); exit;
}
$accounts = $pdo->query("SELECT * FROM platform_payment_accounts ORDER BY created_at DESC")->fetchAll();
include_once __DIR__ . '/../header.php';
?>
<div class="container">
  <h3>Cuentas de pago de la plataforma</h3>
  <form method="post">
    <?= csrf_field() ?>
    <input name="provider" placeholder="Proveedor (stripe)" required>
    <input name="account_identifier" placeholder="Identificador (acct_xxx)" required>
    <label><input type="checkbox" name="is_default" value="1"> Por defecto</label>
    <button type="submit">Agregar</button>
  </form>
  <hr>
  <ul>
    <?php foreach ($accounts as $a): ?>
      <li><?= sanitize($a['provider']) ?> - <?= sanitize($a['account_identifier']) ?> <?= $a['is_default'] ? '(default)' : '' ?></li>
    <?php endforeach; ?>
  </ul>
</div>
<?php include_once __DIR__ . '/../footer.php'; ?>