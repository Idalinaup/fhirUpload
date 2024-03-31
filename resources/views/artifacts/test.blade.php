@php
    //dd($object->getItem());
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
        <h1 class="mt-4 mb-4">FHIR Questionnaire Viewer</h1>
        
        <div class="questionnaire-info">
            <p>
                The following Questionnaire was loaded from:  {{$object->getUrl()}}
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
</body>

</html>

