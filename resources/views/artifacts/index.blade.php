<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="asset-path" content="{{ asset('') }}">
    <link id="themeStyle" rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="{{ asset('js/change.js')}}"></script>


    <title>Compilador FHIR</title>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="container mt-5">
    <div class="row">
        <!-- Column 1 -->
        <div class="col-md-6">
            <h1 class="mb-4">Compilador FHIR</h1>

            @if(session('success'))
                <div id="flash-message" class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div id="flash-message" class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- File Upload Form -->
            <form action="{{ route('artifacts.upload') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="file" class="form-label">Selecione o template FHIR em Formato .json:</label>
                    <input class="form-control" type="file" name="file" id="file">
                </div>
                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>

            <hr>

            <!-- Artifact Selection Form -->
            <h2 class="mb-3">Arquivos existentes:</h2>
            <form id="generateForm" action="{{ route('artifacts.generate') }}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="selectedArtifact" class="form-label">Seleciona o arquivo disponível:</label>
                    <select multiple class="form-control" name="selectedArtifact" id="selectedArtifact">
                        @foreach($artifacts as $artifact)
                            <option value="{{ $artifact }}">{{ basename($artifact) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Buttons for Actions -->
                <div class="d-flex align-items-center mt-3 gap-2">
                    <button type="button" class="btn btn-success" id="gerarFormulario">JSON Formato</button>
                    <button type="button" class="btn btn-success" id="gerarFormularioView">Gerar Formulário</button>
                </div>
            </form>
        </div>
   
        <!-- Column 2: Tabs "View" and "JSON" -->
        <div class="col-md-6">
            <div class="d-flex justify-content-end mt-3 gap-2">
                <button type="button" class="btn btn-secondary btn-sm" onclick="mudarCSS('css/style.css')">
                    Aplicar 1º style
                </button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="mudarCSS('css/style_1.css')">
                    Aplicar 2º style
                </button>                
            </div>
                     

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
                    <div class="container" id="viewContent"></div>
                </div>
                <div class="tab-pane fade" id="json">
                    <div class="container" id="jsonContent"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Message Timeout Script -->
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#flash-message').fadeOut('slow');
            }, 3000); // 3 seconds
        });
    </script>

    <!-- JSON Format Generation Script -->
    <script>
        $(document).ready(function() {
            $('#gerarFormulario').click(function() {
                $.ajax({
                    url: '/artifacts/generate',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        selectedArtifact: $('#selectedArtifact').val()
                    },
                    success: function(data) {
                        var fileContent = data.fileContent;

                        // Update JSON content
                        $('#jsonContent').html(`
                            <p>Arquivo Selecionado:  ${data.selectedArtifactName}</p>
                            <pre>${JSON.stringify(fileContent, null, 2)}</pre>
                        `);

                        // Activate the JSON tab
                        $('#json-tab').tab('show');  // Switch to JSON tab
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });
        });
    </script>

    <!-- Form Generation Script -->
    <script>
        $(document).ready(function() {
            $('#gerarFormularioView').click(function() {
                $.ajax({
                    url: '/artifacts/generate/FHIRQuestionnaire',
                    method: 'GET',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        selectedArtifact: $('#selectedArtifact').val()
                    },
                    success: function(response) {
                        // Update View content
                        $('#viewContent').html(response);  

                        // Activate the View tab
                        $('#view-tab').tab('show');  // Switch to View tab
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });
        });
    </script>
</body>
</html>






