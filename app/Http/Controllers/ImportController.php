<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ImportedProductsImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        Excel::import(new ImportedProductsImport, $request->file('file'));

        return redirect()->back()->with('success', 'Products imported successfully!');
    }
}