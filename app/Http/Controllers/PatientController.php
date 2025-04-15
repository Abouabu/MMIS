<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with('assignedDoctor')->get(); // Eager load the assigned doctor
        $doctorsByStatus = Doctor::orderBy('name')
            ->get()
            ->groupBy('status');

        return view('patients.index', compact('patients', 'doctorsByStatus'));
    }

    public function assignDoctor(Request $request, Patient $patient)
    {
        Log::info('Assign Doctor Request - Patient ID: ' . $patient->id);
        Log::info('Assign Doctor Request - Doctor ID: ' . $request->input('doctor_id'));
    
        $doctorId = $request->input('doctor_id');
        Log::info('Attempting to find doctor with ID: ' . $doctorId);
        $doctor = Doctor::find($doctorId);
    
        if ($doctor) {
            Log::info('Doctor found: ' . $doctor->name);
            Log::info('Attempting to assign doctor ID ' . $doctorId . ' to patient ID ' . $patient->id);
            $patient->doctor_assigned_id = $doctorId;
            $patient->save();
            $doctor->status = 'Busy';
            $doctor->save();
            Log::info('Assignment successful.');
            return response()->json(['message' => 'Doctor assigned successfully', 'doctor_name' => $doctor->name]);
        } else {
            Log::error('Doctor not found with ID: ' . $doctorId);
            return response()->json(['error' => 'Doctor not found'], 404);
        }
    }
    // public function index(Request $request)
    // {
    //     $patients = Patient::all();
    //     return view('patients.index', compact('patients'));
    // }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
        {
            // Validate the incoming request data
            $validator = $request->validate([
                'name' => 'required|string|max:255',
                'DOB' => 'nullable|date',
                'gender' => 'nullable|string|max:1',
                'contact_number' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                // You might add validation for 'assigned_doctor_id' if you want to set it during creation
            ]);
    
            if (!$validator) {
                return redirect()->route('patients.create')
                                 ->withErrors($validator)
                                 ->withInput();
            }

            $patient = Patient::create($validator);
            $patient->save();
    
            // Redirect the user to a success page or the patient list
            return redirect()->route('patients.index')->with('success', 'Patient created successfully!');
        }

        public function edit(Patient $patient)
        {
            return view('patients.edit', compact('patient'));
        }

        public function update(Request $request, Patient $patient)
        {
            $validator = $request->validate([
                'name' => 'required|string|max:255',
                'DOB' => 'nullable|date',
                'gender' => 'nullable|string|max:1',
                'contact_number' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
            ]);
            if(!$validator) {
                return redirect()->route('patients.edit', $patient->id)
                             ->withErrors($validator)
                             ->withInput();
            }
            $patient->update($request->all());

            return redirect()->route('patients.index')->with('success', 'Patient updated successfully');
        }

        public function destroy(Patient $patient)
        {
            $patient->delete();
            return redirect()->route('patients.index')->with('success', 'Patient updated successfully');
        }

    }

