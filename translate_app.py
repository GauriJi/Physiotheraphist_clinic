import os
import re

# Dictionary mapping Spanish phrases to English keys
translations = {
    # Common words
    "Guardar": "save",
    "Editar": "edit",
    "Eliminar": "delete",
    "Cancelar": "cancel",
    "Atrás": "back",
    "Volver": "back",
    "Acciones": "actions",
    "Opciones": "options",
    "Detalles": "details",
    "Ver": "view",
    "Crear": "create",
    "Actualizar": "update",
    "Buscar": "search",
    "Filtrar": "filter",
    "Limpiar": "clear",
    "Siguiente": "next",
    "Anterior": "previous",
    "Mostrar": "show",
    "Ocultar": "hide",
    "Confirmar": "confirm",
    "Aceptar": "accept",
    "Cerrar": "close",
    "Enviar": "send",
    
    # Modules
    "Paciente": "patient",
    "Pacientes": "patients",
    "Fisioterapeuta": "physiotherapist",
    "Fisioterapeutas": "physiotherapists",
    "Cita": "appointment",
    "Citas": "appointments",
    "Historial": "history",
    "Historiales": "histories",
    "Historial Clínico": "clinical_history",
    "Historiales Clínicos": "clinical_histories",
    "Usuario": "user",
    "Usuarios": "users",
    "Rol": "role",
    "Roles": "roles",
    "Especialidad": "specialty",
    "Especialidades": "specialties",
    "Perfil": "profile",
    "Dashboard": "dashboard",
    "Inicio": "home",
    "Bienvenido": "welcome",
    
    # Auth
    "Iniciar Sesión": "login",
    "Cerrar Sesión": "logout",
    "Registrarse": "register",
    "Contraseña": "password",
    "Confirmar Contraseña": "confirm_password",
    "Olvidé mi contraseña": "forgot_password",
    "Recordarme": "remember_me",
    "Correo Electrónico": "email",
    "Nombre": "name",
    "Apellido": "last_name",
    "Teléfono": "phone",
    "Dirección": "address",
    "Fecha de Nacimiento": "birth_date",
    "Género": "gender",
    "Masculino": "male",
    "Femenino": "female",
    "Otro": "other",
    
    # Form labels & fields
    "Estado": "status",
    "Pendiente": "pending",
    "Confirmada": "confirmed",
    "Cancelada": "cancelled",
    "Completada": "completed",
    "Fecha": "date",
    "Hora": "time",
    "Notas": "notes",
    "Descripción": "description",
    "Precio": "price",
    "Costo": "cost",
    "Total": "total",
    "Especialidad requerida": "required_specialty",
    "Motivo de consulta": "consultation_reason",
    "Diagnóstico": "diagnosis",
    "Tratamiento": "treatment",
    "Observaciones": "observations",
    
    # Headers
    "Nuevo Paciente": "new_patient",
    "Editar Paciente": "edit_patient",
    "Detalles del Paciente": "patient_details",
    "Nueva Cita": "new_appointment",
    "Editar Cita": "edit_appointment",
    "Detalles de la Cita": "appointment_details",
    "Nuevo Fisioterapeuta": "new_physiotherapist",
    "Editar Fisioterapeuta": "edit_physiotherapist",
    "Nuevo Usuario": "new_user",
    "Editar Usuario": "edit_user",
    
    # Messages
    "Creado exitosamente": "created_successfully",
    "Actualizado exitosamente": "updated_successfully",
    "Eliminado exitosamente": "deleted_successfully",
    "No se encontraron resultados": "no_results_found",
    "Seleccione una opción": "select_an_option",
    "Por favor, complete este campo": "please_fill_this_field",
    "Campo obligatorio": "required_field",
    "¿Estás seguro?": "are_you_sure",
    "Esta acción no se puede deshacer": "cannot_be_undone",
    "Agendar Cita": "schedule_appointment",
    "Mis Citas": "my_appointments",
    "Mis Pacientes": "my_patients",
    "Horarios": "schedules",
    "Disponibilidad": "availability",
}

# The reverse mapping to generate messages.php
en_translations = {
    "save": "Save",
    "edit": "Edit",
    "delete": "Delete",
    "cancel": "Cancel",
    "back": "Back",
    "actions": "Actions",
    "options": "Options",
    "details": "Details",
    "view": "View",
    "create": "Create",
    "update": "Update",
    "search": "Search",
    "filter": "Filter",
    "clear": "Clear",
    "next": "Next",
    "previous": "Previous",
    "show": "Show",
    "hide": "Hide",
    "confirm": "Confirm",
    "accept": "Accept",
    "close": "Close",
    "send": "Send",
    
    "patient": "Patient",
    "patients": "Patients",
    "physiotherapist": "Physiotherapist",
    "physiotherapists": "Physiotherapists",
    "appointment": "Appointment",
    "appointments": "Appointments",
    "history": "History",
    "histories": "Histories",
    "clinical_history": "Clinical History",
    "clinical_histories": "Clinical Histories",
    "user": "User",
    "users": "Users",
    "role": "Role",
    "roles": "Roles",
    "specialty": "Specialty",
    "specialties": "Specialties",
    "profile": "Profile",
    "dashboard": "Dashboard",
    "home": "Home",
    "welcome": "Welcome",
    
    "login": "Log in",
    "logout": "Log out",
    "register": "Register",
    "password": "Password",
    "confirm_password": "Confirm Password",
    "forgot_password": "Forgot your password?",
    "remember_me": "Remember me",
    "email": "Email",
    "name": "Name",
    "last_name": "Last Name",
    "phone": "Phone",
    "address": "Address",
    "birth_date": "Birth Date",
    "gender": "Gender",
    "male": "Male",
    "female": "Female",
    "other": "Other",
    
    "status": "Status",
    "pending": "Pending",
    "confirmed": "Confirmed",
    "cancelled": "Cancelled",
    "completed": "Completed",
    "date": "Date",
    "time": "Time",
    "notes": "Notes",
    "description": "Description",
    "price": "Price",
    "cost": "Cost",
    "total": "Total",
    "required_specialty": "Required Specialty",
    "consultation_reason": "Reason for Consultation",
    "diagnosis": "Diagnosis",
    "treatment": "Treatment",
    "observations": "Observations",
    
    "new_patient": "New Patient",
    "edit_patient": "Edit Patient",
    "patient_details": "Patient Details",
    "new_appointment": "New Appointment",
    "edit_appointment": "Edit Appointment",
    "appointment_details": "Appointment Details",
    "new_physiotherapist": "New Physiotherapist",
    "edit_physiotherapist": "Edit Physiotherapist",
    "new_user": "New User",
    "edit_user": "Edit User",
    
    "created_successfully": "Created successfully",
    "updated_successfully": "Updated successfully",
    "deleted_successfully": "Deleted successfully",
    "no_results_found": "No results found",
    "select_an_option": "Select an option",
    "please_fill_this_field": "Please fill this field",
    "required_field": "Required field",
    "are_you_sure": "Are you sure?",
    "cannot_be_undone": "This action cannot be undone",
    "schedule_appointment": "Schedule Appointment",
    "my_appointments": "My Appointments",
    "my_patients": "My Patients",
    "schedules": "Schedules",
    "availability": "Availability",
}

# Generate messages.php
lang_dir = r"c:\xampp\htdocs\physiocare-laravel\resources\lang\en"
os.makedirs(lang_dir, exist_ok=True)

with open(os.path.join(lang_dir, "messages.php"), "w", encoding="utf-8") as f:
    f.write("<?php\n\nreturn [\n")
    for key, value in en_translations.items():
        f.write(f"    '{key}' => '{value}',\n")
    f.write("];\n")

print("Generated resources/lang/en/messages.php")

# Compile regex patterns
# We sort keys by length descending so longer phrases match first
sorted_keys = sorted(translations.keys(), key=len, reverse=True)

def process_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    original_content = content
    
    for es_text in sorted_keys:
        en_key = translations[es_text]
        
        # In blade files we replace text with {{ __('messages.key') }}
        if filepath.endswith('.blade.php'):
            # Simple text replacement (this is aggressive, might need caution)
            # Regex to find exact word boundaries or within > <
            # Using simple replace for now as regex might break HTML
            content = content.replace(f">{es_text}<", f">{{{{ __('messages.{en_key}') }}}}<")
            content = content.replace(f"'{es_text}'", f"__('messages.{en_key}')")
            content = content.replace(f'"{es_text}"', f"__('messages.{en_key}')")
            # Replace placeholder="Buscar..." -> placeholder="{{ __('messages.search') }}"
            content = content.replace(f'placeholder="{es_text}"', f'placeholder="{{{{ __(\'messages.{en_key}\') }}}}"')
            content = content.replace(f'placeholder="{es_text}..."', f'placeholder="{{{{ __(\'messages.{en_key}\') }}}}"')
            # Look for raw text in common spots like {{ 'Guardar' }}
            
        elif filepath.endswith('.php'):
            # In controllers or seeders
            content = content.replace(f"'{es_text}'", f"__('messages.{en_key}')")
            content = content.replace(f'"{es_text}"', f"__('messages.{en_key}')")

    if content != original_content:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Updated: {filepath}")

# Walk through views
views_dir = r"c:\xampp\htdocs\physiocare-laravel\resources\views"
for root, dirs, files in os.walk(views_dir):
    for file in files:
        if file.endswith('.blade.php'):
            process_file(os.path.join(root, file))

# Walk through controllers
controllers_dir = r"c:\xampp\htdocs\physiocare-laravel\app\Http\Controllers"
for root, dirs, files in os.walk(controllers_dir):
    for file in files:
        if file.endswith('.php'):
            process_file(os.path.join(root, file))

# Walk through seeders
seeders_dir = r"c:\xampp\htdocs\physiocare-laravel\database\seeders"
for root, dirs, files in os.walk(seeders_dir):
    for file in files:
        if file.endswith('.php'):
            process_file(os.path.join(root, file))

print("Translation script completed.")
