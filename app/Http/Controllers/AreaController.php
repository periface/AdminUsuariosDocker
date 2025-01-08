<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AreaService;
use App\Services\UserService;

class AreaController extends Controller
{

    protected $areaService;
    protected $userService;

    public function __construct(AreaService $areaService, UserService $userService){
        $this->areaService = $areaService;
        $this->userService = $userService;
    }
    //
    
    public function index(){
        $areas = $this->areaService->getAllAreas();

        return view('areas.index', compact('areas'));
    }

    public function create(){
        $users = $this->userService->getAllUsers();
        return view('areas.add', compact('users'));
    }

    public function createOrEdit(Area $area){
        
        $users = $this->userService->getAllUsers();

        return view('areas.createEdit', compact('area', 'users'));

    }
}
