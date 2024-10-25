<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DispecinkController extends Controller
{
    public function index()
    {
        $linky = DB::table('Linka')
        ->select('číslo linky as id')
        ->orderBy('číslo linky', 'asc')
        ->get();

        return view('dispecink', ['linky' => $linky]);
    }

    public function getDispecinkData(Request $request)
    {
        $selectedLinka = $request->input('linka');

        $data = $this->prepareDispecinkData($selectedLinka);

        return view('dispecink', $data);
    }

    public function addVozidlo(Request $request)
    {
        try {
            DB::table('Spoj')->where('ID_spoj', $request->input('spoj'))->update(['FK_vozidlo' => $request->input('spz')]);

            $data = $this->prepareDispecinkData($request->input('linka'));

            session()->flash('success', 'Vozidlo úspěšně přidáno!');
            return view('dispecink', $data)->with(['success' => true]);
        } catch (\Exception $e) {
            session()->flash('error', 'Error');
            return redirect()->route('dispecink')->with(['success' => false]);
        }
    }

    public function addRidic(Request $request)
    {
        try {
            DB::table('Spoj')->where('ID_spoj', $request->input('spoj'))->update(['FK_řidič' => $request->input('id')]);

            $data = $this->prepareDispecinkData($request->input('linka'));

            session()->flash('success', 'Řidič úspěšně přidán!');
            return view('dispecink', $data)->with(['success' => true]);
        } catch (\Exception $e) {
            session()->flash('error', 'Error');
            return redirect()->route('dispecink')->with(['success' => false]);
        }
    }

    private function prepareDispecinkData($selectedLinka)
    {
        $spoje = DB::table('Spoj')
        ->select('ID_spoj', 'čas odjezdu as cas_odjezdu', 'den v týdnu as den', 'FK_řidič', 'FK_vozidlo', 'users.name as jmeno')
        ->leftJoin('users', 'users.id', '=', 'Spoj.FK_řidič')
        ->where('FK_číslo_linky', $selectedLinka)
        ->orderBy('den v týdnu', 'asc')
        ->orderBy('čas odjezdu', 'asc')
        ->get();

        $linky = DB::table('Linka')
        ->select('číslo linky as id')
        ->orderBy('číslo linky', 'asc')
        ->get();

        $vozidla = DB::table('Vozidla')
        ->select('SPZ')
        ->orderBy('SPZ', 'asc')
        ->get();

        $users = DB::table('users')
        ->select('id', 'name')
        ->where('role', 'ridic')
        ->get();

        return ['spoje' => $spoje, 'linky' => $linky, 'vozidla' => $vozidla, 'users' => $users, 'selectedLinka' => $selectedLinka];
    }
}
