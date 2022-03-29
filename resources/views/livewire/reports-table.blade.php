<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="py-2 align-middle inline-block min-w-full">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">
                                HL
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/5">
                                Title
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/5">
                                Details
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">
                                Item
                            </th>
                            @if (auth()->user()->role_id == \App\Models\Role::ADMIN)
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">
                                    Status
                                </th>
                            @endif
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">
                                Reported By
                            </th>
                            <th scope="col" class="relative px-6 py-3 w-1/5">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($reports as $r)
                            <tr id="row-{{ $r->id }}" class="row_select">
                                <td class="px-6 py-4 whitespace-wrap w-12">
                                    <button
                                        onclick="document.querySelectorAll('.row_select').forEach(el => el.classList.remove('bg-yellow-100'));document.querySelector('#row-{{ $r->id }}').classList.toggle('bg-yellow-100');">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                        </svg></button>
                                </td>
                                <td class="px-6 py-4 whitespace-wrap w-2/5">
                                    <div class="flex mb-2">
                                        @if ($r->priority == \App\Models\Report::PRIORITY_TRESHOLDS['AWAITING_REVIEW'])
                                            <p class="text-xs bg-blue-200 text-blue-800 rounded-xl py-1 px-2">Awaiting
                                                Review</p>
                                        @endif
                                        @if ($r->priority >= \App\Models\Report::PRIORITY_TRESHOLDS['MINOR_ISSUES'] && $r->priority < \App\Models\Report::PRIORITY_TRESHOLDS['IMPORTANT_ISSUES'])
                                            <p class="text-xs bg-sky-200 text-sky-800 rounded-xl py-1 px-2">Minor
                                                Issue(s)</p>
                                        @endif
                                        @if ($r->priority >= \App\Models\Report::PRIORITY_TRESHOLDS['IMPORTANT_ISSUES'] && $r->priority < \App\Models\Report::PRIORITY_TRESHOLDS['SEVERE_ISSUES'])
                                            <p class="text-xs bg-yellow-200 text-yellow-800 rounded-xl py-1 px-2">
                                                Important
                                                Issue(s)</p>
                                        @endif
                                        @if ($r->priority >= \App\Models\Report::PRIORITY_TRESHOLDS['SEVERE_ISSUES'] && $r->priority < \App\Models\Report::PRIORITY_TRESHOLDS['COLLECT_DATA'])
                                            <p class="text-xs bg-orange-200 text-orange-800 rounded-xl py-1 px-2">Severe
                                                Issue(s)
                                            </p>
                                        @endif
                                        @if ($r->priority >= \App\Models\Report::PRIORITY_TRESHOLDS['COLLECT_DATA'])
                                            <p class="text-xs bg-red-200 text-red-800 rounded-xl py-1 px-2">No Data
                                                Issue(s)
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $r->title }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-wrap w-1/5">
                                    <div class="text-sm font-medium text-gray-900">
                                        @if ($r->detail != '')
                                            {{ $r->details }}<br>
                                        @endif
                                        @if ($r->item)
                                            @switch($r->item_type)
                                                @case('App\Models\Book')
                                                    <b>Language:</b> {{ $r->item->language }}<br>
                                                    <b>Authors:</b> {{ $r->item->authors }}<br>
                                                    <b>Publisher:</b> {{ $r->item->publisher }}<br>
                                                    <b>Publish Date:</b> {{ $r->item->publish_date }}<br>
                                                    <b>Pages:</b> {{ $r->item->pages }}
                                                @break

                                                @case('App\Models\Series')
                                                    <b>Authors:</b> {{ $r->item->authors }}<br>
                                                @break

                                                @default
                                            @endswitch
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-wrap w-1/5">
                                    @switch($r->item_type)
                                        @case('App\Models\Book')
                                            <div
                                                class="flex items-center justify-evenly text-center  rounded-md bg-green-100 text-green-800">
                                                <a href="{{ route('book.edit', ['book' => $r->item_id]) }}" target="_blank"
                                                    class="w-full px-1 py-1 inline-flex items-center text-xs leading-5 font-semibold">
                                                    @if (isset($r->item->clean_title))
                                                        {{ $r->item->clean_title . ', Volume ' . $r->item->volume_number }}
                                                    @endif
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-1/3 h-5 w-5" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                </a>
                                            </div>
                                        @break

                                        @case('App\Models\Series')
                                            <div
                                                class="flex items-center justify-center text-center   rounded-md bg-purple-100 text-purple-900">
                                                <a href="{{ route('series.edit', ['series' => $r->item_id]) }}"
                                                    target="_blank"
                                                    class="w-full px-1 py-1 inline-flex items-center text-xs leading-5 font-semibold">
                                                    @if (isset($r->item->title))
                                                        {{ $r->item->title }}
                                                    @endif
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-1/3 h-5 w-5" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                </a>
                                            </div>
                                        @break

                                        @case('App\Models\BookVendor')
                                            <div
                                                class="flex items-center justify-evenly text-center rounded-md bg-pink-100 text-pink-900">
                                                <a href="{{ route('bookvendors.edit', ['bookvendor' => $r->item_id]) }}"
                                                    target="_blank"
                                                    class="w-full px-1 py-1 inline-flex text-xs leading-5 font-semibold ">
                                                    @if (isset($r->item->name))
                                                        {{ $r->item->name }}
                                                    @endif
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-1/3 h-5 w-5" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                </a>
                                            </div>
                                        @break

                                        @default
                                    @endswitch
                                </td>

                                @if (auth()->user()->role_id == \App\Models\Role::ADMIN)
                                    <td class="px-6 py-4 whitespace-wrap w-1/5 text-center">
                                        @switch($r->status)
                                            @case(\App\Models\Report::STATUS_CREATED)
                                                <p class="text-sm text-gray-500 italic">CREATED</p>
                                            @break

                                            @case(\App\Models\Report::STATUS_DROPPED)
                                                <p class="text-sm text-gray-500 italic">DROPPED BY {{ $r->assignee->name }}</p>
                                            @break

                                            @case(\App\Models\Report::STATUS_ASSIGNED)
                                                <p class="text-sm text-gray-500 italic mb-2">ASSIGNED TO</p>

                                                @if (isset($r->assignee))
                                                    <form
                                                        action="{{ route('reports.remove_assignee', ['report' => $r->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button
                                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-indigo-200 items-center cursor-pointer">
                                                            <p class="mr-1">{{ $r->assignee->name }}</p>
                                                            <span>
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </span>
                                                        </button>

                                                    </form>
                                                @endif
                                            @break

                                            @default
                                        @endswitch
                                    </td>
                                @endif
                                <td class="px-6 py-4 whitespace-wrap w-1/5">
                                    @if ($r->reporter_id == null)
                                        <p class="text-gray-600">System</p>
                                    @else
                                        <p class="text-gray-600">User</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 w-1/5">
                                    <div class="h-full w-full flex flex-col items-center justify-between">
                                        <div>
                                            <form action="{{ route('reports.complete', ['report' => $r->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="text-indigo-600 hover:text-red-900">Mark as
                                                    Complete</button>
                                            </form>
                                        </div>
                                        <p>or</p>
                                        @if (auth()->user()->role_id == \App\Models\Role::ADMIN)
                                        <div>
                                            <form action="{{ route('reports.destroy', ['report' => $r->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        </div>
                                        @else
                                        <div>
                                            <form action="{{ route('reports.drop', ['report' => $r->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Drop</button>
                                            </form>
                                        </div>
                                        @endif
                                    </div>
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
