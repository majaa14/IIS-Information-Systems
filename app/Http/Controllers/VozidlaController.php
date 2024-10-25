<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VozidlaController extends Controller
{
    public function index()
    {
        $vozidla = DB::table('Vozidla')
        ->select('SPZ', 'Druh', 'nepojizdné')
        ->orderBy('SPZ', 'asc')
        ->get();

        $zavady = DB::table('Závada')
        ->select('FK_SPZ as SPZ', 'Datum závady as Datum', 'Popis závady as Popis')
        ->orderBy('Datum', 'desc')
        ->get();

        return view('vozidla', ['vozidla' => $vozidla, 'zavady' => $zavady]);
    }

    public function getVozidloData(Request $request)
    {
        $selectedVozidlo = $request->input('vozidlo');

        $vozidloData = DB::table('Vozidla')
            ->where('SPZ', $selectedVozidlo)
            ->first();

        $vozidla = DB::table('Vozidla')
        ->select('SPZ', 'Druh', 'nepojizdné')
        ->orderBy('SPZ', 'asc')
        ->get();

        $zavady = DB::table('Závada')
        ->select('FK_SPZ as SPZ', 'Datum závady as Datum', 'Popis závady as Popis')
        ->orderBy('Datum', 'desc')
        ->get();

        return view('vozidla', ['vozidloData' => $vozidloData, 'vozidla' => $vozidla, 'zavady' => $zavady]);
    }

    public function deleteVozidlo(Request $request)
    {
        DB::table('Vozidla')->where('SPZ', $request->input('spz'))->delete();

        session()->flash('success');
        return redirect()->route('vozidla')->with(['success' => true, 'message' => 'Vozidlo úspěšně odstraněno!']);
    }

    public function createPozadavek(Request $request)
    {
        try {
            $pozadavekData = [
                'Datum požadavku' => Carbon::now(),
                'Popis problému' => $request->input('popis'),
                'FK_SPZ' => $request->input('spz'),
            ];

            \DB::table('Požadavek na servis')->insert($pozadavekData);

            session()->flash('success');
            return redirect()->route('vozidla')->with(['success' => true, 'message' => 'Požadavek úspěšně přidán!']);
        } catch (\Exception $e) {
            session()->flash('error');
            return redirect()->route('vozidla')->with(['success' => false, 'message' => 'Error']);
        }
    }

    public function createPozadavekPravid(Request $request)
    {
        try {
            $pozadavekData = [
                'Datum požadavku' => Carbon::now(),
                'Popis problému' => 'Pravidelný servis',
                'FK_SPZ' => $request->input('spz'),
            ];

            \DB::table('Požadavek na servis')->insert($pozadavekData);

            session()->flash('success');
            return redirect()->route('vozidla')->with(['success' => true, 'message' => 'Pravidelný požadavek úspěšně přidán!']);
        } catch (\Exception $e) {
            session()->flash('error');
            return redirect()->route('vozidla')->with(['success' => false, 'message' => 'Error']);
        }
    }

    public function createVozidlo(Request $request)
    {
        try {
            try {
                $request->validate([
                    'spz' => ['required', 'string', 'size:7'],
                ]);
            } catch (\Exception $e) {
                session()->flash('error');
                return redirect()->route('vozidla')->with(['success' => false, 'message' => 'Error SPZ musí mít 7 znaků!']);
            }

            $vozidloData = [
                'SPZ' => $request->input('spz'),
                'Druh' => $request->input('druh'),
                'nepojizdné' => false,
            ];

            \DB::table('Vozidla')->insert($vozidloData);

            session()->flash('success');
            return redirect()->route('vozidla')->with(['success' => true, 'message' => 'Vozidlo úspěšně přidáno!']);
        } catch (\Exception $e) {
            session()->flash('error');
            return redirect()->route('vozidla')->with(['success' => false, 'message' => 'Error SPZ se shoduje s již existujícím vozidlem!']);
        }
    }
}
