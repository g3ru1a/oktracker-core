<div class="w-1/2 ml-1 relative">
    <x-jet-label for="series_id" value="{{ __('Series').($series_id == null ? ' (Missing)' : ' ✔️') }}" />
    <input required wire:model="series_id" name="series_id" type="hidden" />

    <div class="flex items-stretch">
        <x-jet-input wire:model="lookup" disabled="{{ $series_id != null }}" name=""
         placeholder="E.g. Monkey High" type="text"
          class="focus:ring-0 mt-1 block w-full {{ $open_results ? 'border-b-none' : ''}} {{ $series_id != null ? 'bg-gray-100 text-gray-600 rounded-r-none' : ''}}"/>
        @if($series_id != null)
        <button wire:click="clearSelection" type="button" class="mt-1 rounded-md bg-red-400 hover:bg-red-500 text-white px-1 py-2 h-full border border-l-0 rounded-l-none border-gray-300">Clear</button>
        @endif
    </div>

    @if($open_results)
    <div class="absolute w-full pt-2 flex-col items-center justify-start bg-gray-100 rounded-b-md border border-gray-300 max-h-48 overflow-x-hidden overflow-y-scroll">
        @if(count($series) == 0)
        <div class="px-2 py-1">
            <p>{{ __('No Series Found.')}}</p>
        </div>
        @endif
        @foreach($series as $s)
        <div wire:click="selectSeries({{$s->id}})" class="px-2 py-1 hover:bg-black hover:text-white cursor-pointer">
            <p>{{$s->title}}</p>
        </div>
        @endforeach
    </div>
    @endif
</div>