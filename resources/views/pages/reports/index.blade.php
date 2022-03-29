<x-app-layout>
    @section('title', 'Reports')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    @if (auth()->user()->role_id != \App\Models\Role::ADMIN)
        @can('take_batch', \App\Models\Report::class)
            <livewire:take-report-batch />
        @else
            <livewire:reports-table />
        @endcan
    @else
        <livewire:reports-table />
    @endif

</x-app-layout>
