<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Evaluacion;
use App\Models\Secretaria;
use App\Services\AreaService;
use App\Services\EvaluacionService;
use App\Services\UserService;

class AreaController extends Controller
{

    protected $areaService;
    protected $userService;
    protected $evaluacionService;
    public function __construct(
        AreaService $areaService,
        UserService $userService,
        EvaluacionService $evaluacionService
    ) {
        $this->areaService = $areaService;
        $this->userService = $userService;
        $this->evaluacionService = $evaluacionService;
    }
    //

    public function index()
    {
        return view('areas.index');
    }

    public function details($id)
    {
        $area = Area::find($id);
        if (!$area) {
            return redirect()->route('areas.index');
        }
        if ($area->responsableId != null) {
            $responsable = $this->userService->getUserById($area->responsableId);
            $area['responsable'] = $responsable->nombre . ' ' . $responsable->apPaterno . ' ' . $responsable->apMaterno;
        }
        else {
            $area['responsable'] = 'Sin responsable';
        }
        $evaluaciones = Evaluacion::where('areaId', $id)->get();

        foreach ($evaluaciones as $evaluacion) {
            $evaluacion = $this->evaluacionService->get_evaluacion_stats($evaluacion);
        }
        return view('areas.details', compact('area', 'evaluaciones'));
    }
    public function create()
    {
        $users = $this->userService->getAllUsers();
        $secretarias = Secretaria::all();
        return view('areas.add', compact('users', 'secretarias'));
    }
    public function createOrEdit(Area $area)
    {

        $users = $this->userService->getAllUsers();
        $secretarias = Secretaria::all();
        return view('areas.createEdit', compact('area', 'users', 'secretarias'));
    }

    public function fetchAreas()
    {
        $user = auth()->user();
        $role = $user->getRoleNames();

        switch ($role[0]) {
            case 'ADM':
                $areas = $this->areaService->getAllAreas();
                break;

            default:
                $areas = $this->areaService->getAreaById($user->areaId, $user->id);
                break;
        }
        return view('areas.table', compact('areas'));
    }
}
