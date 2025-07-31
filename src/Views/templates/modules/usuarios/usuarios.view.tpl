<h1>Lista de Usuarios</h1>
<section class="WWList">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Correo Electrónico</th>
                <th>Nombre de Usuario</th>
                <th>Estado</th>
                <th>Tipo</th>
                <th>
                    <a href="index.php?page=Modules-Usuarios-Usuario&mode=INS&id=" class="">Nuevo</a>
                </th>
            </tr>
        </thead>
        <tbody>
            {{foreach usuarios}}
            <tr>
                <td>{{usercod}}</td>
                <td>{{useremail}}</td>
                <td>{{username}}</td>
                <td>{{userest}}</td>
                <td>{{usertipo}}</td>
                <td>
                    <a href="index.php?page=Modules-Usuarios-Usuario&mode=UPD&id={{usercod}}">
                        Editar
                    </a> &nbsp;
                    <a href="index.php?page=Modules-Usuarios-Usuario&mode=DSP&id={{usercod}}">
                        Ver
                    </a> &nbsp;
                    <a href="index.php?page=Modules-Usuarios-Usuario&mode=DEL&id={{usercod}}">
                        Eliminar
                    </a>
                </td>
            </tr>
            {{endfor usuarios}}
        </tbody>
    </table>
</section>
