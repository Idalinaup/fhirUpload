
@php
    // dd($object->getItem());
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FHIR Questionnaire Viewer</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 20px;
            color: #333;
        }

        h2 {
            color: #0066cc;
        }

        p {
            margin-bottom: 20px;
        }

        div {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h2>FHIR Questionnaire Viewer</h2>
    
    <p>
        The following Questionnaire was loaded from:  {{$object->getUrl()}};
    </p>

    <div>
        @foreach($object->getItem() as $item)
            @include('artifacts.structure.item')
        @endforeach
    </div>
</body>
</html>