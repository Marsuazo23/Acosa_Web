<section class="depth-2 px-4 py-5">
    <h1>Detalle de la Orden #{{orderId}}</h1>

    <div class="row my-2">
        <label class="col-12 col-m-4 col-l-3">Fecha:</label>
        <div class="col-12 col-m-8 col-l-9">{{order_date}}</div>
    </div>

    <div class="row my-2">
        <label class="col-12 col-m-4 col-l-3">Estado:</label>
        <div class="col-12 col-m-8 col-l-9">{{order_status}}</div>
    </div>

    <div class="row my-2">
        <label class="col-12 col-m-4 col-l-3">Estado Envío:</label>
        <div class="col-12 col-m-8 col-l-9">{{shipping_status}}</div>
    </div>

    <div class="row my-2">
        <label class="col-12 col-m-4 col-l-3">Total:</label>
        <div class="col-12 col-m-8 col-l-9">{{total_amount}} {{currency}}</div>
    </div>
</section>

<section class="depth-2 px-4 py-4 my-4">
    <h2>Productos</h2>
    <div class="table-responsive">
        <table class="col-12">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                {{foreach items}}
                <tr>
                    <td>{{productName}}</td>
                    <td>{{quantity}}</td>
                    <td>{{unit_price}} {{currency}}</td>
                    <td>{{subtotal}} {{currency}}</td>
                </tr>
                {{endfor items}}
                <tr>
                    <td colspan="3" class="right"><strong>Subtotal:</strong></td>
                    <td>{{subtotal_amount}} {{currency}}</td>
                </tr>
                <tr>
                    <td colspan="3" class="right"><strong>Impuesto (15%):</strong></td>
                    <td>{{tax_amount}} {{currency}}</td>
                </tr>
                <tr>
                    <td colspan="3" class="right"><strong>Total:</strong></td>
                    <td>{{total_amount}} {{currency}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>


<section class="depth-2 px-4 py-3">
    <button id="btnBack">Volver a mis órdenes</button>
</section>

<script>
    document.getElementById("btnBack").addEventListener("click", () => {
        window.location.assign("index.php?page=Pages-myOrders");
    });
</script>
