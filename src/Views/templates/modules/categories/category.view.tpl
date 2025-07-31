<section class="depth-2 px-4 py-5">
    <h2>{{modeDsc}}</h2>
</section>
<section class="depth-2 px-4 py-4 my-4 grid row">
<form 
    method="POST"
    action="index.php?page=Modules-Categories-Category&mode={{mode}}&id={{categoryId}}"
    class="grid col-12 col-m-8 offset-m-2 col-l-6 offset-l-3"
>
    <div class="row my-2">
        <label for="categoryId" class="col-12 col-m-4 col-l-3">ID:</label>
        <input 
            type="text"
            name="categoryId"
            id="categoryId"
            value="{{categoryId}}"
            placeholder="Category ID"
            class="col-12 col-m-8 col-l-9"
            readonly
        />
        <input type="hidden" name="xsrtoken" value="{{xsrtoken}}" />
    </div>

    <div class="row my-2">
        <label for="categoryName" class="col-12 col-m-4 col-l-3">Nombre:</label>
        <input 
            type="text"
            name="categoryName"
            id="categoryName"
            value="{{categoryName}}"
            placeholder="Nombre de la categoría (ej: Escolar, Muebles)"
            class="col-12 col-m-8 col-l-9"
            {{readonly}}
        />
        {{foreach errors_categoryName}}
            <div class="error col-12">{{this}}</div>
        {{endfor errors_categoryName}}
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
                window.location.assign("index.php?page=Modules-Categories-Categories");
            });
    });
</script>
