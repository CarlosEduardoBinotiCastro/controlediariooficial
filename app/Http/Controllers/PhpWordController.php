<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PhpWordController extends Controller
{
    //

public function criarTemplate(){
    $wordTest = new \PhpOffice\PhpWord\PhpWord();

    $newSection = $wordTest->addSection();

    $desc1 = "Escreva Aqui o conteúdo de forma corrida, sem delimitações de margem e caixas de texto.";

    $newSection->addText($desc1);

    $objectWriter = \PhpOffice\PhpWord\IOFactory::createWriter($wordTest, "Word2007");
    try{
        $objectWriter->save(public_path('template.docx'));
    } catch(\Exception $e) {

    }
    return response()->download(public_path('template.docx'));
}


public function lerDocumento(){

    // $word = \PhpOffice\PhpWord\IOFactory::load('teste.docx');
    // $word = \PhpOffice\PhpWord\IOFactory::load('TestOdt.odt', 'ODText');
    $word = \PhpOffice\PhpWord\IOFactory::load('TestWord.docx');
    // $word = \PhpOffice\PhpWord\IOFactory::load('Lista2.docx');

    dd($word);

    // print_r($word);
    // dd($word->getSections()[0]->getElements()[0]->getElements()[0]->getText());
    // print_r($word->getSections()[0]->getElements()[0]->getElements()[0]->getText());
    // print_r($word->getSections()[0]->getElements()[0]->getElements()[1]->getText());

    foreach($word->getSections()[0]->getElements() as $elements){
            dd($elements->GetFontStyle()->GetParagraph()->getLineHeight());
            echo "<br>";
             print_r($elements->getText());
    }

}

}
