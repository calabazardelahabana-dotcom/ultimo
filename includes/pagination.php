<?php
// includes/pagination.php
function paginate_query($pdo, $sql, $params = [], $page = 1, $per = 20) {
    $offset = ($page - 1) * $per;
    $stmt = $pdo->prepare($sql . " LIMIT :off, :per");
    foreach ($params as $k=>$v) $stmt->bindValue($k, $v);
    $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':per', $per, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}