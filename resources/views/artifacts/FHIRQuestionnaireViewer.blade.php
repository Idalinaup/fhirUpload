@php
    //dd($object->getItem());
    // Supondo que $questionnaire seja uma instância de Questionnaire e $answers seja um array de respostas
    
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FHIR Questionnaire Viewer</title>
</head>

<body>
    <form action="{{ route('artifacts.response') }}" method="post" enctype="multipart/form-data">
        <h1 class="mt-4 mb-4">FHIR Questionnaire Viewer</h1>
        
        <div class="questionnaire-info">
            <p>
                The following Questionnaire was loaded from:  {{$object->getId()}}
            </p>
        </div>

        <div>
            @foreach($object->getItem() as $item)
                <div class="questionnaire-item">
                    @include('artifacts.structure.item')
                </div>
            @endforeach
        </div>

        @yield('form_script')
        <br>  
        @csrf
        <button id="submit-button" type="submit" class="btn btn-success" >SUBMIT</button>
    </form>
</body>

</html>

<script> 
    if ( $item->getRequired() == "true" && $item->getLinkId() == null) {
        document.getElementById('submit-button').disabled = false;
    } else {
        console.log("Item is not required", itemRequired);
        console.log("Item", itemRequired);
        document.getElementById('submit-button').disabled = true;
    }
</script>