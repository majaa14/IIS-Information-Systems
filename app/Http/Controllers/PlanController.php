<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = null;
        if ($user) {
            $userId = $user->id;
        }

        $spoje = DB::table('Spoj')
        ->select('Spoj.ID_spoj', 'Spoj.čas odjezdu as cas_odjezdu', 'den v týdnu as Den', 'Spoj.FK_číslo_linky', 'FK_vozidlo', 'Zastávka_počáteční.název zastávky as počáteční_zastávka', 'Zastávka_konečná.název zastávky as konečná_zastávka')
        ->join('Linka', 'Spoj.FK_číslo_linky', '=', 'Linka.číslo linky')
        ->join('Zastávka as Zastávka_počáteční', 'Spoj.FK_ID_počáteční_zastávka', '=', 'Zastávka_počáteční.ID_zastávky')
        ->join('Zastávka as Zastávka_konečná', 'Spoj.FK_ID_konečná_zastávka', '=', 'Zastávka_konečná.ID_zastávky')
        ->where('Spoj.FK_řidič', '=', $userId)
        ->orderby('Den', 'asc')
        ->orderby('cas_odjezdu', 'asc')
        ->get();

        return view('plan', ['spoje' => $spoje]);
    }

}
