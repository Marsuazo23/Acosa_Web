<h1>Lista de Funciones por Rol</h1>
<section class="WWList">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Código Rol</th>
                <th>Código Función</th>
                <th>Estado</th>
                <th>Fecha Expiración</th>
                <th>
                    <a href="index.php?page=Modules-FuncionesRoles-FuncionesRol&mode=INS&id=" class="">Nuevo</a>
                </th>
            </tr>
        </thead>
        <tbody>
            {{foreach funcionesroles}}
            <tr>
                <td>{{fnrolid}}</td>
                <td>{{rolescod}}</td>
                <td>{{fncod}}</td>
                <td>{{fnrolest}}</td>
                <td>{{fnexp}}</td>
                <td>
                    <a href="index.php?page=Modules-FuncionesRoles-FuncionesRol&mode=UPD&id={{fnrolid}}">
                        Editar
                    </a> &nbsp;
                    <a href="index.php?page=Modules-FuncionesRoles-FuncionesRol&mode=DSP&id={{fnrolid}}">
                        Ver
                    </a> &nbsp;
                    <a href="index.php?page=Modules-FuncionesRoles-FuncionesRol&mode=DEL&id={{fnrolid}}">
                        Eliminar
                    </a>
                </td>
            </tr>
            {{endfor funcionesroles}}
        </tbody>
    </table>
</section>

<div class="pagination-container">
    {{pagination}}
</div>