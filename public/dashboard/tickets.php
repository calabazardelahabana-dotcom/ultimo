<?php
// public/dashboard/tickets.php - ver y crear tickets por tenant
require_once __DIR__ . '/../../includes/init.php';
require_login();
$user = current_user($pdo);
if (empty($user['tenant_id'])) die("No administras una tienda.");
$tenant_id = $user['tenant_id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['_csrf'] ?? '')) die("CSRF inválido.");
    $type = $_POST['type'] ?? 'soporte';
    $subject = trim($_POST['subject'] ?? '');
    $messageTxt = trim($_POST['message'] ?? '');
    if ($subject && $messageTxt) {
        $stmt = $pdo->prepare("INSERT INTO tickets (tenant_id, user_id, type, subject, message, status, created_at) VALUES (:t,:u,:type,:s,:m,'open',NOW())");
        $stmt->execute([':t'=>$tenant_id, ':u'=>$user['id'], ':type'=>$type, ':s'=>$subject, ':m'=>$messageTxt]);
        $message = "Ticket enviado.";
    } else $message = "Completa asunto y mensaje.";
}

$stmt = $pdo->prepare("SELECT * FROM tickets WHERE tenant_id = :t ORDER BY created_at DESC");
$stmt->execute([':t'=>$tenant_id]);
$tickets = $stmt->fetchAll();

include_once __DIR__ . '/../../header.php';
?>
<div class="container">
  <h3>Tickets</h3>
  <?php if ($message): ?><div class="alert"><?= sanitize($message) ?></div><?php endif; ?>
  <form method="post">
    <?= csrf_field() ?>
    <select name="type"><option value="soporte">Soporte</option><option value="comercial">Comercial</option></select>
    <input name="subject" placeholder="Asunto" required>
    <textarea name="message" placeholder="Mensaje" required></textarea>
    <button type="submit">Enviar</button>
  </form>
  <hr>
  <?php foreach ($tickets as $t): ?>
    <div style="border:1px solid #eee;padding:8px;margin:6px 0">
      <strong><?= sanitize($t['subject']) ?></strong> — <?= sanitize($t['status']) ?> — <?= sanitize($t['created_at']) ?>
      <p><?= nl2br(sanitize($t['message'])) ?></p>
    </div>
  <?php endforeach; ?>
</div>
<?php include_once __DIR__ . '/../../footer.php'; ?>