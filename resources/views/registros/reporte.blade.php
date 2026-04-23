{{-- resources/views/registros/reporte.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exportar Reporte</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Exportar Reporte</h1>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600">{{ session('user_nombre') }}</span>
                    <a href="{{ route('logout') }}" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Salir</a>
                </div>
            </div>
            
            <!-- Estadísticas -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h2 class="font-bold text-lg mb-2">Estadísticas de Registros</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Total de registros:</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $totalRegistros }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Registros por sede:</p>
                        <div class="text-sm">
                            @foreach($registrosPorSede as $sede => $cantidad)
                                <p><span class="font-semibold">{{ $sede }}:</span> {{ $cantidad }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            <form action="{{ route('exportar.excel') }}" method="GET">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Seleccionar Sede</label>
                    <select name="sede_id" class="w-full px-3 py-2 border rounded-lg">
                        <option value="">Todas las sedes</option>
                        @foreach($sedes as $sede)
                            <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex gap-4 mb-4">
                    <button type="submit" class="flex-1 bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                        📊 Exportar a Excel
                    </button>
                    <a href="{{ route('registros.index') }}" class="flex-1 bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 text-center">
                        📝 Volver al Formulario
                    </a>
                </div>
            </form>
            
            <!-- Botón de truncar - Solo visible para admin -->
            @if(session('user_usuario') === 'admin')
                <div class="border-t pt-4 mt-4">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-bold text-red-700">⚠️ Zona de Administrador</h3>
                                <p class="text-sm text-red-600">Esta acción eliminará TODOS los registros de la base de datos</p>
                                <p class="text-xs text-red-500 mt-1">Registros a eliminar: <strong>{{ $totalRegistros }}</strong></p>
                            </div>
                            <button id="btnTruncar" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                                🗑️ Vaciar todos los registros
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <script>
        @if(session('user_usuario') === 'admin')
        document.getElementById('btnTruncar').addEventListener('click', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: '⚠️ ¿Estás seguro?',
                html: `
                    <p>Esta acción <strong>ELIMINARÁ PERMANENTEMENTE</strong> todos los registros.</p>
                    <p class="text-red-600 font-bold mt-2">Esta acción no se puede deshacer.</p>
                    <p class="mt-4">Total de registros a eliminar: <strong>{{ $totalRegistros }}</strong></p>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar todo',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar loading
                    Swal.fire({
                        title: 'Eliminando registros...',
                        text: 'Por favor espere',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Hacer la petición DELETE
                    fetch('{{ route("truncar.registros") }}', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: '¡Eliminado!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: data.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    }).catch(error => {
                        Swal.fire({
                            title: 'Error',
                            text: 'Ocurrió un error al eliminar los registros',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
                }
            });
        });
        @endif
    </script>
</body>
</html>