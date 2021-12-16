<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('book.index') }}"
                class="font-normal cursor-pointer hover:underline">{{ __('Books') }}</a> / <span
                class="font-normal">{{ __('Edit') }} </span>/ {{ $book->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-jet-action-section title="Pointers"
                description="This should be a list of pointers but I dont know what to write rn">
                <x-slot name="content">

                    <form action="{{ route('book.update', ['book' => $book->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="">
                            <x-jet-label for="title" value="{{ __('Title') }}" />
                            <x-jet-input required value="{{$book->title}}" name="title" placeholder="E.g. Monkey High, Vol. 6" type="text" class="mt-1 block w-full" autocomplete="title" />
                            <x-jet-input-error for="title" class="mt-2" />
                        </div>

                        <div class="mt-2 flex">
                            <div class="w-1/2 mr-1">
                                <x-jet-label for="isbn_10" value="{{ __('ISBN 10') }}" />
                                <x-jet-input required value="{{$book->isbn_10}}" name="isbn_10" placeholder="E.g. 1642731447" type="text" class="mt-1 block w-full" autocomplete="isbn_10" />
                                <x-jet-input-error for="isbn_10" class="mt-2" />
                            </div>
                            <div class="w-1/2 ml-1">
                                <x-jet-label for="isbn_13" value="{{ __('ISBN 13') }}" />
                                <x-jet-input required value="{{$book->isbn_13}}" name="isbn_13" placeholder="E.g. 9781642731446" type="text" class="mt-1 block w-full" autocomplete="isbn_13" />
                                <x-jet-input-error for="isbn_13" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-2 flex">
                            <div class="w-1/3 mr-1">
                                <x-jet-label for="publish_date" value="{{ __('Publish Date') }} ({{ __('Optional') }})" />
                                <x-jet-input value="{{$book->publish_date}}" name="publish_date" placeholder="E.g. Seiso, Mark" type="date" class="mt-1 block w-full" autocomplete="publish_date" />
                                <x-jet-input-error for="publish_date" class="mt-2" />
                            </div>
                            <div class="w-1/3 mx-1">
                                <x-jet-label for="pages" value="{{ __('Pages') }} ({{ __('Optional') }})" />
                                <x-jet-input value="{{$book->pages}}" name="pages" placeholder="E.g. 180" type="number" class="mt-1 block w-full" autocomplete="pages" />
                                <x-jet-input-error for="pages" class="mt-2" />
                            </div>
                            <div class="w-1/3 ml-1">
                                <x-jet-label for="volume_number" value="{{ __('Volume Number') }} ({{ __('Optional') }})" />
                                <x-jet-input value="{{$book->volume_number}}" name="volume_number" placeholder="E.g. 10" type="number" class="mt-1 block w-full" autocomplete="volume_number" />
                                <x-jet-input-error for="volume_number" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-2 flex">
                            <div class="w-full ml-1">
                                <x-jet-label for="series_id" value="{{ __('Series') }}" />
                                <select name="series_id"
                                    class="form-select appearance-none block w-full px-3 py-1.5 text-base font-normal text-gray-700
                                        bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300
                                        rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-black focus:outline-none"
                                    aria-label="Default select example">
                                    <option @if($book->series_id == null) selected @endif disabled>Select a Series</option>
                                    @foreach($series as $s)
                                    <option value="{{$s->id}}" @if($book->series_id == $s->id) selected @endif>{{$s->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-2 flex">
                            <div class="w-full ml-1">
                                <x-jet-label for="oneshot" value="{{ __('Oneshot') }} ({{ __('Optional') }})" />
                                <label class="inline-flex items-center mt-3">
                                    <input name="is_oneshot" type="checkbox" class="form-checkbox h-5 w-5 text-gray-600" @if($book->oneshot) checked @endif><span class="ml-2 text-gray-700">This is the only book in the series</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-2">
                            <x-jet-label for="contributions"
                                value="{{ __('Cover Photo') }} ({{ __('Optional') }})" />
                            <div onclick="openInput()"
                                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                @if($book->cover_url != '/missing_cover.png')
                                <img src="{{$book->cover_url}}" alt="preview" id="cover-preview" class="max-h-64">
                                @else
                                <img src="" alt="preview" id="cover-preview" class="hidden max-h-64">
                                @endif
                                <div id="empty_file_input" class="space-y-1 text-center {{$book->cover_url != '/missing_cover.png' ? 'hidden' : null}}">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48" aria-hidden="true">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex justify-center text-sm text-gray-600">
                                        <label for="file-upload"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
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
                            <button
                                class="w-full text-center py-3 bg-black text-white rounded-md">{{ __('Submit') }}</button>
                        </div>
                    </form>
                    <div class="p-2">
                        <form action="{{ route('book.destroy', ['book' => $book->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="float-right text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </div>
                        @if($errors->any())
                            <div class="mt-2 rounded-md bg-red-400 px-4 py-2 text-white">
                                {{ implode('', $errors->all(':message')) }}
                            </div>
                        @endif

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
