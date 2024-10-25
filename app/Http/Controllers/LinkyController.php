<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LinkyController extends Controller
{
    public function index()
    {
        $zastavky = DB::table('Zastávka')
        ->select(['název zastávky as nazev', 'ID_zastávky'])
        ->orderBy('nazev', 'asc')
        ->get();

        $linky = DB::table('Linka')
        ->select('číslo linky as id')
        ->orderBy('číslo linky', 'asc')
        ->get();

        return view('linky', ['zastavky' => $zastavky, 'linky' => $linky]);
    }

    public function createLinka(Request $request)
    {
        try {
            $linka = DB::table('Linka')
            ->select('číslo linky')
            ->where('číslo linky', '=', $request->input('cislo'))
            ->first();

            if ($linka != null) {
                session()->flash('error', 'Error linka se stejným číslem již existuje!');
                return redirect()->route('linka.create')->with(['success' => false]);
            }

            $linkaData = [
                'číslo linky' => $request->input('cislo'),
                'FK_ID_hraniční_zastávka_A' => $request->input('start'),
                'FK_ID_hraniční_zastávka_B' => $request->input('end'),
            ];

            \DB::table('Linka')->insert($linkaData);

            session()->flash('success', 'Linka úspěšně přidána!');
            return redirect()->route('linka.create')->with(['success' => true]);
        } 
        catch (\Exception $e) {
            session()->flash('error', 'Error');
            return redirect()->route('linka.create')->with(['success' => false]);
        }
    }

    public function getLinkyData(Request $request)
    {
        $data = $this->prepareLinkyData($request->input('linka'));

        return view('linky', $data);
    }

    public function deleteLinka(Request $request)
    {
        try {
            $linka = DB::table('Linka')
            ->where('číslo linky', '=', $request->input('id'))
            ->delete();

            session()->flash('success', 'Linka úspěšně odstraněna!');
            return redirect()->route('linky')->with(['success' => true]);
        } 
        catch (\Exception $e) {
            session()->flash('error', 'Error');
            return redirect()->route('linky')->with(['success' => false]);
        }
    }

    public function deleteZastavka(Request $request)
    {
        DB::table('Zastávka na lince')
        ->where('FK_číslo_linky', '=', $request->input('linka'))
        ->where('pořadí zastávky', '=', $request->input('poradi'))
        ->delete();

        $data = $this->prepareLinkyData($request->input('linka'));

        session()->flash('success', 'Zastávka úspěšně odstraněna!');
        return view('linky', $data)->with(['success' => true]);
    }

    public function addZastavka(Request $request)
    {
        try {
            $highestPoradi = DB::table('Zastávka na lince')
            ->where('FK_číslo_linky', '=', $request->input('linka'))
            ->max('pořadí zastávky');

            if ($highestPoradi + 1 < $request->input('poradi')) {
                session()->flash('error', 'Error pořadí zastávky musí být maximálně o 1 vyšší než nejvyšší pořadí zastávky na lince!');
                return redirect()->route('linky')->with(['success' => false]);
            }

            $zastavkaData = [
                'FK_číslo_linky' => $request->input('linka'),
                'FK_číslo_zastávky' => $request->input('zastavka'),
                'pořadí zastávky' => $request->input('poradi'),
            ];

            DB::table('Zastávka na lince')->insert($zastavkaData);

            $data = $this->prepareLinkyData($request->input('linka'));

            session()->flash('success', 'Zastávka úspěšně přidána!');
            return view('linky', $data)->with(['success' => true]);
        }
        catch (\Exception $e) {
            session()->flash('error', 'Error Chyba při přidávání zastávky!' . $e->getMessage());
            return redirect()->route('linky')->with(['success' => false]);
        }
    }

    private function prepareLinkyData($selectedLinka)
    {
        $linkaItem = DB::table('Linka')
        ->select('číslo linky as cislo', 'Zastávka_počáteční.název zastávky as start', 'Zastávka_konečná.název zastávky as end')
        ->join('Zastávka as Zastávka_počáteční', 'Zastávka_počáteční.ID_zastávky', '=', 'Linka.FK_ID_hraniční_zastávka_A')
        ->join('Zastávka as Zastávka_konečná', 'Zastávka_konečná.ID_zastávky', '=', 'Linka.FK_ID_hraniční_zastávka_B')
        ->where('číslo linky', '=', $selectedLinka)
        ->orderBY('číslo linky', 'asc')
        ->first();

        $linky = DB::table('Linka')
        ->select('číslo linky as id')
        ->orderBy('číslo linky', 'asc')
        ->get();

        $zastavky = DB::table('Zastávka')
        ->select(['název zastávky as nazev', 'ID_zastávky'])
        ->orderBy('ID_zastávky', 'asc')
        ->get();

        $zastavkyLinky = DB::table('Zastávka na lince')
        ->select('pořadí zastávky as poradi', 'Zastávka.název zastávky as nazev')
        ->join('Zastávka', 'Zastávka.ID_zastávky', '=', 'Zastávka na lince.FK_číslo_zastávky')
        ->where('FK_číslo_linky', '=', $selectedLinka)
        ->orderBy('pořadí zastávky', 'asc')
        ->get();

        return ['linkaItem' => $linkaItem, 'linky' => $linky, 'zastavky' => $zastavky, 'zastavkyLinky' => $zastavkyLinky];
    }
}