<x-layouts.app >
<div class="relative mb-6 w-full">
<flux:breadcrumbs>
    <flux:breadcrumbs.item :href="route('dashboard')">
        Inicio
    </flux:breadcrumbs.item> Venta
    <flux:breadcrumbs.item :href="route('venta')">
        
    </flux:breadcrumbs.item>
    <!-- <flux:breadcrumbs.item>
        Post
    </flux:breadcrumbs.item> -->
</flux:breadcrumbs>
</div>
<livewire:venta />
</x-layouts.app>
