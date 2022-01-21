<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Exports\CustomerExport;
use Illuminate\Http\Request;
use PDF;
use Excel;
use Session;

class CustomerController extends Controller
{

    public function index()
    {
        $customer = Customer::all();
        $code = Customer::code();
        return view('customer.index', compact('customer', 'code'));
    }

    public function cetakCustomerPDF()
    {
        $data = Customer::all();
        $no = 1;
        $pdf = PDF::loadview('customer.cetakcustomer', compact('data', 'no'));
        return $pdf->download('Data-customer.pdf');
    }

    public function cetakCustomerExcel()
    {
        return Excel::download(new CustomerExport, 'Data customer.xlsx');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
            'email' => 'required|email',
            'no_telp' => 'required'
        ]);

        $customer = new Customer;
        $customer->kode = $request->kode;
        $customer->nama = $request->nama;
        $customer->alamat = $request->alamat;
        $customer->email = $request->email;
        $customer->telepon = $request->no_telp;
        $customer->save();

        return redirect('customer')->withSuccess('Data berhasil disimpan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'email' => 'required|email',
            'no_telp' => 'required'
        ]);

        $customer = Customer::findOrfail($id);
        $customer->nama = $request->nama;
        $customer->alamat = $request->alamat;
        $customer->email = $request->email;
        $customer->telepon = $request->no_telp;
        $customer->update();

        return redirect('customer')->withInfo('Data berhasil diubah!');
    }

    public function destroy($id)
    {
        if (!Customer::destroy($id)) {
            return redirect()->back();
        }
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Data Berhasil Dihapus",
        ]);
        return redirect('customer')->withSuccess('Data telah dihapus');
    }
}
