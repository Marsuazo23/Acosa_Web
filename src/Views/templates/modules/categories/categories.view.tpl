<h1>Lista de Categorías</h1>
<section class="WWList">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>
                    <a href="index.php?page=Modules-Categories-Category&mode=INS&id=" class="">
                        Nueva
                    </a>
                </th>
            </tr>
        </thead>
        <tbody>
            {{foreach categories}}
            <tr>
                <td>{{categoryId}}</td>
                <td>{{categoryName}}</td>
                <td>
                    <a href="index.php?page=Modules-Categories-Category&mode=UPD&id={{categoryId}}">
                        Editar
                    </a> &nbsp;
                    <a href="index.php?page=Modules-Categories-Category&mode=DSP&id={{categoryId}}">
                        Ver
                    </a> &nbsp;
                    <a href="index.php?page=Modules-Categories-Category&mode=DEL&id={{categoryId}}">
                        Eliminar
                    </a>
                </td>
            </tr>
            {{endfor categories}}
        </tbody>
    </table>
</section>
