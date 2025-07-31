<h1>Lista de Transacciones PayPal</h1>
<section class="WWList">
    <table>
        <thead>
            <tr>
                <th>ID Orden</th>
                <th>ID Captura</th>
                <th>Estado</th>
                <th>Monto</th>
                <th>Moneda</th>
                <th>Email Pagador</th>
                <th>Nombre Pagador</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            {{foreach transactions}}
            <tr>
                <td>{{order_id}}</td>
                <td>{{capture_id}}</td>
                <td>{{status}}</td>
                <td>{{amount}}</td>
                <td>{{currency}}</td>
                <td>{{payer_email}}</td>
                <td>{{payer_name}}</td>
                <td>{{created_at}}</td>
                <td>
                    <a href="index.php?page=Modules-Transactions-Transaction&mode=DSP&id={{id_transaction}}">
                        Ver
                    </a>
                </td>
            </tr>
            {{endfor transactions}}
        </tbody>
    </table>
</section>
