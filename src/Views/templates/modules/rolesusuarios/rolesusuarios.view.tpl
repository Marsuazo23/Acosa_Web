<h1>Lista de Roles de Usuario</h1>
<section class="WWList">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Código Usuario</th>
                <th>Código Rol</th>
                <th>Estado</th>
                <th>Fecha Asignación</th>
                <th>Fecha Expiración</th>
                <th>
                    <a href="index.php?page=Modules-RolesUsuarios-RolesUsuario&mode=INS&id=" class="">Nuevo</a>
                </th>
            </tr>
        </thead>
        <tbody>
            {{foreach rolesusuarios}}
            <tr>
                <td>{{roleuserid}}</td>
                <td>{{usercod}}</td>
                <td>{{rolescod}}</td>
                <td>{{roleuserest}}</td>
                <td>{{roleuserfch}}</td>
                <td>{{roleuserexp}}</td>
                <td>
                    <a href="index.php?page=Modules-RolesUsuarios-RolesUsuario&mode=UPD&id={{roleuserid}}">
                        Editar
                    </a> &nbsp;
                    <a href="index.php?page=Modules-RolesUsuarios-RolesUsuario&mode=DSP&id={{roleuserid}}">
                        Ver
                    </a> &nbsp;
                    <a href="index.php?page=Modules-RolesUsuarios-RolesUsuario&mode=DEL&id={{roleuserid}}">
                        Eliminar
                    </a>
                </td>
            </tr>
            {{endfor rolesusuarios}}
        </tbody>
    </table>
</section>
