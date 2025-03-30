<div class="max-w-4xl mx-auto p-6 bg-gray-800 shadow-lg rounded-lg">
    <!-- Mensaje de éxito -->
    @if (session()->has('message'))
        <div class="alert alert-success mb-4 text-white">
            {{ session('message') }}
        </div>
    @endif

    <!-- Formulario de búsqueda y selección de producto -->
    <form wire:submit.prevent="buscarProducto" class="space-y-4 text-white">
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
                            <td class="px-4 py-2">
                                <input type="number" wire:model="productosSeleccionados.{{ $index }}.cantidad" 
                                    wire:change="actualizarCantidad({{ $index }})" 
                                    class="w-16 px-2 py-1 border border-gray-600 rounded bg-gray-700 text-white" />
                            </td>
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
    <div class="flex justify-end">
            <button wire:click="agregarProductoLista" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Agregar Producto
            </button>
        </div>
    <!-- Formulario de pago y cliente -->
    @if(!empty($productosSeleccionados))
        <form wire:submit.prevent="submit" class="mt-6 space-y-4 text-white">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Selección del método de pago -->
                <div class="mb-4">
                    <label for="metodo_pago" class="block font-medium text-gray-300">Método de pago:</label>
                    <select id="metodo_pago" wire:model="metodo_pago" 
                        class="w-full px-4 py-2 border border-gray-600 rounded bg-gray-700 text-white">
                        <option value="">Seleccione un método</option>
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta">Tarjeta</option>
                    </select>
                </div>
            </div>

            <!-- Mostrar calculadora si se selecciona efectivo o tarjeta -->
            @if ($metodo_pago === 'efectivo' || $metodo_pago === 'tarjeta')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Campo para el pago en efectivo -->
                    @if ($metodo_pago === 'efectivo')
                        <div class="mb-4">
                            <label for="pago_efectivo" class="block font-medium text-gray-300">Pago en Efectivo:</label>
                            <input type="number" id="pago_efectivo" wire:model="pago_efectivo" 
                                class="w-full px-4 py-2 border border-gray-600 rounded bg-gray-700 text-white" 
                                placeholder="Ingrese el monto en efectivo" />
                        </div>
                    @endif

                    <!-- Campo para el pago con tarjeta -->
                    @if ($metodo_pago === 'tarjeta')
                        <div class="mb-4">
                            <label for="pago_tarjeta" class="block font-medium text-gray-300">Pago con Tarjeta:</label>
                            <input type="number" id="pago_tarjeta" wire:model="pago_tarjeta" 
                                class="w-full px-4 py-2 border border-gray-600 rounded bg-gray-700 text-white" 
                                placeholder="Ingrese el monto con tarjeta" />
                        </div>
                    @endif
                </div>

                <!-- Mostrar el vuelto -->
                <div class="mb-4 text-green-400">
                    <strong>Vuelto:</strong> ${{ number_format($vuelto, ) }}
                </div>

                <!-- Mostrar el monto restante si no se ha completado el pago -->
                @if ($restante > 0)
                    <div class="mb-4 text-red-400">
                        <strong>Restante:</strong> ${{ number_format($restante, ) }}
                    </div>
                @endif
            @endif

            <!-- Botón para registrar la venta -->
            <div class="mb-4">
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Registrar Venta
                </button>
            </div>
        </form>
    @endif
</div>