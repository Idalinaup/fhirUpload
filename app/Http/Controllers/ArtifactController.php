<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

use DCarbone\PHPFHIRGenerated\R4\PHPFHIRResponseParserConfig;
use DCarbone\PHPFHIRGenerated\R4\PHPFHIRResponseParser; // Add this import statement
use Illuminate\Support\Facades\Log;
use DCarbone\PHPFHIRGenerated\R4\FHIRResource\FHIRDomainResource\FHIRQuestionnaire;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRBackboneElement\FHIRQuestionnaire\FHIRQuestionnaireItem;
use DCarbone\PHPFHIRGenerated\R4\FHIRResource\FHIRDomainResource\FHIRLinkage;



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

    public function parseFHIRQuestionnaire(Request $request){
        $selectedArtifact = $request->input('selectedArtifact');
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

        dd($object);

        $data = [];

        if ($object instanceof FHIRQuestionnaire) {
            $data['Url'] = $object->getUrl();
            $data['Identifier'] = $object->getIdentifier();
            $data['Version'] = $object->getVersion();
            $data['Name'] = $object->getName();
            $data['Title'] = $object->getTitle();
            $data['Status'] = $object->getStatus();
            $data['subjectType'] = $object->getsubjectType();
            $data['description'] = $object->getdescription();

            Log::info($data['Url']);

            $ItemsData = [];

            $Items = $object->getItem();
            
            
            foreach ($Items as $item) {
                Log::info("----------------");

                $linkId = $item->getLinkId();
                Log::info($linkId);
                
                $text = $item->gettext();
                Log::info($text);
                

                if ($item instanceof FHIRQuestionnaireItem) {
                    $typeObject = $item->getType();
                
                    $type = $typeObject->getValue();
                
                    Log::info($type);
                }

                $Items_1 = $item->getItem();
                //Log::info($Items_1);

                foreach ($Items_1 as $item_1) {
                    $linkId = $item_1->getLinkId();
                    Log::info($linkId);
                    
                    $text = $item_1->gettext();
                    Log::info($text);

                    if ($item_1 instanceof FHIRQuestionnaireItem) {
                        $typeObject = $item_1->getType();
                    
                        $type = $typeObject->getValue();
                    
                        Log::info($type);
                    }
                }
            
            }
             
        }

        return response()->json(['data' => $data]);
    }

    public function Testparse(){
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

        return view('artifacts.test', compact('object'));
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


    public function removerArtifacts(){
    // Remova os arquivos do diretório 'artifacts'
    $artifacts = Storage::files('artifacts');
    Storage::delete($artifacts);

    return response()->json(['message' => 'Arquivos removidos com sucesso']);
    }



}