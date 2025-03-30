<div>
    {{-- Modal para agregar o editar categoría --}}
    <flux:modal.trigger name="categoria-modal">
        <flux:button class="bg-gray-800 text-white hover:bg-gray-700">{{ $modo_edicion ? 'Editar Categoría' : 'Registrar Categoría' }}</flux:button>
    </flux:modal.trigger>

    <flux:modal name="categoria-modal" class="md:w-96 bg-gray-900 text-white">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $modo_edicion ? 'Editar Categoría' : 'Registrar Categoría' }}</flux:heading>
                <flux:subheading>Complete la información de la categoría.</flux:subheading>
            </div>

            {{-- Mensaje de éxito --}}
            @if (session()->has('success'))
                <div class="bg-green-500 text-white p-2 mb-2">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Formulario de agregar o editar categoría --}}
            <form wire:submit.prevent="{{ $modo_edicion ? 'actualizarCategoria' : 'agregarCategoria' }}" class="space-y-4">
                <flux:input label="Nombre" placeholder="Nombre de la categoría" wire:model="nombre" class="bg-gray-800 text-white border border-gray-700" />
                @error('nombre') <span class="text-red-500">{{ $message }}</span> @enderror

                <flux:textarea label="Descripción" placeholder="Descripción de la categoría" wire:model="descripcion" class="bg-gray-800 text-white border border-gray-700" />
                @error('descripcion') <span class="text-red-500">{{ $message }}</span> @enderror

                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary" class="bg-blue-600 text-white hover:bg-blue-700">{{ $modo_edicion ? 'Actualizar' : 'Agregar' }} Categoría</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    {{-- Lista de categorías dentro de una tarjeta --}}
    <div class="mt-6 bg-gray-800 p-6 rounded-lg shadow-lg border border-gray-700">
        <flux:heading size="lg" class="text-white">Lista de Categorías</flux:heading>
        <table class="w-full border-collapse mt-4">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="border p-4 text-left">Nombre</th>
                    <th class="border p-4 text-left">Descripción</th>
                    <th class="border p-4 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-gray-900">
                @foreach ($categoria as $cat)
                    <tr class="hover:bg-gray-700">
                        <td class="border p-4">{{ $cat->nombre }}</td>
                        <td class="border p-4">{{ $cat->descripcion }}</td>
                        <td class="border p-4 flex gap-2">
                            <flux:button wire:click="editarCategoria({{ $cat->id }})" class="bg-yellow-500 text-white hover:bg-yellow-600">Editar</flux:button>
                            <flux:button variant="danger" wire:click="eliminarCategoria({{ $cat->id }})" onclick="confirm('¿Seguro que deseas eliminar esta categoría?') || event.stopImmediatePropagation();" class="bg-red-600 text-white hover:bg-red-700">Eliminar</flux:button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
