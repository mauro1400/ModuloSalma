@if (isset($error))
    <div class="container-fluid">
        <div class="row justify-content-end">
            <div class="col-4">
                <div id="errorAlert" class="alert alert-danger fade show" role="alert" style="display: none;">
                    <h6>Error Inesperdado del Sistema:</h6>
                    <h5>{{ $error }}</h5>
                    <p>Este mesajde desaparecera en 10 segundos</p>
                </div>
            </div>
        </div>
    </div>
@endif
<script>
    document.getElementById("errorAlert").style.display = "block";
    // Desvanecer la alerta después de 10 segundos
    setTimeout(function() {
        document.getElementById("errorAlert").style.display = "none";
    }, 10000);
</script>
