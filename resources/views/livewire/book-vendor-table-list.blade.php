<div>
    <div class="w-full h-full flex items-center justify-between">
        <a href="{{ route('bookvendors.create') }}"
            class="h-full inline-flex bg-green-300 hover:bg-green-400 px-4 py-2 rounded-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg> Add Vendor
        </a>
        <div class="flex items-stretch">
            <div>
                <label for="view_item_count">Show </label>
                <select wire:model="count" name="view_item_count" id="view_item_count" class="rounded-md">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="15" selected>15</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="flex items-stretch">
                <input wire:model="lookup" type="text" placeholder="Search For Vendor..."
                    class="placeholder:italic h-full ml-2 rounded-l-md">
                <button class="h-full bg-gray-800 hover:bg-black p-2 text-white rounded-r-md mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div class="py-2 align-middle inline-block min-w-full">
        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Public
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Edit</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($vendors as $b)
                        <tr class="">
                            <td class="px-6 py-4 whitespace-wrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $b->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $b->public ? 'Visible' : 'Hidden'}}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('bookvendors.edit', ['bookvendor' => $b->id]) }}"
                                    class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <form action="{{ route('bookvendors.destroy', ['bookvendor' => $b->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-2 bg-gray-100 w-full flex items-center justify-center">
                {{$vendors->links()}}
            </div>
        </div>
    </div>

</div>
