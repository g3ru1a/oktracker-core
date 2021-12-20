<x-app-layout>
    @section('title', 'Create Series')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{route('series.index')}}" class="font-normal cursor-pointer hover:underline">{{ __('Series') }}</a> / {{ __('Create') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-jet-action-section title="Pointers" description="This should be a list of pointers but I dont know what to write rn">
                <x-slot  name="content">
                    
                    <form action="{{route('series.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="">
                            <x-jet-label for="title" value="{{ __('Title') }}" />
                            <x-jet-input required name="title" placeholder="E.g. Monkey High" type="text" class="mt-1 block w-full" autocomplete="title" />
                            <x-jet-input-error for="title" class="mt-2" />
                        </div>

                        <div class="mt-2">
                            <x-jet-label for="summary" value="{{ __('Summary') }} ({{ __('Optional') }})" />
                            <textarea name="summary" id="summary" placeholder="Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultrices nibh ut justo auctor mollis. Nulla varius consectetur nunc, sed dapibus diam." class="w-full placeholder:italic border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"></textarea>
                        </div>

                        <div class="mt-2 flex">
                            <div class="w-1/4 mr-1">
                                <x-jet-label for="publisher" value="{{ __('Publisher') }} ({{ __('Optional') }})" />
                                <x-jet-input name="publisher" placeholder="E.g. Kodansha" type="text" class="mt-1 block w-full" autocomplete="publisher" />
                                <x-jet-input-error for="publisher" class="mt-2" />
                            </div>
                            <div class="w-1/4 ml-1">
                                <x-jet-label for="language" value="{{ __('Language') }}" />
                                <x-jet-input required name="language" placeholder="E.g. en_US" type="text" class="mt-1 block w-full" autocomplete="language" />
                                <x-jet-input-error for="language" class="mt-2" />
                            </div>
                            <div class="w-2/4 ml-1">
                                <livewire:book-kind-selector/>
                            </div>
                        </div>

                        <div class="mt-2 flex">
                            <div class="w-1/2 mr-1">
                                <x-jet-label for="authors" value="{{ __('Authors') }} ({{ __('Optional') }})" />
                                <x-jet-input name="authors" placeholder="E.g. Seiso, Mark" type="text" class="mt-1 block w-full" autocomplete="authors" />
                                <x-jet-input-error for="authors" class="mt-2" />
                            </div>
                            <div class="w-1/2 ml-1">
                                <x-jet-label for="contributions" value="{{ __('Contributions') }} ({{ __('Optional') }})" />
                                <x-jet-input name="contributions" placeholder="E.g. Natsume Akatsuki" type="text" class="mt-1 block w-full" autocomplete="contributions" />
                                <x-jet-input-error for="contributions" class="mt-2" />
                            </div>
                        </div>


                        <div class="mt-2">
                            <x-jet-label for="contributions" value="{{ __('Cover Photo') }} ({{ __('Optional') }})" />
                            <div onclick="openInput()" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <img src="" alt="preview" id="cover-preview" class="hidden max-h-64">
                                <div id="empty_file_input" class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex justify-center text-sm text-gray-600">
                                        <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Upload a file</span>
                                        <input id="file-upload" onchange="handleFiles()" name="cover" type="file" class="hidden sr-only">
                                        </label>
                                        {{-- <p class="pl-1">or drag and drop</p> --}}
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG, JPEG up to 10MB
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button class="w-full text-center py-3 bg-black text-white rounded-md">{{ __('Submit') }}</button>
                        </div>
                    </form>

                </x-slot>
            </x-jet-action-section>
        </div>
    </div>
    <script>
        const preview = document.getElementById('cover-preview');
        const file_input = document.getElementById('file-upload');
        const empty_file_input_data = document.getElementById('empty_file_input');
    
        function openInput(){
            file_input.click();
        }

        function handleFiles(){
            let [file] = file_input.files;
            if(file){
                preview.src = URL.createObjectURL(file);
            }
            hideEmptyInputData();
        }

        function hideEmptyInputData(){
            empty_file_input_data.classList.add('hidden');
            preview.classList.remove('hidden');
        }
    </script>
</x-app-layout>