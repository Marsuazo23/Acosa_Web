<h1>Lista de Productos</h1>
<section class="WWList">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Imagen</th>
                <th>Estado</th>
                <th>Categoría</th>
                <th>Stock</th>
                <th>
                    <a href="index.php?page=Modules-Products-Product&mode=INS&id=" class="">Nuevo</a>
                </th>
            </tr>
        </thead>
        <tbody>
            {{foreach products}}
            <tr>
                <td>{{productId}}</td>
                <td>{{productName}}</td>
                <td>{{productDescription}}</td>
                <td>{{productPrice}}</td>
                <td><img src="{{productImgUrl}}" alt="{{productName}}" style="max-height:40px;"></td>
                <td>{{productStatus}}</td>
                <td>{{categoryId}}</td>
                <td>{{productStock}}</td>
                <td>
                    <a href="index.php?page=Modules-Products-Product&mode=UPD&id={{productId}}">
                        Editar
                    </a> &nbsp;
                    <a href="index.php?page=Modules-Products-Product&mode=DSP&id={{productId}}">
                        Ver
                    </a> &nbsp;
                    <a href="index.php?page=Modules-Products-Product&mode=DEL&id={{productId}}">
                        Eliminar
                    </a>
                </td>
            </tr>
            {{endfor products}}
        </tbody>
    </table>
</section>

<div class="pagination-container">
    {{pagination}}
</div>
