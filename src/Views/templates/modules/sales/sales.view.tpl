<h1>Lista de Ofertas</h1>
<section class="WWList">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>ID Producto</th>
                <th>Descuento (%)</th>
                <th>Inicio de Venta</th>
                <th>Fin de Venta</th>
                <th>
                    <a href="index.php?page=Modules-Sales-Sale&mode=INS&id=" class="">Nuevo</a>
                </th>
            </tr>
        </thead>
        <tbody>
            {{foreach sales}}
            <tr>
                <td>{{saleId}}</td>
                <td>{{productId}}</td>
                <td>{{discountPercent}}</td>
                <td>{{saleStart}}</td>
                <td>{{saleEnd}}</td>
                <td>
                    <a href="index.php?page=Modules-Sales-Sale&mode=UPD&id={{saleId}}">
                        Editar
                    </a> &nbsp;
                    <a href="index.php?page=Modules-Sales-Sale&mode=DSP&id={{saleId}}">
                        Ver
                    </a> &nbsp;
                    <a href="index.php?page=Modules-Sales-Sale&mode=DEL&id={{saleId}}">
                        Eliminar
                    </a>
                </td>
            </tr>
            {{endfor sales}}
        </tbody>
    </table>
</section>
