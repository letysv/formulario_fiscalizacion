{{-- resources/views/registros/formulario.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">Formulario de Registro</h1>
            <p class="font-bold mb-6">"Veracruz y la Fiscalización: Capacitación por una Gestión Responsable 2026"</p> <br>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('registros.store') }}" method="POST" id="registroForm">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Sede</label>
                    <select name="sede_id" id="sede_id" class="w-full px-3 py-2 border rounded-lg" required>
                        <option value="">Seleccione una sede</option>
                        @foreach($sedes as $sede)
                            <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Ayuntamiento</label>
                    <select name="ayuntamiento_id" id="ayuntamiento_id" class="w-full px-3 py-2 border rounded-lg" required>
                        <option value="">Primero seleccione una sede</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Cargo</label>
                    <select name="cargo_id" class="w-full px-3 py-2 border rounded-lg" required>
                        <option value="">Seleccione un cargo</option>
                        @foreach($cargos as $cargo)
                            <option value="{{ $cargo->id }}">{{ $cargo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nombre Completo</label>
                    <input type="text" name="nombre_completo" id="nombre_completo" 
                           class="w-full px-3 py-2 border rounded-lg" 
                           pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" 
                           title="Solo letras y espacios" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Teléfono Celular <span class="text-red-500">*</span></label>
                    <input type="tel" name="telefono" id="telefono" 
                           class="w-full px-3 py-2 border rounded-lg" 
                           maxlength="10"
                           pattern="[0-9]{10}"
                           title="Debe contener 10 dígitos numéricos"
                           required>
                    <div id="telefonoError" class="text-red-500 text-xs hidden">⚠️ El teléfono debe tener 10 dígitos</div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Teléfono de Oficina</label>
                    <input type="tel" name="telefono_oficina" id="telefono_oficina" 
                           class="w-full px-3 py-2 border rounded-lg" 
                           maxlength="10"
                           pattern="[0-9]{10}"
                           title="Debe contener 10 dígitos numéricos">
                    <div id="telefonoOfiError" class="text-red-500 text-xs hidden">⚠️ El teléfono debe tener 10 dígitos</div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Correo Personal <span class="text-red-500">*</span></label>
                    <input type="email" name="correo_personal" id="correo_personal" 
                           class="w-full px-3 py-2 border rounded-lg" 
                           required>
                    <div id="correoPersonalError" class="text-red-500 text-xs hidden">⚠️ Ingrese un correo electrónico válido (ejemplo@dominio.com)</div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Correo Institucional <span class="text-red-500">*</span></label>
                    <input type="email" name="correo_institucional" id="correo_institucional" 
                           class="w-full px-3 py-2 border rounded-lg" 
                           required>
                    <div id="correoInstError" class="text-red-500 text-xs hidden">⚠️ Ingrese un correo electrónico válido (ejemplo@dominio.com)</div>
                </div>
                
                <div class="flex justify-between items-center">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        Guardar Registro
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        $(document).ready(function() {
            // Filtro dinámico de ayuntamientos
            $('#sede_id').change(function() {
                var sedeId = $(this).val();
                if(sedeId) {
                    $.ajax({
                        url: '/ayuntamientos/' + sedeId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#ayuntamiento_id').empty();
                            $('#ayuntamiento_id').append('<option value="">Seleccione un ayuntamiento</option>');
                            $.each(data, function(key, value) {
                                $('#ayuntamiento_id').append('<option value="'+ value.id +'">'+ value.nombre +'</option>');
                            });
                        }
                    });
                } else {
                    $('#ayuntamiento_id').empty();
                    $('#ayuntamiento_id').append('<option value="">Primero seleccione una sede</option>');
                }
            });
            
            // Función para validar email
            function validarEmail(email) {
                const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
                return regex.test(email);
            }
            
            // Validación en tiempo real para teléfono celular
            $('#telefono').on('input', function() {
                // Eliminar cualquier caracter que no sea número
                this.value = this.value.replace(/[^0-9]/g, '');
                
                if(this.value.length > 0 && this.value.length !== 10) {
                    $(this).addClass('border-red-500').removeClass('border-green-500');
                    $('#telefonoError').removeClass('hidden');
                } else if(this.value.length === 10) {
                    $(this).removeClass('border-red-500').addClass('border-green-500');
                    $('#telefonoError').addClass('hidden');
                } else {
                    $(this).removeClass('border-red-500 border-green-500');
                    $('#telefonoError').addClass('hidden');
                }
            });
            
            // Validación en tiempo real para teléfono de oficina
            $('#telefono_oficina').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                
                if(this.value.length > 0 && this.value.length !== 10) {
                    $(this).addClass('border-red-500').removeClass('border-green-500');
                    $('#telefonoOfiError').removeClass('hidden');
                } else if(this.value.length === 10) {
                    $(this).removeClass('border-red-500').addClass('border-green-500');
                    $('#telefonoOfiError').addClass('hidden');
                } else {
                    $(this).removeClass('border-red-500 border-green-500');
                    $('#telefonoOfiError').addClass('hidden');
                }
            });
            
            // Validación en tiempo real para correo personal
            $('#correo_personal').on('input', function() {
                const email = this.value;
                if(email && !validarEmail(email)) {
                    $(this).addClass('border-red-500').removeClass('border-green-500');
                    $('#correoPersonalError').removeClass('hidden');
                } else if(email && validarEmail(email)) {
                    $(this).removeClass('border-red-500').addClass('border-green-500');
                    $('#correoPersonalError').addClass('hidden');
                } else {
                    $(this).removeClass('border-red-500 border-green-500');
                    $('#correoPersonalError').addClass('hidden');
                }
            });
            
            // Validación en tiempo real para correo institucional
            $('#correo_institucional').on('input', function() {
                const email = this.value;
                if(email && !validarEmail(email)) {
                    $(this).addClass('border-red-500').removeClass('border-green-500');
                    $('#correoInstError').removeClass('hidden');
                } else if(email && validarEmail(email)) {
                    $(this).removeClass('border-red-500').addClass('border-green-500');
                    $('#correoInstError').addClass('hidden');
                } else {
                    $(this).removeClass('border-red-500 border-green-500');
                    $('#correoInstError').addClass('hidden');
                }
            });
            
            // Validación antes de enviar el formulario
            $('#registroForm').on('submit', function(e) {
                let telefono = $('#telefono').val();
                let telefonoOfi = $('#telefono_oficina').val();
                let correoPersonal = $('#correo_personal').val();
                let correoInst = $('#correo_institucional').val();
                let isValid = true;
                let errorMessage = '';
                
                // Validar teléfono celular
                if(!/^\d{10}$/.test(telefono)) {
                    errorMessage += '❌ El teléfono celular debe tener exactamente 10 dígitos numéricos.\n';
                    isValid = false;
                }
                
                // Validar teléfono de oficina
                if(telefonoOfi && !/^\d{10}$/.test(telefonoOfi)) {
                    errorMessage += '❌ El teléfono de oficina debe tener exactamente 10 dígitos numéricos.\n';
                    isValid = false;
                }
                
                // Validar correo personal
                if(!validarEmail(correoPersonal)) {
                    errorMessage += '❌ El correo personal no es válido. Debe tener el formato: nombre@dominio.com\n';
                    isValid = false;
                }
                
                // Validar correo institucional
                if(!validarEmail(correoInst)) {
                    errorMessage += '❌ El correo institucional no es válido. Debe tener el formato: nombre@dominio.com\n';
                    isValid = false;
                }
                
                if(!isValid) {
                    e.preventDefault();
                    alert('Por favor corrija los siguientes errores:\n\n' + errorMessage);
                }
            });
        });
    </script>
</body>
</html>