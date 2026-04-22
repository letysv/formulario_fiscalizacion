<?php

// namespace App\Http\Controllers;
namespace App\Http\Controllers;

use App\Models\Registro;
use App\Models\Sede;
use App\Models\Ayuntamiento;
use App\Models\Cargo;
use Illuminate\Http\Request;
use App\Exports\RegistrosExport;
use Maatwebsite\Excel\Facades\Excel;

class RegistroController extends Controller
{
    public function index()
    {
        $sedes = Sede::all();
        $cargos = Cargo::all();
        return view('registros.formulario', compact('sedes', 'cargos'));
    }
    
    public function getAyuntamientos($sede_id)
    {
        $ayuntamientos = Ayuntamiento::where('sede_id', $sede_id)->get();
        return response()->json($ayuntamientos);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'sede_id' => 'required|exists:sedes,id',
            'ayuntamiento_id' => 'required|exists:ayuntamientos,id',
            'cargo_id' => 'required|exists:cargos,id',
            'nombre_completo' => 'required|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'telefono' => 'required|string|size:10|regex:/^[0-9]+$/',
            'telefono_oficina' => 'nullable|string|size:10|regex:/^[0-9]+$/',
            'correo_personal' => [
                'required',
                'email',
                'max:255',
                'unique:registros,correo_personal',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],
            'correo_institucional' => [
                'required',
                'email',
                'max:255',
                'unique:registros,correo_institucional',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],
        ], [
            'nombre_completo.regex' => 'El nombre completo solo puede contener letras y espacios',
            'telefono.required' => 'El teléfono celular es obligatorio',
            'telefono.size' => 'El teléfono celular debe tener exactamente 10 dígitos',
            'telefono.regex' => 'El teléfono celular solo debe contener números',
            'telefono_oficina.size' => 'El teléfono de oficina debe tener exactamente 10 dígitos',
            'telefono_oficina.regex' => 'El teléfono de oficina solo debe contener números',
            'correo_personal.email' => 'El correo personal debe ser una dirección de email válida',
            'correo_personal.regex' => 'El correo personal debe tener un formato válido (ejemplo@dominio.com)',
            'correo_personal.unique' => 'Este correo personal ya está registrado',
            'correo_institucional.email' => 'El correo institucional debe ser una dirección de email válida',
            'correo_institucional.regex' => 'El correo institucional debe tener un formato válido (ejemplo@dominio.com)',
            'correo_institucional.unique' => 'Este correo institucional ya está registrado',
        ]);
    
        Registro::create($request->all());

        return redirect()->route('registros.index')->with('success', 'Registro guardado exitosamente');
    }
    
    public function exportarExcel(Request $request)
    {
        $sede_id = $request->get('sede_id');
        
        $export = new RegistrosExport($sede_id);
        return Excel::download($export, 'reporte_fiscalizacion_' . date('Y-m-d') . '.xlsx');
    }
    
    public function reporte()
    {
        $sedes = Sede::all();
        return view('registros.reporte', compact('sedes'));
    }
}