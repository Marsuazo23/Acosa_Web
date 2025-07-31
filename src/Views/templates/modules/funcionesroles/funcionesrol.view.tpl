<section class="depth-2 px-4 py-5">
    <h2>{{modeDsc}}</h2>
</section>
<section class="depth-2 px-4 py-4 my-4 grid row">
<form 
    method="POST"
    action="index.php?page=Modules-FuncionesRoles-FuncionesRol&mode={{mode}}&id={{fnrolid}}"
    class="grid col-12 col-m-8 offset-m-2 col-l-6 offset-l-3"
    >
        <div class="row my-2">
            <label for="fnrolid" class="col-12 col-m-4 col-l-3">ID:</label>
            <input 
                type="text"
                name="fnrolid"
                id="fnrolid"
                value="{{fnrolid}}"
                placeholder="ID"
                class="col-12 col-m-8 col-l-9"
                readonly
            />
            <input type="hidden" name="xsrtoken" value="{{xsrtoken}}" />
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
            <label for="fncod" class="col-12 col-m-4 col-l-3">Código Función:</label>
            <input 
                type="text"
                name="fncod"
                id="fncod"
                value="{{fncod}}"
                placeholder="Código de la función"
                class="col-12 col-m-8 col-l-9"
                {{readonly}}
             />
             {{foreach errors_fncod}}
                <div class="error col-12">{{this}}</div>
             {{endfor errors_fncod}}
        </div>

        <div class="row my-2">
            <label for="fnrolest" class="col-12 col-m-4 col-l-3">Estado:</label>
            <select 
                name="fnrolest" 
                id="fnrolest" 
                class="col-12 col-m-8 col-l-9"
                {{disabled}}
            >
                <option value="ACT" {{fnrolest_ACT}}>Activo</option>
                <option value="INA" {{fnrolest_INA}}>Inactivo</option>
                <option value="PEN" {{fnrolest_PEN}}>Pendiente</option>
            </select>
            {{foreach errors_fnrolest}}
                <div class="error col-12">{{this}}</div>
            {{endfor errors_fnrolest}}
        </div>

        <div class="row my-2">
            <label for="fnexp" class="col-12 col-m-4 col-l-3">Fecha Expiración:</label>
            <input 
                type="datetime-local"
                name="fnexp"
                id="fnexp"
                value="{{fnexp}}"
                placeholder="Fecha de expiración"
                class="col-12 col-m-8 col-l-9"
                {{readonly}}
             />
             {{foreach errors_fnexp}}
                <div class="error col-12">{{this}}</div>
             {{endfor errors_fnexp}}
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
                window.location.assign("index.php?page=Modules-FuncionesRoles-FuncionesRoles");
            });
    });
</script>
