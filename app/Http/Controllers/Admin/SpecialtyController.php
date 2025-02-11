<?php

namespace App\Http\Controllers\Admin;

use App\Models\Specialty;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SpecialtyController extends Controller
{


    public function index()
    {
        $specialties = Specialty::all();
        return view('specialties.index', compact('specialties'));
    }

    public function create()
    {
        return view ('specialties.create');
    }

    public function sendData(Request $request)
    {
        $rules =
        [
          'name' => 'required|min:4'
        ];

        $messages =
        [
            'name.required' => 'Este campo no debe estar vacio.',
            'name.min' => 'Debe contener al menos 4 caracteres.'
        ];

        $this->validate($request,$rules,$messages);

        $specialty = new Specialty();
        $specialty-> name = $request->input('name');
        $specialty-> description = $request->input('description');
        $specialty->save();

        $notification = 'Dato Creado con Éxito';

        return redirect('/especialidades')->with(compact('notification'));
    }

    public function edit(Specialty $specialty)
    {
        return view('specialties.edit', compact('specialty'));
    }

    public function update(Request $request, Specialty $specialty)
    {
        $rules =
        [
          'name' => 'required|min:4'
        ];

        $messages =
        [
            'name.required' => 'Este campo no debe estar vacio.',
            'name.min' => 'Debe contener al menos 4 caracteres.'
        ];

        $this->validate($request,$rules,$messages);

        $specialty-> name = $request->input('name');
        $specialty-> description = $request->input('description');
        $specialty->save();

        $notification = 'Dato Actualizado con Éxito';

        return redirect('/especialidades')->with(compact('notification'));
    }

    public function destroy(Specialty $specialty)
    {
        $deleteName = $specialty->name;
        $specialty->delete();
        $notification = 'El Dato'.$deleteName.'Eliminado con Éxito.';

        return redirect('/especialidades')->with(compact('notification'));
    }
}
