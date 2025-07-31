<h2><?= $message ?></h2>
<?php if (!empty($order_id)): ?>
<p>ID de la orden: <strong><?= $order_id ?></strong></p>
<p>Monto pagado: <strong><?= $amount ?> <?= $currency ?></strong></p>
<a href="index.php?page=products_list">Volver a la tienda</a>
<?php endif; ?>
