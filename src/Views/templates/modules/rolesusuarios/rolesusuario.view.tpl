<section class="depth-2 px-4 py-5">
    <h2>{{modeDsc}}</h2>
</section>
<section class="depth-2 px-4 py-4 my-4 grid row">
<form 
    method="POST"
    action="index.php?page=Modules-RolesUsuarios-RolesUsuario&mode={{mode}}&id={{roleuserid}}"
    class="grid col-12 col-m-8 offset-m-2 col-l-6 offset-l-3"
>
    <div class="row my-2">
        <label for="roleuserid" class="col-12 col-m-4 col-l-3">ID:</label>
        <input 
            type="text"
            name="roleuserid"
            id="roleuserid"
            value="{{roleuserid}}"
            placeholder="ID Rol Usuario"
            class="col-12 col-m-8 col-l-9"
            readonly
        />
        <input type="hidden" name="xsrtoken" value="{{xsrtoken}}" />
    </div>

    <div class="row my-2">
        <label for="usercod" class="col-12 col-m-4 col-l-3">Código Usuario:</label>
        <input 
            type="number"
            name="usercod"
            id="usercod"
            value="{{usercod}}"
            placeholder="Código del usuario"
            class="col-12 col-m-8 col-l-9"
            min="1"
            {{readonly}}
        />
        {{foreach errors_usercod}}
            <div class="error col-12">{{this}}</div>
        {{endfor errors_usercod}}
    </div>

    <div class="row my-2">
        <label for="rolescod" class="col-12 col-m-4 col-l-3">Código Rol:</label>
        <input 
            type="text"
            name="rolescod"
            id="rolescod"
            value="{{rolescod}}"
            placeholder="Código del rol"
            class="col-12 col-m-8 col-l-9"
            {{readonly}}
        />
        {{foreach errors_rolescod}}
            <div class="error col-12">{{this}}</div>
        {{endfor errors_rolescod}}
    </div>

    <div class="row my-2">
        <label for="roleuserest" class="col-12 col-m-4 col-l-3">Estado:</label>
        <select 
            name="roleuserest" 
            id="roleuserest" 
            class="col-12 col-m-8 col-l-9"
            {{disabled}}
        >
            <option value="ACT" {{roleuserest_ACT}}>Activo</option>
            <option value="INA" {{roleuserest_INA}}>Inactivo</option>
            <option value="PEN" {{roleuserest_PEN}}>Pendiente</option>
        </select>
        {{foreach errors_roleuserest}}
            <div class="error col-12">{{this}}</div>
        {{endfor errors_roleuserest}}
    </div>

    <div class="row my-2">
        <label for="roleuserfch" class="col-12 col-m-4 col-l-3">Fecha Asignación:</label>
        <input 
            type="datetime-local"
            name="roleuserfch"
            id="roleuserfch"
            value="{{roleuserfch}}"
            class="col-12 col-m-8 col-l-9"
            {{readonly}}
        />
        {{foreach errors_roleuserfch}}
            <div class="error col-12">{{this}}</div>
        {{endfor errors_roleuserfch}}
    </div>

    <div class="row my-2">
        <label for="roleuserexp" class="col-12 col-m-4 col-l-3">Fecha Expiración:</label>
        <input 
            type="datetime-local"
            name="roleuserexp"
            id="roleuserexp"
            value="{{roleuserexp}}"
            class="col-12 col-m-8 col-l-9"
            {{readonly}}
        />
        {{foreach errors_roleuserexp}}
            <div class="error col-12">{{this}}</div>
        {{endfor errors_roleuserexp}}
    </div>

    <div class="row">
        <div class="col-12 right">
            <button class="" id="btnCancel" type="button">{{cancelLabel}}</button>
            &nbsp;
            {{if showConfirm}}
                <button class="primary" type="submit">Confirmar</button>
            {{endif showConfirm}}
       </div>
    </div>

    {{if errors_global}}
        <div class="row">
            <ul class="col-12">
            {{foreach errors_global}}
                <li class="error">{{this}}</li>
            {{endfor errors_global}}
            </ul>
        </div>
    {{endif errors_global}}
</form>
</section>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        document.getElementById("btnCancel")
            .addEventListener("click", (e) => {
                e.preventDefault();
                e.stopPropagation();
                window.location.assign("index.php?page=Modules-RolesUsuarios-RolesUsuarios");
            });
    });
</script>
