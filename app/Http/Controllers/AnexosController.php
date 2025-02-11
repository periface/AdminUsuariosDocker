<?php

namespace App\Http\Controllers;

use App\Models\Anexo;
use App\Models\Evaluacion;
use App\Models\EvaluacionResult;
use App\Models\Indicador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AnexosController extends Controller
{
    public function index($id)
    {
        $evaluacionResult = EvaluacionResult::find($id);
        $evaluacion = Evaluacion::find($evaluacionResult["evaluacionId"]);
        $indicador = Indicador::find($evaluacion["indicadorId"]);
        $anexos = Anexo::where('evaluacionResultId', $id)->orderBy('created_at', 'desc')->get();
        return view('anexos.index', compact('anexos', 'evaluacionResult', 'evaluacion', 'indicador'));
    }
    public function get_rows($id)
    {
        $anexos = Anexo::where('evaluacionResultId', $id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $anexos,
            'statusCode' => 200,
            'error' => null
        ]);
    }
    public function delete($id)
    {
        try {
            $anexo = Anexo::find($id);
            $filePath = storage_path('app/public/' . $anexo["filePath"]);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $anexo->delete();
            return response()->json([
                'status' => 'success',
                'data' => null,
                'statusCode' => 200,
                'error' => null
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'statusCode' => 500,
                'error' => $th->getMessage()
            ]);
        }
    }
    // Soporte para subir multiples archivos
    public function upload(Request $request)
    {
        $request_all = $request->all();
        $validator = Validator::make($request_all, [
            'file.*' => 'required|max:10240|mimes:pdf,docx,doc',
        ], [
            'file.*.required' => 'El archivo es requerido',
            'file.*.max' => 'El archivo no debe pesar mas de 10MB',
            'file.*.mimes' => 'El archivo debe ser un PDF, DOCX o DOC'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'statusCode' => 422,
                'error' => $validator->errors()
            ]);
        }
        try {
            $files = $request->file('file');
            foreach ($files as $file) {
                $evaluacionResult = EvaluacionResult::find($request->id);
                $pathName = 'anexos/' . $evaluacionResult["id"];
                $file->store($pathName, 'public');
                $path = Storage::url($pathName . '/' . $file->hashName());
                Anexo::create([
                    'fileName' => $file->getClientOriginalName(),
                    'filePath' => $path,
                    'fileType' => $file->getClientMimeType(),
                    'fileSize' => $file->getSize(),
                    'fileExtension' => $file->extension(),
                    'fileDescription' => 'Anexo de la evaluación',
                    'fileStatus' => 'Pendiente',
                    'evaluacionResultId' => $evaluacionResult["id"]
                ]);
            }
            return response()->json([
                'status' => 'success',
                'data' => null,
                'statusCode' => 422,
                'error' => null
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'statusCode' => 500,
                'error' => $th->getMessage()
            ]);
        }
        return response()->json([
            'status' => 'error',
            'data' => null,
            'statusCode' => 422,
            'error' => 'El archivo no es valido'
        ]);
    }
}
