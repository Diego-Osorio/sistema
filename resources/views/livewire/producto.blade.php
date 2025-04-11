<div>
    {{-- Modal para agregar o editar producto --}}
    <flux:modal.trigger name="producto-modal">
        <flux:button class="bg-gray-800 text-white hover:bg-gray-700">{{ $producto_id ? 'Editar Producto' : 'Registrar Producto' }}</flux:button>
    </flux:modal.trigger>

    <flux:modal name="producto-modal" class="md:w-96 bg-gray-900 text-white" wire:modal="close-producto-modal">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $producto_id ? 'Editar Producto' : 'Registrar Producto' }}</flux:heading>
                <flux:subheading>Complete la información del producto.</flux:subheading>
            </div>

            {{-- Mensaje de éxito --}}
            @if (session()->has('success'))
                <div class="bg-green-500 text-white p-2 mb-2">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Formulario de agregar producto --}}
            <form wire:submit.prevent="agregarProducto" class="space-y-4">
                <flux:input label="Nombre" placeholder="Nombre del producto" wire:model="nombre" class="bg-gray-800 text-white border border-gray-700" />
                @error('nombre') <span class="text-red-500">{{ $message }}</span> @enderror

                <flux:input label="Código" placeholder="Código del producto" wire:model="codigo" class="bg-gray-800 text-white border border-gray-700" />
                @error('codigo') <span class="text-red-500">{{ $message }}</span> @enderror

                <flux:input label="Precio" placeholder="Precio del producto" type="number" step="0.01" wire:model="precio" class="bg-gray-800 text-white border border-gray-700" />
                @error('precio') <span class="text-red-500">{{ $message }}</span> @enderror

                <flux:input label="Stock" placeholder="Stock disponible" type="number" wire:model="stock" class="bg-gray-800 text-white border border-gray-700" />
                @error('stock') <span class="text-red-500">{{ $message }}</span> @enderror

                <flux:select label="Categoría" wire:model="categoria_id" class="bg-gray-800 text-white border border-gray-700">
                    <option value="">Seleccione una categoría</option>
                    @foreach ($categoria as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                    @endforeach
                </flux:select>
                @error('categoria_id') <span class="text-red-500">{{ $message }}</span> @enderror

                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary" class="bg-blue-600 text-white hover:bg-blue-700">{{ $producto_id ? 'Actualizar Producto' : 'Registrar Producto' }}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    {{-- Lista de productos --}}
    <div class="mt-6 bg-gray-900 p-6 rounded-lg shadow-lg">
    <flux:heading size="lg" class="text-white dark:text-black">Lista de Productos</flux:heading>

        <table class="w-full border-collapse border border-gray-700 mt-4">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="border p-4 text-left">Nombre</th>
                    <th class="border p-4 text-left">Código</th>
                    <th class="border p-4 text-left">Precio</th>
                    <th class="border p-4 text-left">Stock</th>
                    <th class="border p-4 text-left">Categoría</th>
                    <th class="border p-4 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-gray-900 text-white">
                @foreach($productos as $producto)
                <tr class="hover:bg-gray-700">
                    <td class="border p-4">{{ $producto->nombre }}</td>
                    <td class="border p-4">{{ $producto->codigo }}</td>
                    <td class="border p-4 text-green-400">${{ number_format($producto->precio, ) }}</td>
                    <td class="border p-4 text-yellow-400">{{ $producto->stock }}</td>
                    <td class="border p-4">{{ $producto->categoria->nombre }}</td>
                    <td class="border p-4 flex gap-2">
                        <flux:button wire:click="edit({{ $producto->id }})" class="bg-yellow-500 text-white hover:bg-yellow-600">Editar</flux:button>
                        <flux:button variant="danger" wire:click="delete({{ $producto->id }})" class="bg-red-600 text-white hover:bg-red-700">Eliminar</flux:button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    

</div>
