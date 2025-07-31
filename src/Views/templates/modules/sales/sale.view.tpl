<section class="depth-2 px-4 py-5">
    <h2>{{modeDsc}}</h2>
</section>
<section class="depth-2 px-4 py-4 my-4 grid row">
<form 
    method="POST"
    action="index.php?page=Modules-Sales-Sale&mode={{mode}}&id={{saleId}}"
    class="grid col-12 col-m-8 offset-m-2 col-l-6 offset-l-3"
    >
        <div class="row my-2">
            <label for="saleId" class="col-12 col-m-4 col-l-3">ID:</label>
            <input 
                type="text"
                name="saleId"
                id="saleId"
                value="{{saleId}}"
                placeholder="Sale ID"
                class="col-12 col-m-8 col-l-9"
                readonly
            />
            <input type="hidden" name="xsrtoken" value="{{xsrtoken}}" />
        </div>

        <div class="row my-2">
            <label for="productId" class="col-12 col-m-4 col-l-3">ID Producto:</label>
            <input 
                type="number"
                name="productId"
                id="productId"
                value="{{productId}}"
                placeholder="ID del producto"
                class="col-12 col-m-8 col-l-9"
                min="1"
                {{readonly}}
             />
             {{foreach errors_productId}}
                <div class="error col-12">{{this}}</div>
             {{endfor errors_productId}}
        </div>

        <div class="row my-2">
            <label for="discountPercent" class="col-12 col-m-4 col-l-3">Descuento (%):</label>
            <input 
                type="number"
                step="0.01"
                name="discountPercent"
                id="discountPercent"
                value="{{discountPercent}}"
                placeholder="Porcentaje de descuento"
                class="col-12 col-m-8 col-l-9"
                min="0"
                max="100"
                {{readonly}}
             />
             {{foreach errors_discountPercent}}
                <div class="error col-12">{{this}}</div>
             {{endfor errors_discountPercent}}
        </div>

        <div class="row my-2">
            <label for="saleStart" class="col-12 col-m-4 col-l-3">Inicio de Oferta:</label>
            <input 
                type="datetime-local"
                name="saleStart"
                id="saleStart"
                value="{{saleStart}}"
                placeholder="Fecha y hora inicio"
                class="col-12 col-m-8 col-l-9"
                {{readonly}}
             />
             {{foreach errors_saleStart}}
                <div class="error col-12">{{this}}</div>
             {{endfor errors_saleStart}}
        </div>

        <div class="row my-2">
            <label for="saleEnd" class="col-12 col-m-4 col-l-3">Fin de Oferta:</label>
            <input 
                type="datetime-local"
                name="saleEnd"
                id="saleEnd"
                value="{{saleEnd}}"
                placeholder="Fecha y hora fin"
                class="col-12 col-m-8 col-l-9"
                {{readonly}}
             />
             {{foreach errors_saleEnd}}
                <div class="error col-12">{{this}}</div>
             {{endfor errors_saleEnd}}
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
                window.location.assign("index.php?page=Modules-Sales-Sales");
            });
    });
</script>
