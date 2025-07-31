<h1>Lista de Roles</h1>
<section class="WWList">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>
                    <a href="index.php?page=Modules-Roles-Rol&mode=INS&id=" class="">Nuevo</a>
                </th>
            </tr>
        </thead>
        <tbody>
            {{foreach roles}}
            <tr>
                <td>{{roleid}}</td>
                <td>{{rolescod}}</td>
                <td>{{rolesdsc}}</td>
                <td>{{rolesest}}</td>
                <td>
                    <a href="index.php?page=Modules-Roles-Rol&mode=UPD&id={{roleid}}">
                        Editar
                    </a> &nbsp;
                    <a href="index.php?page=Modules-Roles-Rol&mode=DSP&id={{roleid}}">
                        Ver
                    </a> &nbsp;
                    <a href="index.php?page=Modules-Roles-Rol&mode=DEL&id={{roleid}}">
                        Eliminar
                    </a>
                </td>
            </tr>
            {{endfor roles}}
        </tbody>
    </table>
</section>
