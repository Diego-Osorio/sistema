<x-layouts.app >
<div class="relative mb-6 w-full">
<flux:breadcrumbs>
    <flux:breadcrumbs.item :href="route('dashboard')">
        Inicio
    </flux:breadcrumbs.item> Producto
    <flux:breadcrumbs.item :href="route('producto')">
        
    </flux:breadcrumbs.item>
    <!-- <flux:breadcrumbs.item>
        Post
    </flux:breadcrumbs.item> -->
</flux:breadcrumbs>
</div>
<livewire:producto />
</x-layouts.app>