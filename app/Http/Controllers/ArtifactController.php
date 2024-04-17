<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

use DCarbone\PHPFHIRGenerated\R4\PHPFHIRResponseParserConfig;
use DCarbone\PHPFHIRGenerated\R4\PHPFHIRResponseParser; // Add this import statement


class ArtifactController extends Controller
{
    public function index()
    {
        $artifacts = Storage::files('artifacts');

        return view('artifacts.index', compact('artifacts'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:json,xml|max:2048',
        ]);


        $file = $request->file('file');
        $file->storeAs('artifacts', $file->getClientOriginalName());

        return Redirect::route('artifacts.index')->with('success', 'Arquivo enviado com sucesso!');
    }

    public function generateForm(Request $request) {
        $selectedArtifact = $request->input('selectedArtifact');
        $artifactsPath = storage_path('app');
        $selectedArtifactName = is_array($selectedArtifact) ? $selectedArtifact[0] : $selectedArtifact;
        $filePath = $artifactsPath . '/' . $selectedArtifactName;
        $content = file_get_contents($filePath);
    
        // File content handling
        if (file_exists($filePath)) {
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
    
            if ($extension === 'json') {
                $decodedContent = json_decode($content, true);
                return response()->json(['selectedArtifactName' => $selectedArtifactName, 'fileContent' => $decodedContent]);
            } elseif ($extension === 'xml') {
                $xml = simplexml_load_string($content);
                return response()->json(['selectedArtifactName' => $selectedArtifactName, 'fileContent' => $xml]);
            } else {
                return response()->json(['selectedArtifactName' => $selectedArtifactName, 'fileContent' => 'Tipo de arquivo não suportado']);
            }
        } else {
            return response()->json(['selectedArtifactName' => $selectedArtifactName, 'fileContent' => 'Arquivo não encontrado']);
        }
    }

    public function FHIRQuestionnaireViewer(){
        $selectedArtifact = "artifacts/Questionnaire-sirb-adverse-event-questionnaire-populate.json";
        $artifactsPath = storage_path('app');

        $selectedArtifactName = is_array($selectedArtifact) ? $selectedArtifact[0] : $selectedArtifact;
        $filePath = $artifactsPath . '/' . $selectedArtifactName;

        $config = new PHPFHIRResponseParserConfig([
             'registerAutoloader' => true, // use if you are not using Composer
             'sxeArgs' => LIBXML_COMPACT | LIBXML_NSCLEAN // choose different libxml arguments if you want, ymmv.
         ]);


        $parser = new PHPFHIRResponseParser($config);

        $content = file_get_contents($filePath);

        $object = $parser->parse($content);

        return view('artifacts.FHIRQuestionnaireViewer', compact('object'));
    }

    public function FHIRQuestionnaireResponse(Request $request){
    dd($request->all());
    
    }
}