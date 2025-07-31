<h2 style="text-align:center;">Tu Carrito</h2>

{{if cartMessage}}
  <div class="cart-alert" id="cartAlert">{{cartMessage}}</div>
{{endif cartMessage}}

<ul class="cart-list">
  {{foreach cartItems}}
    <li class="cart-item">
      <img src="{{productImgUrl}}" alt="{{productName}}" class="cart-item-img">
      <div class="cart-item-info">
        <span class="product-name">{{productName}}</span>
        <div class="quantity-control">
          <form method="POST" action="index.php?page=Checkout-UpdateQuantity" class="qty-form">
            <input type="hidden" name="productId" value="{{productId}}">
            <button type="submit" name="action" value="decrease" class="btn-qty btn-decrease">-</button>
            <span class="qty-value">{{crrctd}}</span>
            <button type="submit" name="action" value="increase" class="btn-qty btn-increase">+</button>
          </form>
          <span class="pricecart">L {{crrprc}}</span>
        </div>
      </div>
      <form method="POST" action="index.php?page=Checkout-RemoveFromCart" class="remove-form">
        <input type="hidden" name="productId" value="{{productId}}">
        <button type="submit" class="btn-remove">Quitar</button>
      </form>
    </li>
  {{endfor cartItems}}
</ul>

<div class="cart-footer">
  <h3>Subtotal: L {{cartSubtotal}}</h3>
  <h3>Impuesto: L {{cartTax}}</h3>
  <h3>Total: L {{cartTotal}}</h3>
  <form method="POST">
    <button type="submit" class="btn-paypal">Pagar con PayPal</button>
  </form>
</div>

<script>
window.addEventListener("load", function () {
  const alertBox = document.getElementById("cartAlert");
  if (alertBox) {
    setTimeout(() => {
      alertBox.style.transition = "opacity 0.5s ease";
      alertBox.style.opacity = "0";
      setTimeout(() => {
        if (alertBox && alertBox.parentNode) {
          alertBox.parentNode.removeChild(alertBox);
        }
      }, 500);
    }, 3000);
  }
});
</script>

