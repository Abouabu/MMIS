<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Patient List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #doctors-sidebar {
            width: 250px;
            background-color: #f8f9fa;
            padding: 15px;
            border-right: 1px solid #ddd;
            height: 100vh; /* Adjust as needed */
            overflow-y: auto;
            position: fixed;
            top: 0;
            left: 0;
        }

        #patients-list {
            margin-left: 270px; /* Adjust margin based on sidebar width */
            padding: 15px;
        }

        .doctor-group {
            margin-bottom: 15px;
        }

        .doctor-item {
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 8px;
            margin-bottom: 5px;
            cursor: grab;
        }

        .doctor-item small {
            color: #777;
        }
        .assign-doctor-area {
            border: 1px dashed #aaa;
            padding: 10px;
            min-height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .assign-doctor-area.drag-over {
            background-color: #e9ecef;
        }
        .button-container {
            display: flex;
            justify-content: space-between; 
            align-items: center; 
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div id="doctors-sidebar">
                <h2>Available Doctors</h2>
                @foreach ($doctorsByStatus as $status => $doctors)
                    <div class="doctor-group">
                        <h5>{{ $status }}</h5>
                        @forelse ($doctors as $doctor)
                            <div class="doctor-item" draggable="true" data-doctor-id="{{ $doctor->id }}">
                                {{ $doctor->name }}
                                <small>({{ $doctor->specialization ?? 'N/A' }})</small>
                            </div>
                        @empty
                            <p>No doctors with status: {{ $status }}</p>
                        @endforelse
                    </div>
                @endforeach
            </div>

            <div id="patients-list">
                <h1>Patient List</h1>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="table table-striped">
                    <thead>
                        <tr>
                            {{-- <th>ID</th> --}}
                            <th>Name</th>
                            <th>Date of Birth</th>
                            <th>Gender</th>
                            <th>Contact Number</th>
                            <th>Email</th>
                            <th>Assigned Doctor</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($patients as $patient)
                            <tr>
                                {{-- <td>{{ $patient->id }}</td> --}}
                                <td>{{ $patient->name }}</td>
                                <td>{{ $patient->DOB ? $patient->DOB->format('Y-m-d') : '-' }}</td>
                                <td>{{ $patient->gender ?? '-' }}</td>
                                <td>{{ $patient->contact_number ?? '-' }}</td>
                                <td>{{ $patient->email ?? '-' }}</td>
                                <td class="assign-doctor-area" data-patient-id="{{ $patient->id }}">
                                    @if ($patient->assignedDoctor)
                                        {{ $patient->assignedDoctor->name }}
                                    @else
                                        Drag Doctor Here
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">No patients found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="button-container">

                <a href="{{ route('patients.create') }}" class="btn btn-success">Add New Patient</a>
                <div>
                    <form action="{{ route('receptionist.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>

            </div>
        </div>
    </div>

    <script>
        const doctorItems = document.querySelectorAll('.doctor-item');
        const assignAreas = document.querySelectorAll('.assign-doctor-area');
        let draggedDoctorId = null;

        doctorItems.forEach(item => {
            item.addEventListener('dragstart', (event) => {
                draggedDoctorId = event.target.dataset.doctorId;
                event.dataTransfer.setData("text/plain", draggedDoctorId); // Required for Firefox
            });
        });

        assignAreas.forEach(area => {
            area.addEventListener('dragover', (event) => {
                event.preventDefault();
                area.classList.add('drag-over');
            });

            area.addEventListener('dragleave', () => {
                area.classList.remove('drag-over');
            });

            area.addEventListener('drop', async (event) => {
                event.preventDefault();
                area.classList.remove('drag-over');
                const patientId = area.dataset.patientId;
                const doctorId = event.dataTransfer.getData("text/plain");

                if (doctorId && patientId) {
                    try {
                        const response = await fetch(`/patients/${patientId}/assign-doctor`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({ doctor_id: doctorId }),
                        });

                        if (response.ok) {
                            const data = await response.json();
                            area.textContent = data.doctor_name;
                            // Optionally update the assignedDoctor in the patient list row
                            const patientRow = area.closest('tr');
                            if (patientRow) {
                                const assignedDoctorCell = patientRow.querySelector('.assign-doctor-area');
                                if (assignedDoctorCell) {
                                    assignedDoctorCell.textContent = data.doctor_name;
                                }
                            }
                        } else {
                            console.error('Failed to assign doctor');
                        }
                    } catch (error) {
                        console.error('Error assigning doctor:', error);
                    }
                }
                draggedDoctorId = null;
            });
        });
    </script>
</body>
</html>