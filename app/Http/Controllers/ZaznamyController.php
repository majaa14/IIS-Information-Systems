<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ZaznamyController extends Controller
{
    public function index()
    {
        $pozadavky = DB::table('Požadavek na servis')
        ->select('ID_požadavek', 'FK_SPZ', 'Datum požadavku as Datum', 'Popis problému as Popis')
        ->orderBy('Datum', 'desc')
        ->get();

        return view('zaznamy', ['pozadavky' => $pozadavky]);
    }

    public function createZaznam(Request $request)
    {
        try {
            $inputDate = Carbon::parse($request->input('datum'));
            $currentDate = Carbon::now();
            if ($inputDate->isFuture()) {
                session()->flash('error');
                return redirect()->route('zavada.create')->with(['success' => false, 'message' => 'Error Datum nemůže být v budoucnosti!',]);
            }

            $SPZ = DB::table('Požadavek na servis')
            ->select('FK_SPZ')
            ->where('ID_požadavek', '=', $request->input('id'))
            ->first();

            $zaznamData = [
                'FK_ID_požadavek' => $request->input('id'),
                'FK_SPZ' => $SPZ->FK_SPZ,
                'Datum údržby' => $request->input('datum'),
                'Typ údržby' => $request->input('popis'),
            ];

            \DB::table('Záznam o údržbě')->insert($zaznamData);

            session()->flash('success');
            return redirect()->route('zaznam.create')->with(['success' => true, 'message' => 'Záznam úspěšně přidán!']);
        } 
        catch (\Exception $e) {
            session()->flash('error');
            return redirect()->route('zaznam.create')->with(['success' => false, 'message' => 'Error']);
        }
    }
}
