<h1>Mis Órdenes</h1>
<section class="WWList">
    <table>
        <thead>
            <tr>
                <th>ID Orden</th>
                <th>Estado Pago</th>
                <th>Estado Envío</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            {{foreach orders}}
            <tr>
                <td>{{orderId}}</td>
                <td>{{order_status}}</td>
                <td>{{shipping_status}}</td>
                <td>{{order_date}}</td>
                <td>{{total_amount}} {{currency}}</td>
                <td>
                    <a href="index.php?page=Pages-OrderDetail&id={{orderId}}">
                        Ver Detalle
                    </a>
                </td>
            </tr>
            {{endfor orders}}
        </tbody>
    </table>
</section>
