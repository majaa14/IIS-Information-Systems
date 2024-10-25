<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpojeController extends Controller
{
    public function index()
    {
        $linky = DB::table('Linka')
        ->select('číslo linky as id')
        ->orderBY('číslo linky', 'asc')
        ->get();

        return view('spoje', ['linky' => $linky]);
    }

    public function createSpoj(Request $request)
    {
        try {
            $linka = DB::table('Linka')
            ->select(['FK_ID_hraniční_zastávka_A', 'FK_ID_hraniční_zastávka_B'])
            ->where('číslo linky', '=', $request->input('linka'))
            ->orderBy('číslo linky', 'asc')
            ->first();

            if ($request->input('smer') == true) {
                $pocatek = $linka->FK_ID_hraniční_zastávka_B;
                $konec = $linka->FK_ID_hraniční_zastávka_A;
                $smer = 'B';
            } else {
                $pocatek = $linka->FK_ID_hraniční_zastávka_A;
                $konec = $linka->FK_ID_hraniční_zastávka_B;
                $smer = 'A';
            }

            $spojData = [
                'den v týdnu' => $request->input('Den'),
                'Čas odjezdu' => $request->input('cas'),
                'FK_číslo_linky' => $request->input('linka'),
                'FK_ID_počáteční_zastávka' => $pocatek,
                'FK_ID_konečná_zastávka' => $konec,
                'směr' => $smer,
            ];

            \DB::table('Spoj')->insert($spojData);

            session()->flash('success');
            return redirect()->route('spoj.create')->with(['success' => true, 'message' => 'Spoj úspěšně přidán!']);
        } 
        catch (\Exception $e) {
            session()->flash('error');
            return redirect()->route('spoj.create')->with(['success' => false, 'message' => 'Error']);
        }
    }

    public function getSpojeData(Request $request)
    {
        $selectedLinka = $request->input('linka');

        $spoje = DB::table('Spoj')
            ->select('ID_spoj', 'čas odjezdu as cas_odjezdu', 'den v týdnu as den', 'FK_řidič', 'FK_vozidlo', 'users.name as jmeno', 'Zastávka_počáteční.název zastávky as jmeno_počáteční_zastávka', 'Zastávka_konečná.název zastávky as jmeno_konečná_zastávka')
            ->leftJoin('users', 'users.id', '=', 'Spoj.FK_řidič')
            ->join('Zastávka as Zastávka_počáteční', 'Spoj.FK_ID_počáteční_zastávka', '=', 'Zastávka_počáteční.ID_zastávky')
            ->join('Zastávka as Zastávka_konečná', 'Spoj.FK_ID_konečná_zastávka', '=', 'Zastávka_konečná.ID_zastávky')
            ->where('FK_číslo_linky', $selectedLinka)
            ->orderBy('den v týdnu', 'asc')
            ->orderBy('čas odjezdu', 'asc')
            ->get();

        $linky = DB::table('Linka')
        ->select('číslo linky as id')
        ->orderBy('číslo linky', 'asc')
        ->get();

        return view('spoje', ['spoje' => $spoje, 'linky' => $linky]);
    }

    public function deleteSpoj(Request $request)
    {
        DB::table('Spoj')->where('ID_spoj', $request->input('id'))->delete();

        session()->flash('success');
        return redirect()->route('spoje')->with(['success' => true, 'message' => 'Spoje úspěšně odstraněn!']);
    }

    public function editSpoj($id)
    {
        $spoj = DB::table('Spoj')
        ->select('ID_spoj', 'den v týdnu as den', 'čas odjezdu as cas')
        ->where('ID_spoj', $id)
        ->first();

        $cas = \Carbon\Carbon::parse($spoj->cas)->format('H:i');

        return view('spoj-edit', ['spoj' => $spoj, 'cas' => $cas]);
    }

    public function saveSpoj(Request $request)
    {
        try {
            $spojData = [
                'den v týdnu' => $request->input('Den'),
                'Čas odjezdu' => $request->input('cas'),
            ];

            DB::table('Spoj')->where('ID_spoj', $request->input('id'))->update($spojData);

            session()->flash('success');
            return redirect()->route('spoje')->with(['success' => true, 'message' => 'Spoj úspěšně upraven!']);
        }
        catch (\Exception $e) {
            session()->flash('error');
            return redirect()->route('spoje')->with(['success' => false, 'message' => 'Error Chyba při upravování spoje!']);
        }
        
    }
}
