<div class="max-w-4xl mx-auto p-6 bg-gray-800 shadow-lg rounded-lg">
    <!-- Modal para registrar o editar cliente -->
    <div>
        <flux:modal.trigger name="cliente-modal">
            <flux:button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                {{ $cliente_id ? 'Editar Cliente' : 'Registrar Cliente' }}
            </flux:button>
        </flux:modal.trigger>

        <flux:modal name="cliente-modal" class="md:w-96">
            <div class="space-y-6">
                <div>
                    <h2 class="text-lg font-bold text-white">{{ $cliente_id ? 'Editar Cliente' : 'Registrar Cliente' }}</h2>
                    <p class="text-gray-400">Complete la información del cliente.</p>
                </div>

                @if (session()->has('message'))
                    <div class="bg-green-500 text-white p-2 mb-2">
                        {{ session('message') }}
                    </div>
                @endif

                <form wire:submit.prevent="submit" class="space-y-4">
                    <div>
                        <label for="nombre" class="block font-medium text-gray-300">Nombre:</label>
                        <input type="text" id="nombre" wire:model="nombre" 
                            class="w-full px-4 py-2 border border-gray-600 rounded bg-gray-700 text-white placeholder-gray-400" 
                            placeholder="Nombre del cliente" />
                        @error('nombre') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="email" class="block font-medium text-gray-300">Correo Electrónico:</label>
                        <input type="email" id="email" wire:model="email" 
                            class="w-full px-4 py-2 border border-gray-600 rounded bg-gray-700 text-white placeholder-gray-400" 
                            placeholder="Correo del cliente" />
                        @error('email') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="telefono" class="block font-medium text-gray-300">Teléfono:</label>
                        <input type="text" id="telefono" wire:model="telefono" 
                            class="w-full px-4 py-2 border border-gray-600 rounded bg-gray-700 text-white placeholder-gray-400" 
                            placeholder="Teléfono del cliente" />
                        @error('telefono') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="direccion" class="block font-medium text-gray-300">Dirección:</label>
                        <input type="text" id="direccion" wire:model="direccion" 
                            class="w-full px-4 py-2 border border-gray-600 rounded bg-gray-700 text-white placeholder-gray-400" 
                            placeholder="Dirección del cliente" />
                        @error('direccion') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            {{ $cliente_id ? 'Actualizar Cliente' : 'Registrar Cliente' }}
                        </button>
                    </div>
                </form>
            </div>
        </flux:modal>
    </div>

    <!-- Índice de Clientes -->
    <div class="mt-6">
        <h2 class="text-lg font-bold text-white">Lista de Clientes</h2>
        <table class="w-full mt-4 text-white border border-gray-600">
            <thead>
                <tr class="bg-gray-700">
                    <th class="px-4 py-2">Nombre</th>
                    <th class="px-4 py-2">Correo Electrónico</th>
                    <th class="px-4 py-2">Teléfono</th>
                    <th class="px-4 py-2">Dirección</th>
                    <th class="px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clientes as $cliente)
                <tr class="border-t border-gray-600">
                    <td class="px-4 py-2">{{ $cliente->nombre }}</td>
                    <td class="px-4 py-2">{{ $cliente->email }}</td>
                    <td class="px-4 py-2">{{ $cliente->telefono }}</td>
                    <td class="px-4 py-2">{{ $cliente->direccion }}</td>
                    <td class="px-4 py-2">
                        <button wire:click="edit({{ $cliente->id }})" 
                            class="px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Editar
                        </button>
                        <button wire:click="delete({{ $cliente->id }})" 
                            class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                            Eliminar
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>