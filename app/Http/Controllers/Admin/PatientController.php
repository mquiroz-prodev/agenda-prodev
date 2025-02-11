<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;

class PatientController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patients = User::patients()->paginate(15);
        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('patients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|min:4',
            'email' => 'required|email',
            'identity_card' => 'required',
            'address' => 'nullable|min:10',
            'phone' => 'required',
        ];
        $messages = [
            'name.required' =>  'El nombre es obligatorio!',
            'name.min' =>  'El nombre debe poseer mas de 4 caracteres',
            'email.required' =>  'El correo electronico es obligatorio!',
            'email.email' =>  'Por favor ingresar una direccion de correo valido',
            'identity_card.required' =>  'La cedula es obligatoria',
            'address.min' =>  'La direccion debe tener al menos 10 digitos!',
            'phone.required' =>  'El numero de telefono es obligatorio',
        ];
        $this->validate($request, $rules, $messages);

        User::create(
            $request->only('name','email','identity_card','address','phone')
            +[
                'role' => 'paciente',
                'password' => bcrypt($request->input('password'))
            ]
        );
        $notification = "El paciente se registró correctamente.";
        return redirect('/pacientes')->with(compact('notification'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $patient = User::patients()->findOrFail($id);
        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|min:4',
            'email' => 'required|email',
            'identity_card' => 'required',
            'address' => 'nullable|min:10',
            'phone' => 'required',
        ];
        $messages = [
            'name.required' =>  'El nombre es obligatorio!',
            'name.min' =>  'El nombre debe poseer mas de 4 caracteres',
            'email.required' =>  'El correo electronico es obligatorio!',
            'email.email' =>  'Por favor ingresar una direccion de correo valido',
            'identity_card.required' =>  'La cedula es obligatoria',
            'address.min' =>  'La direccion debe tener al menos 10 digitos!',
            'phone.required' =>  'El numero de telefono es obligatorio',
        ];
        $this->validate($request, $rules, $messages);
        $user = User::patients()->findorFail($id);
        $data =  $request->only('name','email','identity_card','address','phone');
        $password = $request->input('password');

        if($password)
        $data['password'] = bcrypt($password);

        $user->fill($data);
        $user->save();

        $Nombrepaciente = $user->name;
        $notification = "El paciente $Nombrepaciente se registró correctamente.";
        return redirect('/pacientes')->with(compact('notification'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::patients()->findOrFail($id);
        $Nombrepaciente = $user->name;
        $user->delete();

        $notification = "El medico $Nombrepaciente se elimino correctamente";
        return redirect('/pacientes')->with(compact('notification'));
    }
}
