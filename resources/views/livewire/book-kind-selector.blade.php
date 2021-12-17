<div>
    <x-jet-label for="kind" value="{{ __('Kind') }}" />
    <div class="flex items-stretch mt-1">
        <select name="kind"
            class="form-select appearance-none block w-full px-3 py-2 text-base font-normal text-gray-700
                                        bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300
                                        rounded-l-md transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-black focus:outline-none"
            aria-label="Series Kind">
            @if ($kinds)
                @foreach ($kinds as $kind)
                    <option value="{{ $kind->name }}" @if (isset($givenKind) && $givenKind == $kind->name) selected @endif>{{ $kind->name }}</option>
                @endforeach
            @endif
        </select>
        <button type="button" wire:click='toggleModal' class="h-full bg-gray-800 text-white px-3 py-2 rounded-r-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </button>
    </div>

    <div class="{{ $modalOpen ? '' : 'hidden' }} fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!--
      Background overlay, show/hide based on modal state.

      Entering: "ease-out duration-300"
        From: "opacity-0"
        To: "opacity-100"
      Leaving: "ease-in duration-200"
        From: "opacity-100"
        To: "opacity-0"
    -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!--
      Modal panel, show/hide based on modal state.

      Entering: "ease-out duration-300"
        From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        To: "opacity-100 translate-y-0 sm:scale-100"
      Leaving: "ease-in duration-200"
        From: "opacity-100 translate-y-0 sm:scale-100"
        To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    -->
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex-col sm:items-start">
                        <div class="w-full flex items-center">
                            <input wire:model="kindInput" type="text" class="w-full rounded-l-md placeholder:italic"
                                placeholder="Kind Name..">
                            <button wire:click="addKind" type="button"
                                class="rounded-r-md text-white bg-gray-800 hover:bg-black px-4 py-2">Add</button>
                        </div>
                        <div class="mt-2 flex-col justify-between items-center">
                            @if ($kinds)
                                @foreach ($kinds as $kind)
                                    <div
                                        class="flex py-2 px-4 items-center justify-between w-full border-b border-gray-200">
                                        <h1>{{ $kind->name }}</h1>
                                        <button wire:click="deleteKind({{ $kind->id }})" type="button"
                                            class="text-red-400">Delete</button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click='toggleModal'
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleModal() {
            let modal = document.getElementById('add_kind_modal');
            modal.classList.toggle('hidden');
        }
    </script>
</div>
