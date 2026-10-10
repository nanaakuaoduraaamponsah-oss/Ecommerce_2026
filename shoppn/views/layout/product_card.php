<?php
// Expects $product (one row from the products table)
$cardImage = $product['product_image'] ?? '';
?>
<div class="product-card">
<a class="product-img-wrap" href="<?= BASE_PATH ?>/views/single_product.php?pro_id=<?= (int) $product['product_id'] ?>">
    <?php if ($cardImage !== '' && $cardImage !== null): ?>
    <img src="<?= BASE_PATH ?>/images/products/<?= htmlspecialchars(rawurlencode($cardImage)) ?>" alt="<?= htmlspecialchars($product['product_title']) ?>">
    <?php else: ?>
    <div class="product-img-placeholder">No image</div>
    <?php endif; ?>
</a>
<h4><?= htmlspecialchars($product['product_title']) ?></h4>
<p class="product-price">GHS <?= number_format((float) $product['product_price'], 2) ?></p>
<div class="product-actions">
    <a class="btn-primary btn-small" href="<?= BASE_PATH ?>/views/single_product.php?pro_id=<?= (int) $product['product_id'] ?>">Details</a>
    <a class="btn-primary btn-secondary btn-small" href="<?= BASE_PATH ?>/actions/add_to_cart_action.php?add_cart=<?= (int) $product['product_id'] ?>">Add to Cart</a>
</div>
</div>