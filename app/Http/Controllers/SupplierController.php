<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use PDF;
use Session;

class SupplierController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:admin,petugas');
    }

    public function index()
    {
        $code = Supplier::code();
        $supplier = Supplier::all();
        return view('supplier.index', compact('supplier', 'code'));
    }

    public function cetakSupplierPDF()
    {
        $data = Supplier::all();
        $no = 1;
        $pdf = PDF::loadview('supplier.cetaksupplier', compact('data', 'no'));
        return $pdf->download('Data-supplier.pdf');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required',
            'perusahaan' => 'required',
        ]);
        $supplier = new Supplier();
        $supplier->kode = $request->kode;
        $supplier->nama_supplier = $request->nama;
        $supplier->alamat = $request->alamat;
        $supplier->no_telp = $request->no_telp;
        $supplier->nama_perusahaan = $request->perusahaan;
        $supplier->save();

        return redirect('supplier')->with('success', 'Data Berhasil Disimpan!');
    }

    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('supplier.show', compact('supplier'));
    }

    public function edit($id)
    {
        $code = Supplier::code();
        $supplier = Supplier::findOrFail($id);
        return view('supplier.edit', compact('supplier', 'code'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required',
            'perusahaan' => 'required',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->nama_supplier = $request->nama;
        $supplier->alamat = $request->alamat;
        $supplier->no_telp = $request->no_telp;
        $supplier->nama_perusahaan = $request->perusahaan;
        $supplier->save();

        return redirect('supplier')->with('info', 'Data berhasil diubah!');
    }

    public function destroy($id)
    {
        if (!Supplier::destroy($id)) {
            return redirect()->back();
        }
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Data Berhasil Dihapus",
        ]);
        return redirect()->route('supplier');
    }
}
