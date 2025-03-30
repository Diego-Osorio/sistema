<div class="max-w-4xl mx-auto p-6 bg-gray-800 shadow-lg rounded-lg">
    <!-- Mensaje de éxito -->
    @if (session()->has('message'))
        <div class="alert alert-success mb-4 text-white">
            {{ session('message') }}
        </div>
    @endif

    <!-- Formulario de búsqueda y selección de producto -->
    <form wire:submit.prevent="agregarProductoLista" class="space-y-4 text-white">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Campo de búsqueda de productos -->
            <div class="mb-4">
                <label for="producto" class="block font-medium text-gray-300">Producto:</label>
                <input type="text" id="producto" wire:model="producto" wire:keyup="buscarProducto" 
                    class="w-full px-4 py-2 border border-gray-600 rounded bg-gray-700 text-white placeholder-gray-400" 
                    placeholder="Buscar producto..." />

                <!-- Lista de productos encontrados -->
                @if(!empty($productos))
                    <ul class="mt-2 border border-gray-600 rounded max-h-60 overflow-y-auto bg-gray-700">
                        @foreach($productos as $prod)
                            <li wire:click="seleccionarProducto({{ $prod->id }})" 
                                class="px-4 py-2 cursor-pointer hover:bg-gray-600">
                                Código: {{ $prod->codigo }} - {{ $prod->nombre }} - Precio: ${{ number_format($prod->precio, 2) }} - Stock: {{ $prod->stock }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- Campo de cantidad -->
            <div class="mb-4">
                <label for="cantidad" class="block font-medium text-gray-300">Cantidad:</label>
                <input type="number" id="cantidad" wire:model="cantidad" min="1" 
                    class="w-full px-4 py-2 border border-gray-600 rounded bg-gray-700 text-white placeholder-gray-400" />
            </div>
        </div>

        <!-- Botón para agregar producto -->
        <div class="flex justify-end">
            <button wire:click="agregarProductoLista" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Agregar Producto
            </button>
        </div>
    </form>

    <!-- Carrito de productos -->
    @if(!empty($productosSeleccionados))
        <div class="mt-6">
            <h2 class="text-lg font-bold text-white">Carrito de Productos</h2>
            <table class="w-full mt-4 text-white border border-gray-600">
                <thead>
                    <tr class="bg-gray-700">
                        <th class="px-4 py-2">Producto</th>
                        <th class="px-4 py-2">Cantidad</th>
                        <th class="px-4 py-2">Precio Unitario</th>
                        <th class="px-4 py-2">Total</th>
                        <th class="px-4 py-2">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productosSeleccionados as $index => $producto)
                        <tr class="border-t border-gray-600">
                            <td class="px-4 py-2">{{ $producto['producto'] }}</td>
                            <td class="px-4 py-2">{{ $producto['cantidad'] }}</td>
                            <td class="px-4 py-2">${{ number_format($producto['precio'], ) }}</td>
                            <td class="px-4 py-2">${{ number_format($producto['total'], ) }}</td>
                            <td class="px-4 py-2">
                                <button wire:click="eliminarProducto({{ $index }})" 
                                    class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-700 font-bold">
                        <td colspan="3" class="px-4 py-2 text-right">Total Venta:</td>
                        <td class="px-4 py-2">${{ number_format($totalVenta, ) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif

    <!-- Formulario de pago y cliente -->
    <form wire:submit.prevent="submit" class="mt-6 space-y-4 text-white">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Selección de cliente -->
            <div class="mb-4">
                <label for="cliente_id" class="block font-medium text-gray-300">Cliente:</label>
                <select id="cliente_id" wire:model="cliente_id" 
                    class="w-full px-4 py-2 border border-gray-600 rounded bg-gray-700 text-white">
                    <option value="">Seleccione un cliente</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Selección del método de pago -->
            <div class="mb-4">
                <label for="metodo_pago" class="block font-medium text-gray-300">Método de pago:</label>
                <select id="metodo_pago" wire:model="metodo_pago" 
                    class="w-full px-4 py-2 border border-gray-600 rounded bg-gray-700 text-white">
                    <option value="efectivo">Efectivo</option>
                    <option value="tarjeta">Tarjeta</option>
                    <option value="transferencia">Transferencia</option>
                    <option value="otro">Otro</option>
                </select>
            </div>
        </div>

        <!-- Botón para registrar la venta -->
        <div class="mb-4">
            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Registrar Venta
            </button>
        </div>
    </form>
</div>
