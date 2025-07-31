<section class="depth-2 px-4 py-5">
    <h2>{{modeDsc}}</h2>
</section>
<section class="depth-2 px-4 py-4 my-4 grid row">
<form 
    method="POST"
    action="index.php?page=Modules-Funciones-Funcion&mode={{mode}}&id={{fnid}}"
    class="grid col-12 col-m-8 offset-m-2 col-l-6 offset-l-3"
>
    <div class="row my-2">
        <label for="fnid" class="col-12 col-m-4 col-l-3">ID:</label>
        <input 
            type="text"
            name="fnid"
            id="fnid"
            value="{{fnid}}"
            placeholder="ID de la función"
            class="col-12 col-m-8 col-l-9"
            readonly
        />
        <input type="hidden" name="xsrtoken" value="{{xsrtoken}}" />
    </div>

    <div class="row my-2">
        <label for="fncod" class="col-12 col-m-4 col-l-3">Código:</label>
        <input 
            type="text"
            name="fncod"
            id="fncod"
            value="{{fncod}}"
            placeholder="Código de la función"
            class="col-12 col-m-8 col-l-9"
            maxlength="255"
            {{readonly}}
         />
         {{foreach errors_fncod}}
            <div class="error col-12">{{this}}</div>
         {{endfor errors_fncod}}
    </div>

    <div class="row my-2">
        <label for="fndsc" class="col-12 col-m-4 col-l-3">Descripción:</label>
        <input 
            type="text"
            name="fndsc"
            id="fndsc"
            value="{{fndsc}}"
            placeholder="Descripción de la función"
            class="col-12 col-m-8 col-l-9"
            maxlength="255"
            {{readonly}}
         />
         {{foreach errors_fndsc}}
            <div class="error col-12">{{this}}</div>
         {{endfor errors_fndsc}}
    </div>

    <div class="row my-2">
        <label for="fnest" class="col-12 col-m-4 col-l-3">Estado:</label>
        <select name="fnest" id="fnest" class="col-12 col-m-8 col-l-9" {{disabled}}>
            <option value="ACT" {{fnest_ACT}}>Activo</option>
            <option value="INA" {{fnest_INA}}>Inactivo</option>
            <option value="PEN" {{fnest_PEN}}>Pendiente</option>
        </select>
        {{foreach errors_fnest}}
            <div class="error col-12">{{this}}</div>
        {{endfor errors_fnest}}
    </div>

    <div class="row my-2">
        <label for="fnest" class="col-12 col-m-4 col-l-3">Estado:</label>
        <select 
            name="fntyp" 
            id="fntyp" 
            class="col-12 col-m-8 col-l-9" 
            {{disabled}}
        >
            <option value="MNU" {{fntyp_XYZ}}>MNU</option>
            <option value="CTR" {{fntyp_ABC}}>CTR</option>
            <option value="FNC" {{fntyp_DEF}}>FNC</option>
        </select>
        {{foreach errors_fnest}}
            <div class="error col-12">{{this}}</div>
        {{endfor errors_fnest}}
    </div>

    <div class="row">
        <div class="col-12 right">
            <button id="btnCancel" type="button">{{cancelLabel}}</button>
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
                window.location.assign("index.php?page=Modules-Funciones-Funciones");
            });
    });
</script>
