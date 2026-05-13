<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\FisioterapeutaController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\CitasPublicasController;
use App\Http\Controllers\HistorialClinicoController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\Admin\PatientController   as AdminPatientController;
use App\Http\Controllers\Admin\DocumentController  as AdminDocumentController;
use App\Http\Controllers\Admin\InvoiceController   as AdminInvoiceController;
use App\Http\Controllers\Admin\DoctorNoteController as AdminNoteController;
use App\Http\Controllers\Admin\AttendanceController    as AdminAttendanceController;
use App\Http\Controllers\Admin\TherapyPlanController   as AdminTherapyPlanController;
use App\Http\Controllers\Admin\TherapySessionController as AdminTherapySessionController;
use App\Http\Controllers\Admin\TreatmentCalendarController as AdminCalendarController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Rutas públicas para agendamiento de appointments
Route::get('/agendar-appointment', [CitasPublicasController::class, 'create'])->name('appointments.publicas.create');
Route::post('/agendar-appointment', [CitasPublicasController::class, 'store'])->name('appointments.publicas.store');
Route::get('/api/physiotherapists/{especialidadId}', [CitasPublicasController::class, 'obtenerFisioterapeutas'])->name('api.physiotherapists');
Route::get('/api/physiotherapists/{id}/availability', [\App\Http\Controllers\HorarioController::class, 'checkAvailability'])->name('api.availability');
// Resource routes
Route::resource('patients', PacienteController::class);
Route::resource('physiotherapists', FisioterapeutaController::class);
Route::resource('specialties', EspecialidadController::class);
Route::resource('appointments', CitaController::class);
Route::resource('histories', HistorialClinicoController::class);
Route::resource('roles', RolController::class);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para Physiotherapists
Route::middleware('role:physiotherapist,doctor')->group(function () {

    Route::get('/doctor/appointments-hoy', [FisioterapeutaController::class, 'citasHoy'])
        ->name('doctor.appointments-hoy');

    Route::get('/doctor/mis-appointments', [FisioterapeutaController::class, 'misCitas'])
        ->name('doctor.mis-appointments');

    Route::get('/doctor/mis-patients', [FisioterapeutaController::class, 'misPacientes'])
        ->name('doctor.mis-patients');

    Route::get('/doctor/mi-schedule', [HorarioController::class, 'miHorario'])
        ->name('doctor.mi-schedule');

    Route::post('/doctor/mi-schedule/actualizar', [HorarioController::class, 'actualizarMiHorario'])
        ->name('doctor.actualizar-mi-schedule');

    Route::post('/doctor/appointment/{id}/confirmar', [FisioterapeutaController::class, 'confirmarCita'])
        ->name('doctor.confirmar-appointment');

    Route::post('/doctor/appointment/{id}/agregar-nota', [FisioterapeutaController::class, 'agregarNota'])
        ->name('doctor.agregar-nota');

    // Clinical Profile & Prescriptions
    Route::get('/doctor/patients/{id}', [FisioterapeutaController::class, 'verPaciente'])
        ->name('doctor.patients.show');
    Route::post('/doctor/patients/{id}/prescription', [FisioterapeutaController::class, 'storePrescription'])
        ->name('doctor.patients.store-prescription');
    Route::get('/doctor/prescriptions/{id}/print', [FisioterapeutaController::class, 'printPrescription'])
        ->name('doctor.patients.print-prescription');

});


    // Shared routes for Admins and Doctors (Appointment Management)
    Route::middleware('role:admin,doctor,physiotherapist')->group(function () {
        Route::post('/shared/appointments/{id}/cancelar', [CitaController::class, 'cancelarCitaAdmin'])->name('shared.appointments.cancelar');
        Route::delete('/shared/appointments/{id}', [CitaController::class, 'destroyCitaAdmin'])->name('shared.appointments.destroy');
        Route::get('/shared/appointments/{id}/edit', [CitaController::class, 'editCitaAdmin'])->name('shared.appointments.edit');
        Route::put('/shared/appointments/{id}', [CitaController::class, 'updateCitaAdmin'])->name('shared.appointments.update');
    });

    // Rutas para Administradores
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/users', [UsuarioController::class, 'indexAdmin'])->name('admin.users.index');

    // Treatment Calendar (moved to shared middleware below)
        Route::get('/admin/users/crear', [UsuarioController::class, 'createAdmin'])->name('admin.users.create');
        Route::post('/admin/users', [UsuarioController::class, 'storeAdmin'])->name('admin.users.store');
        Route::get('/admin/users/{id}/editar', [UsuarioController::class, 'editAdmin'])->name('admin.users.edit');
        Route::patch('/admin/users/{id}', [UsuarioController::class, 'updateAdmin'])->name('admin.users.update');
        Route::delete('/admin/users/{id}', [UsuarioController::class, 'destroyAdmin'])->name('admin.users.destroy');

        Route::get('/admin/doctors', [FisioterapeutaController::class, 'indexAdmin'])->name('admin.doctors.index');
        Route::get('/admin/doctors/crear', [FisioterapeutaController::class, 'createAdmin'])->name('admin.doctors.create');
        Route::post('/admin/doctors', [FisioterapeutaController::class, 'storeAdmin'])->name('admin.doctors.store');
        Route::get('/admin/doctors/{id}/editar', [FisioterapeutaController::class, 'editAdmin'])->name('admin.doctors.edit');
        Route::patch('/admin/doctors/{id}', [FisioterapeutaController::class, 'updateAdmin'])->name('admin.doctors.update');
        Route::delete('/admin/doctors/{id}', [FisioterapeutaController::class, 'destroyAdmin'])->name('admin.doctors.destroy');

        Route::get('/admin/specialties', [EspecialidadController::class, 'indexAdmin'])->name('admin.specialties.index');
        Route::get('/admin/specialties/crear', [EspecialidadController::class, 'createAdmin'])->name('admin.specialties.create');
        Route::post('/admin/specialties', [EspecialidadController::class, 'storeAdmin'])->name('admin.specialties.store');
        Route::get('/admin/specialties/{id}/editar', [EspecialidadController::class, 'editAdmin'])->name('admin.specialties.edit');
        Route::patch('/admin/specialties/{id}', [EspecialidadController::class, 'updateAdmin'])->name('admin.specialties.update');
        Route::delete('/admin/specialties/{id}', [EspecialidadController::class, 'destroyAdmin'])->name('admin.specialties.destroy');

        Route::get('/admin/appointments', [CitaController::class, 'indexAdmin'])->name('admin.appointments.index');
        Route::get('/admin/appointments/create', [CitaController::class, 'createAdmin'])->name('admin.appointments.create');
        Route::post('/admin/appointments', [CitaController::class, 'storeAdmin'])->name('admin.appointments.store');
        Route::post('/admin/appointments/{id}/confirmar', [CitaController::class, 'confirmarCitaAdmin'])->name('admin.appointments.confirmar');
        
        // Patients and Documents moved to shared middleware below

        // Invoices
        Route::get('/admin/invoices', [AdminInvoiceController::class, 'index'])->name('admin.invoices.index');
        Route::get('/admin/invoices/create', [AdminInvoiceController::class, 'create'])->name('admin.invoices.create');
        Route::post('/admin/invoices', [AdminInvoiceController::class, 'store'])->name('admin.invoices.store');
        Route::get('/admin/invoices/{invoice}', [AdminInvoiceController::class, 'show'])->name('admin.invoices.show');
        Route::post('/admin/invoices/{invoice}/pay', [AdminInvoiceController::class, 'markPaid'])->name('admin.invoices.pay');
        Route::delete('/admin/invoices/{invoice}', [AdminInvoiceController::class, 'destroy'])->name('admin.invoices.destroy');

    });

    // Rutas compartidas entre Admin y Doctor (Patients, Therapy, Calendar)
    Route::middleware('role:admin,doctor,physiotherapist')->group(function () {
        // Treatment Calendar (global)
        Route::get('/admin/treatment-calendar', [AdminCalendarController::class, 'index'])
            ->name('admin.treatment-calendar');

        // --- NEW Admin Modules ---
        Route::resource('/admin/patients', AdminPatientController::class)->names([
            'index'   => 'admin.patients.index',
            'create'  => 'admin.patients.create',
            'store'   => 'admin.patients.store',
            'show'    => 'admin.patients.show',
            'edit'    => 'admin.patients.edit',
            'update'  => 'admin.patients.update',
            'destroy' => 'admin.patients.destroy',
        ]);

        // Documents
        Route::post('/admin/documents', [AdminDocumentController::class, 'store'])->name('admin.documents.store');
        Route::get('/admin/documents/{document}/download', [AdminDocumentController::class, 'download'])->name('admin.documents.download');
        Route::delete('/admin/documents/{document}', [AdminDocumentController::class, 'destroy'])->name('admin.documents.destroy');

        // Doctor Notes
        Route::post('/admin/notes', [AdminNoteController::class, 'store'])->name('admin.notes.store');
        Route::put('/admin/notes/{note}', [AdminNoteController::class, 'update'])->name('admin.notes.update');
        Route::delete('/admin/notes/{note}', [AdminNoteController::class, 'destroy'])->name('admin.notes.destroy');

        // Attendance (legacy)
        Route::post('/admin/patients/{patient}/attendance', [AdminAttendanceController::class, 'mark'])->name('admin.attendance.mark');
        Route::delete('/admin/attendance/{attendance}', [AdminAttendanceController::class, 'undo'])->name('admin.attendance.undo');

        // ── Therapy Plans ─────────────────────────────────────────────────────
        Route::get('/admin/patients/{patient}/therapy', [AdminTherapyPlanController::class, 'index'])
            ->name('admin.therapy.index');
        Route::get('/admin/patients/{patient}/therapy/create', [AdminTherapyPlanController::class, 'create'])
            ->name('admin.therapy.create');
        Route::post('/admin/patients/{patient}/therapy', [AdminTherapyPlanController::class, 'store'])
            ->name('admin.therapy.store');
        Route::get('/admin/therapy-plans/{plan}', [AdminTherapyPlanController::class, 'show'])
            ->name('admin.therapy.show');
        Route::delete('/admin/therapy-plans/{plan}', [AdminTherapyPlanController::class, 'destroy'])
            ->name('admin.therapy.destroy');

        // ── Therapy Sessions ──────────────────────────────────────────────────
        Route::patch('/admin/therapy-sessions/{session}/status', [AdminTherapySessionController::class, 'update'])
            ->name('admin.therapy.session.update');
        Route::patch('/admin/therapy-sessions/{session}/revert', [AdminTherapySessionController::class, 'revert'])
            ->name('admin.therapy.session.revert');
    });
});

require __DIR__.'/auth.php';
