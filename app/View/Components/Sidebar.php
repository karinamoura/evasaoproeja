<?php

namespace App\View\Components;

use App\Models\Institution;
use App\Models\Oferta;
use App\Models\School;
use App\Models\User;
use App\Models\Questionario;
use App\Models\QuestionarioOferta;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Sidebar extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $userCount = User::count();
        view()->share('userCount',$userCount);

        $RoleCount = Role::count();
        view()->share('RoleCount',$RoleCount);

        $PermissionCount = Permission::count();
        view()->share('PermissionCount',$PermissionCount);

        $InstitutionCount = Institution::count();
        view()->share('InstitutionCount',$InstitutionCount);

        $SchoolCount = School::count();
        view()->share('SchoolCount',$SchoolCount);



        $OfertaCount = Oferta::count();
        view()->share('OfertaCount',$OfertaCount);

        $QuestionarioCount = Questionario::count();
        view()->share('QuestionarioCount',$QuestionarioCount);

        $QuestionarioOfertaCount = QuestionarioOferta::count();
        view()->share('QuestionarioOfertaCount',$QuestionarioOfertaCount);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sidebar');
    }
}
