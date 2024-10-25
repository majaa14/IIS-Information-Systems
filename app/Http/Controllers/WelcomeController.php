<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WelcomeController
{
    public function index()
    {
        $spoje = DB::table('Spoj')->select('čas odjezdu as cas_odjezdu','den v týdnu as den_v_tydnu', 'z1.název zastávky as nazev_1_zastavky', 'z2.název zastávky as nazev_2_zastavky', 'ID_spoj as id', 'FK_číslo_linky as cislo_linky')
        ->join('Zastávka as z1', 'Spoj.FK_ID_počáteční_zastávka', '=', 'z1.ID_zastávky')
        ->join('Zastávka as z2', 'Spoj.FK_ID_konečná_zastávka', '=', 'z2.ID_zastávky')
        ->orderBy('den v týdnu', 'asc')
        ->orderBy('čas odjezdu', 'asc')
        ->get();

        $linky = DB::table('Linka')->select('číslo linky as cislo_linky')->get();

        return view('welcome', ['spoje' => $spoje, 'linky' => $linky]);
    }

    public function findSpoj(Request $request)
    {
        $den = $request->input('Den');
        $cas = $request->input('cas');
        $linka = $request->input('linka');

        $query = DB::table('Spoj')
        ->select('čas odjezdu as cas_odjezdu', 'den v týdnu as den_v_tydnu', 'z1.název zastávky as nazev_1_zastavky', 'z2.název zastávky as nazev_2_zastavky', 'ID_spoj as id', 'FK_číslo_linky as cislo_linky')
        ->join('Zastávka as z1', 'Spoj.FK_ID_počáteční_zastávka', '=', 'z1.ID_zastávky')
        ->join('Zastávka as z2', 'Spoj.FK_ID_konečná_zastávka', '=', 'z2.ID_zastávky')
        ->orderBy('den v týdnu', 'asc')
        ->orderBy('čas odjezdu', 'asc');

        if ($den != 0) {
            $query->where('den v týdnu', '=', $den);
        }
        if ($linka != 0) {
            $query->where('FK_číslo_linky', '=', $linka);
        }
        if ($cas != null) {
            $query->whereRaw('CONVERT(TIME, [čas odjezdu]) >= ?', [$cas]);
        }
        $spoje = $query->get();

        $linky = DB::table('Linka')->select('číslo linky as cislo_linky')->get();

        return view('welcome', ['spoje' => $spoje, 'linky' => $linky]);
    }

    public function getDetail($id)
    {
        $selectedLinka = DB::table('Spoj')
        ->select('FK_číslo_linky')
        ->where('ID_spoj', '=', $id)
        ->first()->FK_číslo_linky;

        $smer = DB::table('Spoj')
        ->select('směr')
        ->where('ID_spoj', '=', $id)
        ->first()->směr;

        $prvni_cas = DB::table('Spoj')
        ->select('čas odjezdu as cas_odjezdu')
        ->where('ID_spoj', '=', $id)
        ->first()->cas_odjezdu;

        $prvni_cas = Carbon::parse($prvni_cas)->format('H:i');
        $cas = [Carbon::parse($prvni_cas)->format('H:i')];

        $zastavkyLinky = DB::table('Zastávka na lince')
        ->select('pořadí zastávky as poradi', 'Zastávka.název zastávky as nazev', 'ID_zastávky')
        ->join('Zastávka', 'Zastávka.ID_zastávky', '=', 'Zastávka na lince.FK_číslo_zastávky')
        ->where('FK_číslo_linky', '=', $selectedLinka)
        ->orderBy('pořadí zastávky', 'asc')
        ->get();

        $poradiValues = $zastavkyLinky->pluck('poradi')->toArray();
        $nazevValues = $zastavkyLinky->pluck('nazev')->toArray();
        $idValues = $zastavkyLinky->pluck('ID_zastávky')->toArray();

        if ($smer == 'A') {
            $nazevValues = array_reverse($nazevValues);
            $idValues = array_reverse($idValues);
        }

        for ($i = 1; $i < count($idValues); $i++) {
            $casy = DB::table('Časový rozvrh')
            ->select('čas_mezi_zastávkami')
            ->where('FK_ID_A_zastávka', '=', $idValues[$i-1])
            ->where('FK_ID_B_zastávka', '=', $idValues[$i])
            ->first()->čas_mezi_zastávkami;

            $casy = Carbon::parse($casy);

            $total_time = Carbon::parse($cas[$i - 1])->addMinutes(Carbon::parse($casy)->minute)->addHours(Carbon::parse($casy)->hour);

            $cas[] = $total_time->format('H:i');
        }

        return view('spoj-detail', ['poradi' => $poradiValues, 'nazev' => $nazevValues, 'cas' => $cas]);
    }
}
