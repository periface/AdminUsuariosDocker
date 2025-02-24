<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Secretaria;
use App\Services\AreaService;
use App\Services\UserService;

class AreaController extends Controller
{

    protected $areaService;
    protected $userService;

    public function __construct(AreaService $areaService, UserService $userService)
    {
        $this->areaService = $areaService;
        $this->userService = $userService;
    }
    //

    public function index()
    {
        return view('areas.index');
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

    public function fetchAreas() {
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
