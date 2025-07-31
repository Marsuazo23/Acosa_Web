<h1>Lista de Funciones</h1>
<section class="WWList">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Tipo</th>
                <th>
                    <a href="index.php?page=Modules-Funciones-Funcion&mode=INS&id=" class="">Nuevo</a>
                </th>
            </tr>
        </thead>
        <tbody>
            {{foreach funciones}}
            <tr>
                <td>{{fnid}}</td>
                <td>{{fncod}}</td>
                <td>{{fndsc}}</td>
                <td>{{fnest}}</td>
                <td>{{fntyp}}</td>
                <td>
                    <a href="index.php?page=Modules-Funciones-Funcion&mode=UPD&id={{fnid}}">
                        Editar
                    </a> &nbsp;
                    <a href="index.php?page=Modules-Funciones-Funcion&mode=DSP&id={{fnid}}">
                        Ver
                    </a> &nbsp;
                    <a href="index.php?page=Modules-Funciones-Funcion&mode=DEL&id={{fnid}}">
                        Eliminar
                    </a>
                </td>
            </tr>
            {{endfor funciones}}
        </tbody>
    </table>
</section>

<div class="pagination-container">
    {{pagination}}
</div>