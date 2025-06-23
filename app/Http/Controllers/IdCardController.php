<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use PDF;

class IdCardController extends Controller
{
    public function show(Employee $employee)
    {
        return view('id-cards.show', compact('employee'));
    }

    public function download(Employee $employee)
    {
        $pdf = PDF::loadView('id-cards.pdf', compact('employee'));
        return $pdf->download($employee->full_name . '_id_card.pdf');
    }
}
