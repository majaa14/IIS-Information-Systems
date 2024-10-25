<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ZavadyController extends Controller
{
    public function index()
    {
        $vozidla = DB::table('vozidla')
        ->select('SPZ')
        ->orderBy('SPZ', 'asc')
        ->get();

        return view('zavady', ['vozidla' => $vozidla]);
    }

    public function createZavada(Request $request)
    {
        try {
            try {
                $request->validate([
                    'popis' => ['required', 'string', 'max:200'],
                ]);
            } 
            catch (\Exception $e) {
                session()->flash('error');
                return redirect()->route('zavada.create')->with(['success' => false, 'message' => 'Error Moc dlohý popis závady!']);
            }
            $inputDate = Carbon::parse($request->input('datum'));
            $currentDate = Carbon::now();
            if ($inputDate->isFuture()) {
                session()->flash('error');
                return redirect()->route('zavada.create')->with(['success' => false, 'message' => 'Error Datum nemůže být v budoucnosti!',]);
            }

            $zavadaData = [
                'Datum závady' => $request->input('datum'),
                'FK_SPZ' => $request->input('spz'),
                'Popis závady' => $request->input('popis'),
            ];

            \DB::table('Závada')->insert($zavadaData);

            session()->flash('success');
            return redirect()->route('zavada.create')->with(['success' => true, 'message' => 'Závada úspěšně přidána!']);
        } 
        catch (\Exception $e) {
            session()->flash('error');
            return redirect()->route('zavada.create')->with(['success' => false, 'message' => 'Error SPZ se neshoduje s existujícím vozidlem!']);
        }
    }
}
