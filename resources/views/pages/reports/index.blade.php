<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="py-2 align-middle inline-block min-w-full">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/5">
                                    Title
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">
                                    Details
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">
                                    Item
                                </th>
                                <th scope="col" class="relative px-6 py-3 w-1/5">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($reports as $r)
                                <tr class="">
                                    <td class="px-6 py-4 whitespace-wrap w-2/5">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $r->title }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-wrap w-1/5">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $r->details }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-wrap w-1/5">
                                        <a href="{{ $r->item_type == 'App\Models\Book' ? route('book.edit', ['book' => $r->item_id]) : route('series.edit', ['series' => $r->item_id]) }}"
                                            target="_blank"
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $r->item_type == 'App\Models\Book' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-900' }}">
                                            @if (isset($r->item->title)) {{ $r->item->title }} @endif
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 w-1/5">
                                        <form action="{{ route('reports.destroy', ['report' => $r->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-indigo-600 hover:text-red-900">Complete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="p-2 bg-gray-100 w-full flex items-center justify-center">
                        {{ $reports->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
