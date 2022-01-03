<?php

namespace App\Http\Controllers;

use App\Models\Barang_keluar;
use App\Models\Supplier;
use App\Models\Barang;
use Illuminate\Http\Request;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supplier = Supplier::all();
        $barang = Barang::all();
        $keluar = Barang_keluar::all();
        return view('barang-keluar.index', compact('keluar', 'supplier', 'barang'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        
        
     }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

            $request->validate([
                
                'id_barang' => 'required',
                'jumlah' => 'required',
                'tgl_pengiriman' => 'required',
                'tujuan' => 'required',
            ]);
        
             Barang_keluar::create($request->all());
            
             $barang = Barang::where($request->id_barang)->get()->value('jumlah_barang');
             $barang->jumlah_barang -= $request->jumlah;
             $barang->save();
        
            Session::flash("flash_notification", [
                "level" => "success",
                "message" => "Data berhasil disimpan",
            ]);
            return redirect()->route('barang-keluar.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\barang_keluar  $barang_keluar
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $barangKeluar = Barang_keluar::findOrFail($id);
        return view('barang-keluar.show', compact('barangKeluar'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\barang_keluar  $barang_keluar
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $barang = Barang::all();
        $barangKeluar = Barang_keluar::findOrFail($id);
        return view('barang-keluar.edit', compact('barang', 'barangKeluar'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\barang_keluar  $barang_keluar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
                
                'id_barang' => 'required',
                'jumlah' => 'required',
                'tgl_pengiriman' => 'required',
                'tujuan' => 'required',
            ]);
        $barangKeluar = Barang_keluar::findOrFail($id);
        $barangKeluar->update($request->all());
        
        $barang = Barang::findOrFail($request->id_barang);
        $barang->jumlah_barang -= $request->jumlah;
        $barang->save();
        Session::flash("flash_notification", [
                "level" => "success",
                "message" => "Data berhasil diedit",
            ]);
            return redirect()->route('barang-keluar.edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\barang_keluar  $barang_keluar
     * @return \Illuminate\Http\Response
     */
    public function destroy(barang_keluar $barang_keluar)
    {
        //
    }
}
