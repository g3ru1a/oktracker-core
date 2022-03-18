<x-app-layout>
    @section('title', 'Reports')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(auth()->user()->role_id != \App\Models\Role::ADMIN)
            @if($available_reports > 0)
            @cannot('take_batch', \App\Models\Report::class)
            <div class="w-full h-full flex items-center justify-center text-center">
                Items left in batch: {{count($reports)}}
            </div>
            @endcannot
            @can('take_batch', \App\Models\Report::class)
            <div class="w-full h-full flex items-center justify-between">
                <div class="w-1/4 px-2">
                    <a href="{{ route('reports.take_batch', ['batch_size' => 5]) }}" class="w-full h-full inline-flex bg-green-300 hover:bg-green-400 px-4 py-2 rounded-md justify-center">
                        Take Batch of {{$available_reports < 5 ? $available_reports : '5'}}
                    </a>
                </div>
                @if($available_reports >= 10)
                <div class="w-1/4 px-2">
                    <a href="{{ route('reports.take_batch', ['batch_size' => 10]) }}" class="w-full h-full inline-flex bg-blue-300 hover:bg-blue-400 px-4 py-2 rounded-md justify-center">
                        Take Batch of 10
                    </a>
                </div>
                @endif

                @if($available_reports >= 15)
                <div class="w-1/4 px-2">
                    <a href="{{ route('reports.take_batch', ['batch_size' => 15]) }}" class="w-full h-full inline-flex bg-yellow-300 hover:bg-yellow-400 px-4 py-2 rounded-md justify-center">
                        Take Batch of 15
                    </a>
                </div>
                @endif
                @if($available_reports >= 20)
                <div class="w-1/4 px-2">
                    <a href="{{ route('reports.take_batch', ['batch_size' => 20]) }}" class="w-full h-full inline-flex bg-red-300 hover:bg-red-400 px-4 py-2 rounded-md justify-center">
                        Take Batch of 20
                    </a>
                </div>
                @endif
            </div>
            @endcan
            @else
            <div class="w-full h-full flex items-center justify-center text-center">
                No Reports Available
            </div>
            @endif
            @endif
            <div class="py-2 align-middle inline-block min-w-full">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/5">
                                    Title
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/5">
                                    Details
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">
                                    Item
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">
                                    Priority
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">
                                    Reported By
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
                                        @if($r->detail != "") {{ $r->details }}<br> @endif
                                        @if($r->item)
                                        @switch($r->item_type)
                                        @case('App\Models\Book')
                                        <b>Language:</b> {{$r->item->language}}<br>
                                        <b>Authors:</b> {{$r->item->authors}}<br>
                                        <b>Publisher:</b> {{$r->item->publisher}}<br>
                                        <b>Publish Date:</b> {{$r->item->publish_date}}<br>
                                        <b>Pages:</b> {{$r->item->pages}}
                                        @break
                                        @case('App\Models\Series')
                                        <b>Language:</b> {{$r->item->language}}<br>
                                        <b>Authors:</b> {{$r->item->authors}}<br>
                                        <b>Publisher:</b> {{$r->item->publisher}}<br>
                                        @break
                                        @default
                                        @endswitch
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-wrap w-1/5">
                                    @switch($r->item_type)
                                    @case('App\Models\Book')

                                    <div class="flex items-center justify-evenly text-center  rounded-md bg-green-100 text-green-800">
                                        <a href="{{ route('book.edit', ['book' => $r->item_id]) }}" target="_blank" class="w-full px-1 py-1 inline-flex items-center text-xs leading-5 font-semibold">
                                            @if (isset($r->item->clean_title)) {{ ($r->item->clean_title.", Volume ".$r->item->volume_number) }} @endif
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-1/3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                        @break
                                        @case('App\Models\Series')
                                        <div class="flex items-center justify-center text-center   rounded-md bg-purple-100 text-purple-900">
                                            <a href="{{ route('series.edit', ['series' => $r->item_id]) }}" target="_blank" class="w-full px-1 py-1 inline-flex items-center text-xs leading-5 font-semibold">
                                                @if (isset($r->item->title)) {{ $r->item->title }} @endif
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-1/3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                            </a>
                                            @break
                                            @case('App\Models\BookVendor')
                                            <div class="flex items-center justify-evenly text-center rounded-md bg-pink-100 text-pink-900">
                                                <a href="{{ route('bookvendors.edit', ['bookvendor' => $r->item_id]) }}" target="_blank" class="w-full px-1 py-1 inline-flex text-xs leading-5 font-semibold ">
                                                    @if (isset($r->item->name)) {{ $r->item->name }} @endif
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-1/3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                </a>
                                                @break
                                                @default
                                                @endswitch

                                            </div>
                                </td>
                                <td class="px-6 py-4 whitespace-wrap w-1/5 text-center">
                                    @switch($r->status)
                                    @case(\App\Models\Report::STATUS_CREATED)
                                    <p class="text-sm text-gray-500 italic">CREATED</p>
                                    @break
                                    @case(\App\Models\Report::STATUS_ASSIGNED)
                                    <p class="text-sm text-gray-500 italic mb-2">ASSIGNED TO</p>

                                    @can('view_assignee', \App\Models\Report::class)
                                    @if(isset($r->assignee))
                                    <form action="{{ route('reports.remove_assignee', ['report' => $r->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-indigo-200 items-center cursor-pointer">
                                            <p class="mr-1">{{ $r->assignee->name }}</p>
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </span>
                                        </button>

                                    </form>
                                    @endif
                                    @endcan
                                    @break
                                    @default
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 whitespace-wrap w-1/5 text-center">
                                    @if($r->priority == \App\Models\Report::PRIORITY_TRESHOLDS["AWAITING_REVIEW"])
                                    <p class="text-xs bg-blue-200 text-blue-800 rounded-xl py-1 px-2">Awaiting Review</p>
                                    @endif
                                    @if($r->priority >= \App\Models\Report::PRIORITY_TRESHOLDS["MINOR_ISSUES"] && $r->priority < \App\Models\Report::PRIORITY_TRESHOLDS["IMPORTANT_ISSUES"]) <p class="text-xs bg-yellow-200 text-yellow-800 rounded-xl py-1 px-2">Minor Issue(s)</p>
                                        @endif
                                        @if($r->priority >= \App\Models\Report::PRIORITY_TRESHOLDS["IMPORTANT_ISSUES"] && $r->priority < \App\Models\Report::PRIORITY_TRESHOLDS["SEVERE_ISSUES"]) <p class="text-xs bg-orange-200 text-orange-800 rounded-xl py-1 px-2">Important Issue(s)</p>

                                            @endif
                                            @if($r->priority >= \App\Models\Report::PRIORITY_TRESHOLDS["SEVERE_ISSUES"])
                                            <p class="text-xs bg-red-200 text-red-800 rounded-xl py-1 px-2">Severe Issue(s)</p>
                                            @endif
                                </td>
                                <td class="px-6 py-4 whitespace-wrap w-1/5">
                                    @if($r->reporter_id == null)
                                    <p class="text-gray-600">System</p>
                                    @else
                                    <p class="text-gray-600">{{$r->reporter->name}}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 w-1/5">
                                    <form action="{{ route('reports.complete', ['report' => $r->id]) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="text-indigo-600 hover:text-red-900">Mark as Complete</button>
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