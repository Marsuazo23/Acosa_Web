<section class="depth-2 px-4 py-5">
    <h2>{{modeDsc}}</h2>
</section>
<section class="depth-2 px-4 py-4 my-4 grid row">
<form 
    method="POST"
    action="index.php?page=Modules-Usuarios-Usuario&mode={{mode}}&id={{usercod}}"
    class="grid col-12 col-m-8 offset-m-2 col-l-6 offset-l-3"
    >
        <div class="row my-2">
            <label for="usercod" class="col-12 col-m-4 col-l-3">ID:</label>
            <input 
                type="text"
                name="usercod"
                id="usercod"
                value="{{usercod}}"
                placeholder="ID de Usuario"
                class="col-12 col-m-8 col-l-9"
                readonly
            />
            <input type="hidden" name="xsrtoken" value="{{xsrtoken}}" />
        </div>

        <div class="row my-2">
            <label for="useremail" class="col-12 col-m-4 col-l-3">Correo Electrónico:</label>
            <input 
                type="email"
                name="useremail"
                id="useremail"
                value="{{useremail}}"
                placeholder="Correo electrónico"
                class="col-12 col-m-8 col-l-9"
                {{readonly}}
             />
             {{foreach errors_useremail}}
                <div class="error col-12">{{this}}</div>
             {{endfor errors_useremail}}
        </div>

        <div class="row my-2">
            <label for="username" class="col-12 col-m-4 col-l-3">Nombre de Usuario:</label>
            <input 
                type="text"
                name="username"
                id="username"
                value="{{username}}"
                placeholder="Nombre de usuario"
                class="col-12 col-m-8 col-l-9"
                {{readonly}}
             />
             {{foreach errors_username}}
                <div class="error col-12">{{this}}</div>
             {{endfor errors_username}}
        </div>

        <div class="row my-2">
            <label for="userpswd" class="col-12 col-m-4 col-l-3">Contraseña:</label>
            <input 
                type="password"
                name="userpswd"
                id="userpswd"
                value="{{userpswd}}"
                placeholder="Contraseña"
                class="col-12 col-m-8 col-l-9"
                {{userpswd_readonly}}
            />
            {{foreach errors_userpswd}}
                <div class="error col-12">{{this}}</div>
            {{endfor errors_userpswd}}
        </div>

        <div class="row my-2">
            <label for="userpswdest" class="col-12 col-m-4 col-l-3">Estado de Contraseña:</label>
            <select 
                    name="userpswdest" 
                    id="userpswdest" 
                    class="col-12 col-m-8 col-l-9"
                    {{disabled}}
                >
                    <option value="ACT" {{userpswdest_ACT}}>Activo</option>
                    <option value="INA" {{userpswdest_INA}}>Inactivo</option>
                    <option value="PEN" {{userpswdest_PEN}}>Pendiente</option>
            </select>
            {{foreach errors_userpswdest}}
                <div class="error col-12">{{this}}</div>
            {{endfor errors_userpswdest}}
        </div>

        <div class="row my-2">
            <label for="userfching" class="col-12 col-m-4 col-l-3">Fecha de Registro:</label>
            <input 
                type="datetime-local"
                name="userfching"
                id="userfching"
                value="{{userfching}}"
                placeholder="Fecha y hora de creación"
                class="col-12 col-m-8 col-l-9"
                {{readonly}}
            />
        </div>

        <div class="row my-2">
            <label for="userpswdexp" class="col-12 col-m-4 col-l-3">Expiración Contraseña:</label>
            <input 
                type="datetime-local"
                name="userpswdexp"
                id="userpswdexp"
                value="{{userpswdexp}}"
                placeholder="Fecha de expiración"
                class="col-12 col-m-8 col-l-9"
                {{readonly}}
             />
             {{foreach errors_userpswdexp}}
                <div class="error col-12">{{this}}</div>
             {{endfor errors_userpswdexp}}
        </div>

        <div class="row my-2">
            <label for="userest" class="col-12 col-m-4 col-l-3">Estado del Usuario:</label>
            <select 
                    name="userest" 
                    id="userest" 
                    class="col-12 col-m-8 col-l-9"
                    {{disabled}}
                >
                    <option value="ACT" {{userest_ACT}}>Activo</option>
                    <option value="INA" {{userest_INA}}>Inactivo</option>
                    <option value="BLQ" {{userest_BLQ}}>Bloqueado</option>
                    <option value="SUS" {{userest_Sus}}>Suspendido</option>
            </select>
            {{foreach errors_userest}}
                <div class="error col-12">{{this}}</div>
            {{endfor errors_userest}}
        </div>

        <div class="row my-2">
            <label for="usertipo" class="col-12 col-m-4 col-l-3">Tipo de Usuario:</label>
            <select 
                    name="usertipo" 
                    id="usertipo" 
                    class="col-12 col-m-8 col-l-9"
                    {{disabled}}
                >
                    <option value="ADM" {{usertipo_ADM}}>Administrador</option>
                    <option value="PBL" {{usertipo_PBL}}>Público</option>
            </select>
            {{foreach errors_usertipo}}
                <div class="error col-12">{{this}}</div>
            {{endfor errors_usertipo}}
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
                window.location.assign("index.php?page=Modules-Usuarios-Usuarios");
            });
    });
</script>
