<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UzivateleController extends Controller
{
    public function index()
    {
        $uzivatele = DB::table('users')
        ->select('id', 'name', 'email', 'created_at', 'updated_at', 'role')
        ->orderBy('id', 'asc')
        ->get();

        return view('uzivatele', ['uzivatele' => $uzivatele]);
    }

    public function deleteUzivatel(Request $request)
    {
        DB::table('users')->where('id', $request->input('id'))->delete();

        session()->flash('success');
        return redirect()->route('uzivatele')->with(['success' => true, 'message' => 'Uživatel úspěšně odstraněn!']);
    }

    public function editUzivatel($id)
    {
        $uzivatel = DB::table('users')
        ->select('id', 'name', 'email', 'role')
        ->where('id', $id)
        ->first();

        return view('uzivatel-edit', ['uzivatel' => $uzivatel]);
    }

    public function saveUzivatel(Request $request)
    {
        try {
            try{
                $validator = \Validator::make($request->all(), [
                    'email' => 'required|email',
                ]);
            } catch (\Exception $e) {
                session()->flash('error');
                return redirect()->route('uzivatele')->with(['success' => false, 'message' => 'Error Špatný formát emailu!']);
            }

            $uzivatelData = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'role' => $request->input('role'),
            ];

            DB::table('users')->where('id', $request->input('id'))->update($uzivatelData);

            session()->flash('success');
            return redirect()->route('uzivatele')->with(['success' => true, 'message' => 'Uživatel úspěšně upraven!']);
        }
        catch (\Exception $e) {
            session()->flash('error');
            return redirect()->route('uzivatele')->with(['success' => false, 'message' => 'Error Chyba při upravování uživatele!']);
        }
    }
}
