<section class="depth-2 px-4 py-5">
    <h2>{{modeDsc}}</h2>
</section>
<section class="depth-2 px-4 py-4 my-4 grid row">
<form 
    method="POST"
    action="index.php?page=Modules-Transactions-Transaction&mode={{mode}}&id={{id}}"
    class="grid col-12 col-m-8 offset-m-2 col-l-6 offset-l-3"
>
    <div class="row my-2">
        <label for="id" class="col-12 col-m-4 col-l-3">ID:</label>
        <input 
            type="text"
            name="id"
            id="id"
            value="{{id_transaction}}"
            placeholder="Transaction ID"
            class="col-12 col-m-8 col-l-9"
            readonly
        />
        <input type="hidden" name="xsrtoken" value="{{xsrtoken}}" />
    </div>

    <div class="row my-2">
        <label for="order_id" class="col-12 col-m-4 col-l-3">Order ID:</label>
        <input 
            type="text"
            name="order_id"
            id="order_id"
            value="{{order_id}}"
            placeholder="Order ID de PayPal"
            class="col-12 col-m-8 col-l-9"
            {{readonly}}
        />
    </div>

    <div class="row my-2">
        <label for="status" class="col-12 col-m-4 col-l-3">Estado:</label>
        <input 
            type="text"
            name="status"
            id="status"
            value="{{status}}"
            placeholder="Estado de la transacción"
            class="col-12 col-m-8 col-l-9"
            {{readonly}}
        />
    </div>

    <div class="row my-2">
        <label for="amount" class="col-12 col-m-4 col-l-3">Monto:</label>
        <input 
            type="text"
            name="amount"
            id="amount"
            value="{{amount}} {{currency}}"
            placeholder="Monto pagado"
            class="col-12 col-m-8 col-l-9"
            {{readonly}}
        />
    </div>

    <div class="row my-2">
        <label for="payer_email" class="col-12 col-m-4 col-l-3">Correo Pagador:</label>
        <input 
            type="text"
            name="payer_email"
            id="payer_email"
            value="{{payer_email}}"
            placeholder="Correo del pagador"
            class="col-12 col-m-8 col-l-9"
            {{readonly}}
        />
    </div>

    <div class="row my-2">
        <label for="payer_name" class="col-12 col-m-4 col-l-3">Nombre Pagador:</label>
        <input 
            type="text"
            name="payer_name"
            id="payer_name"
            value="{{payer_name}}"
            placeholder="Nombre del pagador"
            class="col-12 col-m-8 col-l-9"
            {{readonly}}
        />
    </div>

    <div class="row my-2">
        <label for="payer_country" class="col-12 col-m-4 col-l-3">País:</label>
        <input 
            type="text"
            name="payer_country"
            id="payer_country"
            value="{{payer_country}}"
            placeholder="País del pagador"
            class="col-12 col-m-8 col-l-9"
            {{readonly}}
        />
    </div>

    <div class="row my-2">
        <label for="created_at" class="col-12 col-m-4 col-l-3">Fecha:</label>
        <input 
            type="text"
            name="created_at"
            id="created_at"
            value="{{created_at}}"
            placeholder="Fecha de creación"
            class="col-12 col-m-8 col-l-9"
            {{readonly}}
        />
    </div>

    <div class="row">
        <div class="col-12 right">
            <button class="" id="btnCancel" type="button">{{cancelLabel}}</button>
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
                window.location.assign("index.php?page=Modules-Transactions-Transactions");
            });
    });
</script>
