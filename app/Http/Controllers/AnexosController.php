<?php

namespace App\Http\Controllers;

use App\Models\Anexo;
use App\Models\EvaluacionResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AnexosController extends Controller
{
    public function index($id)
    {
        $evaluacionResult = EvaluacionResult::find($id);
        $anexos = $evaluacionResult->anexos;
        return view('anexos.index', compact('anexos', 'evaluacionResult'));
    }
    public function delete($id)
    {
        try {
            $anexo = Anexo::find($id);
            $filePath = storage_path('app/public/' . $anexo->filePath);
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
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|max:10240|mimes:pdf,docx,doc',
        ]);
        if ($request->file('file')->isValid()) {
            try {
                $evaluacionResult = EvaluacionResult::find($request->id);
                $pathName = 'anexos/' . $evaluacionResult["id"];
                $request->file->store($pathName, 'public');
                $path = Storage::url($pathName . '/' . $request->file->hashName());
                Anexo::create([
                    'fileName' => $request->file->getClientOriginalName(),
                    'filePath' => $path,
                    'fileType' => $request->file->getClientMimeType(),
                    'fileSize' => $request->file->getSize(),
                    'fileExtension' => $request->file->extension(),
                    'fileDescription' => 'Anexo de la evaluación',
                    'fileStatus' => 'Pendiente',
                    'evaluacionResultId' => $evaluacionResult["id"]
                ]);

                return response()->json([
                    'status' => 'success',
                    'data' => $path,
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
        }
        return response()->json([
            'status' => 'error',
            'data' => null,
            'statusCode' => 422,
            'error' => 'El archivo no es valido'
        ]);
    }
}
