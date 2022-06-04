<?php

namespace App\Http\Controllers;

use Illuminate\Http\UploadedFile;
use App\Models\Empresa;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\EmpresaRequest;
use App\Http\Requests\EmpresaUpdateRequest;

class EmpresaController extends Controller
{

    public function index()
    {
        $empresas = Empresa::get()->toJson(JSON_PRETTY_PRINT);

        if (!$empresas) {
            return response()->json([
                "message" => "Nenhuma empresa cadastrada"
            ], 404);
        } else {
            return response($empresas, 200);
        }
    }

    public function store(EmpresaRequest $request)
    {
        $empresa = new Empresa;

        $empresa->razao = $request->razao;
        $empresa->nome = $request->nome;
        $empresa->cnpj = $request->cnpj;
        $file = $request->file('logo');

        if (!is_numeric($empresa->cnpj)) {
            return response()->json([
                "message" => "O CNPJ só pode conter números!"
            ], 400);
        }

        if ($request->hasFile('logo') && $file->isValid()) {

            $requestLogo = $request->logo;

            $logoName = strtotime("Now") . $requestLogo->getClientOriginalName();

            $requestLogo->move(Storage::path('img'), $logoName);

            $empresa->logo = $logoName;
        } elseif (!$request->hasFile('logo')) {
            $logoName = 'noimage.png';

            $empresa->logo = $logoName;
        }

        $empresa->save();

        return response()->json([
            "message" => "Empresa criada com sucesso!"
        ], 201);
    }

    public function show($cnpj)
    {
        if (Empresa::where('cnpj', $cnpj)->exists()) {
            $empresa = Empresa::firstWhere('cnpj', $cnpj);

            $img = file_get_contents(Storage::path('img/' . $empresa->logo));

            $base64 = base64_encode($img);

            return response([
                "Empresa" => $empresa,
                "base64" => $base64
            ], 200);
        } else {
            return response()->json([
                "message" => "Empresa não encontrada"
            ], 404);
        }
    }

    public function get($cnpj)
    {
        if (Empresa::where('cnpj', $cnpj)->exists()) {
            $empresa = Empresa::where('cnpj', $cnpj)->get()->toJson(JSON_PRETTY_PRINT);
            return response($empresa, 200);
        } else {
            return response()->json([
                "message" => "Empresa não encontrada"
            ], 404);
        }
    }


    public function update(EmpresaUpdateRequest $request, $cnpj)
    {
        if (Empresa::where('cnpj', $cnpj)->exists()) {
            $empresa = Empresa::firstWhere('cnpj', $cnpj);
            $data = $request->all();
            $file = $request->file('logo');

            if (!$data['razao']) {
                $data['razao'] = $empresa->razao;
            }

            if (!$data['nome']) {
                $data['nome'] = $empresa->nome;
            }

            if (!$data['cnpj']) {
                $data['cnpj'] = $empresa->cnpj;
            }

            if (!is_numeric($data['cnpj'])) {
                return response()->json([
                    "message" => "O CNPJ só pode conter números!"
                ], 400);
            }

            if (strlen($data['cnpj']) < 14) {
                return response()->json([
                    "error" => "O CNPJ não atingiu a quantidade necessária de caracteres (14 caracteres)"
                ], 400);
            }

            if ($request->hasFile('logo') && $file->isValid()) {

                unlink(Storage::path('img/' . $empresa->logo));

                $requestLogo = $request->logo;

                $logoName = strtotime("Now") . $requestLogo->getClientOriginalName();

                $requestLogo->move(Storage::path('img'), $logoName);

                $data['logo'] = $logoName;
            }

            $empresa->update($data);

            return response()->json([
                "message" => "Empresa atualizada com sucesso!"
            ], 200);
        } else {
            return response()->json([
                "message" => "Empresa não encontrada"
            ], 404);
        }
    }

    public function destroy($cnpj)
    {
        if (Empresa::where('cnpj', $cnpj)->exists()) {
            $empresa = Empresa::firstWhere('cnpj', $cnpj);

            unlink(Storage::path('img/' . $empresa->logo));

            $empresa->delete();

            return response()->json([
                "message" => "Empresa deletada"
            ], 202);
        } else {
            return response()->json([
                "message" => "Empresa não encontrada"
            ], 404);
        }
    }
}

/*
    if ($request->hasFile('logo') && $file->isValid()) {
        
        $imagenameWithExt = $request->file('logo')->getClientOriginalName();
           
        $imagename = pathinfo($imagenameWithExt, PATHINFO_FILENAME);
           
        $extension = $request->file('logo')->getClientOriginalExtension();
            
        $imageNameToStore= $imagename.'_'.time().'.'.$extension;
           
        $path = $request->file('logo')->storeAs('public/img/logo', $imageNameToStore);
    }
*/

/*$messages = [
    'razao.required' => 'A Razão Social é obrigatória;',
    'razao.max' => 'A Razão Social ultrapassou o limite de caracteres (Máximo de 255 caracteres);',
    'razao.unique' => 'A Razão Social já existe;',
    'nome.max' => 'O Nome Fantasia ultrapassou o limite de caracteres (Máximo de 255 caracteres);',
    'cnpj.required' => 'O CNPJ é obrigatório;',
    'cnpj.size' => 'O CNPJ não atingiu a quantidade de caracteres (14 caracteres);',
    'cnpj.unique' => 'O CNPJ já existe;',
    'logo.image' => 'O arquivo não é uma imagem;',
    'logo.mimes' => 'A imagem deve ser: jpeg, png, jpg ou svg;',
    'logo.max' => 'A imagem ultrapassou o limite de upload (Máximo de 2MB);'
    ];

    $request->validate(
    [
    'razao' => 'unique:empresas|required|max:255',
    'nome' => 'max:255',
    'cnpj' => 'unique:empresas|required|size:14',
    'logo' => 'image|mimes:jpeg,png,jpg,svg|max:2048'
    ],
    $messages
    );
*/

/*
$requestLogo = $request->logo;

//$img = file_get_contents($requestLogo);

//$extension = $requestLogo->extension();

$logoName = strtotime("Now") . $requestLogo->getClientOriginalName();

$requestLogo->move(Storage::path('img'), $logoName);

//$base64 = "data:image/" . $extension . ";base64," . base64_encode($img);

//$base64 =  base64_encode($requestLogo);

//$logoPath = Storage::path('img/') . $logoName;
*/