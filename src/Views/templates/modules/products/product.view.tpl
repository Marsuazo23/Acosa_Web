<section class="depth-2 px-4 py-5">
    <h2>{{modeDsc}}</h2>
</section>
<section class="depth-2 px-4 py-4 my-4 grid row">
<form 
    method="POST"
    action="index.php?page=Modules-Products-Product&mode={{mode}}&id={{productId}}"
    class="grid col-12 col-m-8 offset-m-2 col-l-6 offset-l-3"
    >
        <div class="row my-2">
            <label for="productId" class="col-12 col-m-4 col-l-3">ID:</label>
            <input 
                type="text"
                name="productId"
                id="productId"
                value="{{productId}}"
                placeholder="Product ID"
                class="col-12 col-m-8 col-l-9"
                readonly
            />
            <input type="hidden" name="xsrtoken" value="{{xsrtoken}}" />
        </div>

        <div class="row my-2">
            <label for="productName" class="col-12 col-m-4 col-l-3">Nombre:</label>
            <input 
                type="text"
                name="productName"
                id="productName"
                value="{{productName}}"
                placeholder="Nombre del producto"
                class="col-12 col-m-8 col-l-9"
                {{readonly}}
             />
             {{foreach errors_productName}}
                <div class="error col-12">{{this}}</div>
             {{endfor errors_productName}}
        </div>

        <div class="row my-2">
            <label for="productDescription" class="col-12 col-m-4 col-l-3">Descripción:</label>
            <input 
                type="text"
                name="productDescription"
                id="productDescription"
                value="{{productDescription}}"
                placeholder="Descripción del producto"
                class="col-12 col-m-8 col-l-9"
                {{readonly}}
             />
             {{foreach errors_productDescription}}
             {{foreach errors_productDescription}}
                <div class="error col-12">{{this}}</div>
             {{endfor errors_productDescription}}
        </div>

        <div class="row my-2">
            <label for="productPrice" class="col-12 col-m-4 col-l-3">Precio:</label>
            <input 
                type="number"
                step="0.01"
                name="productPrice"
                id="productPrice"
                value="{{productPrice}}"
                placeholder="Precio del producto"
                class="col-12 col-m-8 col-l-9"
                min="0"
                {{readonly}}
             />
             {{foreach errors_productPrice}}
                <div class="error col-12">{{this}}</div>
             {{endfor errors_productPrice}}
        </div>

        <div class="row my-2">
            <label for="productImgUrl" class="col-12 col-m-4 col-l-3">URL Imagen:</label>
            <input 
                type="text"
                name="productImgUrl"
                id="productImgUrl"
                value="{{productImgUrl}}"
                placeholder="URL de la imagen"
                class="col-12 col-m-8 col-l-9"
                {{readonly}}
             />
             {{foreach errors_productImgUrl}}
                <div class="error col-12">{{this}}</div>
             {{endfor errors_productImgUrl}}
        </div>

        <div class="row my-2">
            <label for="productStatus" class="col-12 col-m-4 col-l-3">Estado:</label>
            <select 
                name="productStatus" 
                id="productStatus" 
                class="col-12 col-m-8 col-l-9"
                {{disabled}}
            >
                <option value="ACT" {{productStatus_ACT}}>Activo</option>
                <option value="INA" {{productStatus_INA}}>Inactivo</option>
                <option value="PEN" {{productStatus_PEN}}>Pendiente</option>
            </select>
            {{foreach errors_productStatus}}
                <div class="error col-12">{{this}}</div>
            {{endfor errors_productStatus}}
        </div>

        <div class="row my-2">
            <label for="categoryId" class="col-12 col-m-4 col-l-3">Categoría:</label>
            <input 
                type="number"
                name="categoryId"
                id="categoryId"
                value="{{categoryId}}"
                placeholder="ID de la categoría"
                class="col-12 col-m-8 col-l-9"
                min="1"
                {{readonly}}
             />
             {{foreach errors_categoryId}}
                <div class="error col-12">{{this}}</div>
             {{endfor errors_categoryId}}
        </div>

        <div class="row my-2">
            <label for="productStock" class="col-12 col-m-4 col-l-3">productStock:</label>
            <input 
                type="number"
                name="productStock"
                id="productStock"
                value="{{productStock}}"
                placeholder="Cantidad en inventario"
                class="col-12 col-m-8 col-l-9"
                min="0"
                {{readonly}}
             />
             {{foreach errors_productStock}}
                <div class="error col-12">{{this}}</div>
             {{endfor errors_productStock}}
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
                window.location.assign("index.php?page=Modules-Products-Products");
            });
    });
</script>
