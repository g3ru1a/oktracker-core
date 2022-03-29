<div>
    <div class="flex items-center">
        <label>Compare to </label>
        <select wire:model="dayDiff" class="mx-1 rounded-md">
            <option value="1">Yesterday</option>
            <option value="7">Last Week</option>
            <option value="30">Last 30 Days</option>
            <option value="60">Last 60 Days</option>
            <option value="90">Last 90 Days</option>
            <option value="365">Last Year</option>
        </select>
    </div>
    <div class="flex w-full items-stretch justify-evenly">

        <div class="w-1/3 p-2">
            <div class="bg-white h-full px-8 py-4  rounded-md flex items-center justify-between">
                <div class="mr-4 bg-yellow-400 rounded-lg p-2 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div class="mx-2">
                    <h2 class="font-semibold text-2xl">{{ $newBooksThisMonth }}</h2>
                    <p class="text-gray-500">New Books {{ $prettyDayDiff }}</p>
                </div>
                <div class="ml-4 flex items-center text-{{ $bookPercDiff < 0 ? 'red' : 'green' }}-600">
                    <p class="text-lg font-semibold">{{ $bookPercDiff }}%</p><svg xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 transform {{ $bookPercDiff < 0 ? 'rotate-180' : 'rotate-0' }}" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7l4-4m0 0l4 4m-4-4v18" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="w-1/3 p-2">
            <div class="bg-white h-full px-8 py-4 rounded-md flex items-center justify-between">
                <div class="mr-4 bg-blue-400 rounded-lg p-2 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 transform rotate-90" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                    </svg>
                </div>
                <div class="mx-2">
                    <h2 class="font-semibold text-2xl">{{ $newSeriesThisMonth }}</h2>
                    <p class="text-gray-500">New Series {{ $prettyDayDiff }}</p>
                </div>
                <div class="ml-4 flex items-center text-{{ $seriesPercDiff < 0 ? 'red' : 'green' }}-600">
                    <p class="text-lg font-semibold">{{ $seriesPercDiff }}%</p><svg xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 transform {{ $seriesPercDiff < 0 ? 'rotate-180' : 'rotate-0' }}" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7l4-4m0 0l4 4m-4-4v18" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="w-1/3 p-2">
            <div class="bg-white h-full  px-8 py-4  rounded-md flex items-center justify-between">
                <div class="mr-4 bg-red-400 rounded-lg p-2 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="mx-2">
                    <h2 class="font-semibold text-2xl">{{ $newReportsThisMonth }}</h2>
                    <p class="text-gray-500">New Reports {{ $prettyDayDiff }}</p>
                </div>
                <div class="ml-4 flex items-center text-{{ $reportsPercDiff < 0 ? 'red' : 'green' }}-600">
                    <p class="text-lg font-semibold">{{ $reportsPercDiff }}%</p><svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 transform {{ $reportsPercDiff < 0 ? 'rotate-180' : 'rotate-0' }}" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7l4-4m0 0l4 4m-4-4v18" />
                    </svg>
                </div>
            </div>
        </div>



    </div>

</div>
