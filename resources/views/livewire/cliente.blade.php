<div>
    <flux:modal.trigger name="cliente-modal">
        <flux:button>{{ $cliente_id ? 'Editar Cliente' : 'Registrar Cliente' }}</flux:button>
    </flux:modal.trigger>

    <flux:modal name="cliente-modal" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $cliente_id ? 'Editar Cliente' : 'Registrar Cliente' }}</flux:heading>
                <flux:subheading>Complete la información del cliente.</flux:subheading>
            </div>

            @if (session()->has('message'))
                <div class="bg-green-500 text-white p-2 mb-2">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit.prevent="submit" class="space-y-4">
                <flux:input label="Nombre" placeholder="Nombre del cliente" wire:model="nombre" />
                @error('nombre') <span class="text-red-500">{{ $message }}</span> @enderror

                <flux:input label="Correo Electrónico" type="email" placeholder="Correo del cliente" wire:model="email" />
                @error('email') <span class="text-red-500">{{ $message }}</span> @enderror

                <flux:input label="Teléfono" placeholder="Teléfono del cliente" wire:model="telefono" />
                @error('telefono') <span class="text-red-500">{{ $message }}</span> @enderror

                <flux:input label="Dirección" placeholder="Dirección del cliente" wire:model="direccion" />
                @error('direccion') <span class="text-red-500">{{ $message }}</span> @enderror

                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">{{ $cliente_id ? 'Actualizar Cliente' : 'Registrar Cliente' }}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    <!-- Índice de Clientes -->
    <div class="mt-6">
        <flux:heading size="lg">Lista de Clientes</flux:heading>
        <table class="w-full border-collapse border border-gray-300 mt-4">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">Nombre</th>
                    <th class="border p-2">Correo Electrónico</th>
                    <th class="border p-2">Teléfono</th>
                    <th class="border p-2">Dirección</th>
                    <th class="border p-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clientes as $cliente)
                <tr>
                    <td class="border p-2">{{ $cliente->nombre }}</td>
                    <td class="border p-2">{{ $cliente->email }}</td>
                    <td class="border p-2">{{ $cliente->telefono }}</td>
                    <td class="border p-2">{{ $cliente->direccion }}</td>
                    <td class="border p-2">
                        <flux:button wire:click="edit({{ $cliente->id }})">Editar</flux:button>
                        <flux:button variant="danger" wire:click="delete({{ $cliente->id }})">Eliminar</flux:button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
