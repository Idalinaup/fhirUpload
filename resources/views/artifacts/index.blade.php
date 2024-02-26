<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>Compilador FHIR</title>
</head>


<body class="container mt-5">

    <div class="row">
        <!-- Coluna 1 -->
        <div class="col-md-6">
        <h1 class="mb-4">Compilador FHIR</h1>

        @if(session('success'))
            <div id="flash-message" class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('artifacts.upload') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="file" class="form-label">Selecione o template FHIR em Formato .json ou .xml:</label>
                <input class="form-control" type="file" name="file" id="file" accept=".json,.xml">
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>

        <hr>

        <h2 class="mb-3">Arquivos existentes:</h2>
        
        <form action="{{ route('artifacts.generate') }}" method="post" >
            @csrf
            <div class="mb-3">
                <label for="selectedArtifact" class="form-label">Seleciona o arquivo disponível:</label>
                <select multiple class="form-control" name="selectedArtifact" id="selectedArtifact">
                    @foreach($artifacts as $artifact)
                        <option value="{{ $artifact }}">{{ basename($artifact) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="button" class="btn btn-success" id="gerarFormulario">Gerar Formulário Json</button>
            <!-- <button type="button" class="btn btn-danger" onclick="confirmRemoveSelected()">Apagar</button> -->
        </form>

        <div style="margin-top: 10px;"></div>

        <form action="{{ route('artifacts.generateView') }}" method="post" >
            @csrf
            <button type="button" class="btn btn-success" id="gerarFormularioView">Gerar Formulário</button>
        </form>

    </div>

    <!-- Coluna 2: Tabs "View" e "JSON" -->
    <div class="col-md-6">
        <ul class="nav nav-tabs" id="myTabs">
            <li class="nav-item">
                <a class="nav-link active" id="view-tab" data-bs-toggle="tab" href="#view">View</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="json-tab" data-bs-toggle="tab" href="#json">JSON</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="view">
                <div class="container" id="viewContent">
                </div>
            </div>
            <div class="tab-pane fade" id="json">
                <div class="container" id="jsonContent">
                </div>
            </div>
        </div>
    </div>
</body>


</html>


<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('#flash-message').fadeOut('slow');
        }, 3000); // <-- time in milliseconds, 3000ms = 3s
    });
</script>

<script>
    $(document).ready(function() {
        $('#gerarFormularioView').click(function() {
            $.ajax({
                url: '/artifacts/generate/FHIRQuestionnaire',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    selectedArtifact: $('#selectedArtifact').val(), 
                 },
                 success: function(response) {
                    var data = response.data;

                    $('#viewContent').html(`
                    <h2>Conteúdo do Arquivo </h2>
                    <p>url: ${data.Url.value}</p>

                    

                    `);


                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        });

    });
</script>


<script>
    $(document).ready(function() {
        $('#gerarFormulario').click(function() {
            $.ajax({
                url: '/artifacts/generate',
                method: 'POST',
                data: {
                    id: 1,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    selectedArtifact: $('#selectedArtifact').val(), 
                 },
                 success: function(data) {
                    var fileContent = data.fileContent;

                    $('#jsonContent').html(`
                        <h2>Conteúdo do Arquivo</h2>
                        <p>Arquivo Selecionado:  ${data.selectedArtifactName}</p>
                        <pre>${JSON.stringify(fileContent, null, 2)}</pre>
                    `);
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        });
    });
</script>

<style>
.options {
    display: flex;
    gap: 10px;
}
</style>





