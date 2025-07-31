<h1>Lista de Órdenes</h1>
<section class="WWList">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Estado Pago</th>
                <th>Estado Envío</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            {{foreach orders}}
            <tr>
                <td>{{orderId}}</td>
                <td>{{userName}}</td>
                <td>{{order_status}}</td>
                <td>{{shipping_status}}</td>
                <td>{{order_date}}</td>
                <td>
                    <a href="index.php?page=Modules-Orders-Order&mode=UPD&id={{orderId}}">
                        Editar Estado
                    </a> &nbsp;
                </td>
            </tr>
            {{endfor orders}}
        </tbody>
    </table>
</section>
