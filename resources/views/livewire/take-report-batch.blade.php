<div class="py-12">
    <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
        <div class="py-2 align-middle inline-block min-w-full">
            <form action="{{route('reports.take_batch')}}" method="POST">
                @csrf
                <div class="bg-white rounded-md shadow-md px-4 py-2">
                    <h1 class="text-lg font-semibold mb-2">{{ __('Configure Batch') }}</h1>

                    <div class="my-2">
                        <p class="font-bold">Report Type</p>

                        <div class="mb-2 flex flex-row flex-wrap justify-center items-center">
                            <div class="px-2 flex flex-row-reverse items-center justify-center">
                                <label for="COLLECT"
                                    class="pl-2 cursor-pointer">{{ __('COLLECT DATA') }}({{ $collect_data_count }})</label>
                                <input wire:model="checked_collect_data" id="COLLECT" name="COLLECT" type="checkbox"
                                    class="cursor-pointer">
                            </div>
                            <div class="px-2 flex flex-row-reverse items-center justify-center">
                                <label for="USER_REPORT"
                                    class="pl-2  cursor-pointer">{{ __('USER REPORTED') }}</label>
                                <input wire:model="checked_user_reported" id="USER_REPORT" name="USER_REPORT"
                                    type="checkbox" class="cursor-pointer">
                            </div>
                        </div>
                        <p class="font-bold">Report Priority</p>
                        <div class="flex flex-row flex-wrap justify-evenly items-center">
                            <div class="px-2 flex flex-row-reverse items-center justify-center">
                                <label for="SEVERE"
                                    class="pl-2  cursor-pointer">{{ __('SEVERE') }}({{ $priority_severe_count }})</label>
                                <input id="SEVERE" name="SEVERE" type="checkbox"
                                    class="cursor-pointer disabled:bg-gray-300"
                                    @if ($priority_severe_count == 0) disabled @endif>
                            </div>
                            <div class="px-2 flex flex-row-reverse items-center justify-center">
                                <label for="IMPORTANT"
                                    class="pl-2  cursor-pointer">{{ __('IMPORTANT') }}({{ $priority_important_count }})</label>
                                <input id="IMPORTANT" name="IMPORTANT" type="checkbox"
                                    class="cursor-pointer disabled:bg-gray-300"
                                    @if ($priority_important_count == 0) disabled @endif>
                            </div>
                            <div class="px-2 flex flex-row-reverse items-center justify-center">
                                <label for="MINOR"
                                    class="pl-2  cursor-pointer">{{ __('MINOR') }}({{ $priority_minor_count }})</label>
                                <input id="MINOR" name="MINOR" type="checkbox"
                                    class="cursor-pointer disabled:bg-gray-300"
                                    @if ($priority_minor_count == 0) disabled @endif>
                            </div>
                            <div class="px-2 flex flex-row-reverse items-center justify-center">
                                <label for="CLEAN"
                                    class="pl-2  cursor-pointer">{{ __('CLEAN') }}({{ $priority_clean_count }})</label>
                                <input id="CLEAN" name="CLEAN" type="checkbox"
                                    class="cursor-pointer disabled:bg-gray-300"
                                    @if ($priority_clean_count == 0) disabled @endif>
                            </div>
                            <div class="px-2 flex flex-row-reverse items-center justify-center">
                                <label for="ANY"
                                    class="pl-2  cursor-pointer">{{ __('ANY') }}({{ $priority_any_count }})</label>
                                <input id="ANY" name="ANY" type="checkbox" class="cursor-pointer disabled:bg-gray-300"
                                    @if ($priority_any_count == 0) disabled @endif>
                            </div>
                        </div>
                    </div>
                    <div class="my-2">
                        <p class="font-bold">Batch Size</p>

                        <div class="mb-2 flex flex-col flex-wrap justify-start items-center">
                            <p
                                class="text-center text-md {{ $batch_size > 10? ($batch_size > 15? ($batch_size > 20? ($batch_size > 25? ($batch_size > 30? ($batch_size > 40? 'text-red-700': 'text-red-500'): 'text-orange-400'): 'text-yellow-400'): 'text-purple-500'): 'text-blue-500'): 'text-gray-500' }}">
                                {{ $batch_size }} Reports</p>
                            <input wire:model="batch_size" type="range" min="5" max="50" value="20" name="size"
                                class="{{ $batch_size > 10? ($batch_size > 15? ($batch_size > 20? ($batch_size > 25? ($batch_size > 30? ($batch_size > 40? 'bg-red-600': 'bg-red-400'): 'bg-orange-300'): 'bg-yellow-300'): 'bg-purple-400'): 'bg-blue-400'): 'bg-gray-400' }} rounded-lg overflow-hidden appearance-none h-3 w-full">
                        </div>
                    </div>

                    <div class="mt-4 mb-2">
                        <button
                            class="w-full h-full bg-orange-400 hover:bg-orange-600 focus:bg-orange-700 text-white px-4 py-2 rounded-md transition-all duration-300">TAKE
                            BATCH</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @media screen and (-webkit-min-device-pixel-ratio: 0) {

        input[type="range"]::-webkit-slider-thumb {
            width: 15px;
            -webkit-appearance: none;
            appearance: none;
            height: 15px;
            cursor: ew-resize;
            background: #FFF;
            box-shadow: -405px 0 0 400px #00000086;
            border-radius: 50%;

        }
    }

</style>
