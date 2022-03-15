<x-app-layout>
    @section('title', 'Create Book')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('book.index') }}" class="font-normal cursor-pointer hover:underline">{{ __('Books') }}</a> / {{ __('Create') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-jet-action-section title="Pointers" description="Follow all the pointers with care.<br><br>
            <b>Title</b> - Copy the book title as you find it, doesn't matter if it includes extra information in brackets or volume numbers<br><br>
            <b>Clean Title</b> - The book title without any volume numbers. Keep the format i.e 'Title Vol 3: subtitle' will be 'Title: subtitle' or 'Title!, Vol 3' will be 'Title!' <br><br>
            <b>ISBN 10 & 13</b> - Make sure the ISBNs match this exact volume and not a box-set or other books. In cases where the ISBN links to a box-set and you can't find any other ISBN for the book, keep it as is.<br><br>
            <b>Synopsis</b> - Copy the synopsis of this volume if there is any, otherwise use the series summary. It supports HTML markup for bolding, italics and underlines.<br><br>
            <b>Publisher</b> - Make sure to include the full company name (i.e 'Viz Media LLC').<br><br>
            <b>Language</b> - Use ISO 639-1 Language Codes (https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes).<br><br>
            <b>Authors</b> - Includes authors and/or artists that worked on this book (Does not include translators, editors etc.). Separate names with a ','. When working with a foreign book, write the name in the english alphabet if possible (i.e. 暁なつめ will be written as Natsume Akatsuki)<br><br>
            <b>Publish Date</b> - Format as Year/Month/Day. If you can't find the publish date, leave empty.<br><br>
            <b>Pages</b> - If you can't find the page number, leave empty.<br><br>
            <b>Volume Number</b> - Use the volume number from the <i>Title</i> or <i>Cover</i>. If the book is not part of a multi-volume series, leave empty.<br><br>
            <b>Series</b> - Look up the series, it should match the <i>Clean Title</i>. If you cant find any, create a series first and then complete the book data.<br><br>
            <b>Binding</b> - Check the Binding type, 'Special' is the catch all option, so if the available selection doesn't fit, use 'Special'<br><br>
            <b>Cover</b> - Make sure the cover matches the Volume Number <small>(if applicable)</small> and the Language & Publisher. Get the highest quality possible thats under 2mb in filesize.<br><br>">
                <x-slot name="content">

                    <form action="{{ route('book.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="">
                            <x-jet-label for="title" value="{{ __('Title') }}" />
                            <x-jet-input required name="title" placeholder="E.g. Monkey High! Vol. 6" type="text" class="mt-1 block w-full" autocomplete="title" />
                            <x-jet-input-error for="title" class="mt-2" />
                        </div>
                        <div class="">
                            <x-jet-label for="clean_title" value="{{ __('Clean Title') }}" />
                            <x-jet-input required name="clean_title" placeholder="E.g. Monkey High!" type="text" class="mt-1 block w-full" autocomplete="title" />
                            <x-jet-input-error for="title" class="mt-2" />
                        </div>

                        <div class="mt-2 flex">
                            <div class="w-1/2 mr-1">
                                <x-jet-label for="isbn_10" value="{{ __('ISBN 10') }}" />
                                <x-jet-input required name="isbn_10" placeholder="E.g. 1642731447" type="text" class="mt-1 block w-full" autocomplete="isbn_10" />
                                <x-jet-input-error for="isbn_10" class="mt-2" />
                            </div>
                            <div class="w-1/2 ml-1">
                                <x-jet-label for="isbn_13" value="{{ __('ISBN 13') }}" />
                                <x-jet-input required name="isbn_13" placeholder="E.g. 9781642731446" type="text" class="mt-1 block w-full" autocomplete="isbn_13" />
                                <x-jet-input-error for="isbn_13" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-2">
                            <x-jet-label for="synopsis" value="{{ __('Synopsis') }} ({{ __('Optional') }})" />
                            <textarea name="synopsis" id="synopsis" placeholder="Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultrices nibh ut justo auctor mollis. Nulla varius consectetur nunc, sed dapibus diam." class="w-full placeholder:italic border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"></textarea>
                        </div>

                        <div class="mt-2 flex">
                            <div class="w-1/4 mr-1">
                                <x-jet-label for="publisher" value="{{ __('Publisher') }} ({{ __('Optional') }})" />
                                <x-jet-input name="publisher" placeholder="E.g. Kodansha" type="text" class="mt-1 block w-full" autocomplete="publisher" />
                                <x-jet-input-error for="publisher" class="mt-2" />
                            </div>
                            <div class="w-1/4 mx-1">
                                <x-jet-label for="language" value="{{ __('Language') }}" />
                                <x-jet-input required name="language" placeholder="E.g. en" type="text" class="mt-1 block w-full" autocomplete="language" />
                                <x-jet-input-error for="language" class="mt-2" />
                            </div>
                            <div class="w-1/2 ml-1">
                                <x-jet-label for="authors" value="{{ __('Authors') }} ({{ __('Optional') }})" />
                                <x-jet-input name="authors" placeholder="E.g. Seiso, Mark" type="text" class="mt-1 block w-full" autocomplete="authors" />
                                <x-jet-input-error for="authors" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-2 flex">
                            <div class="w-1/3 mr-1">
                                <x-jet-label for="publish_date" value="{{ __('Publish Date') }} ({{ __('Optional') }})" />
                                <x-jet-input name="publish_date" placeholder="E.g. Seiso, Mark" type="date" class="mt-1 block w-full" autocomplete="publish_date" />
                                <x-jet-input-error for="publish_date" class="mt-2" />
                            </div>
                            <div class="w-1/3 ml-1">
                                <x-jet-label for="pages" value="{{ __('Pages') }} ({{ __('Optional') }})" />
                                <x-jet-input name="pages" placeholder="E.g. 180" type="number" class="mt-1 block w-full" autocomplete="pages" />
                                <x-jet-input-error for="pages" class="mt-2" />
                            </div>
                            <div class="w-1/3 ml-1">
                                <x-jet-label for="volume_number" value="{{ __('Volume Number') }} ({{ __('Optional') }})" />
                                <x-jet-input name="volume_number" placeholder="E.g. 10" type="number" class="mt-1 block w-full" autocomplete="volume_number" />
                                <x-jet-input-error for="volume_number" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-2 flex">
                            <livewire:series-select />
                            <div class="w-1/2 ml-1">
                                <x-jet-label for="binding" value="{{ __('Binding') }}" />
                                <select name="binding" class=" form-select appearance-none w-full px-3 py-2 text-base font-normal text-gray-700
                                        bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300
                                        rounded-md transition ease-in-out m-0 mt-1 focus:text-gray-700 focus:bg-white focus:border-black focus:outline-none" aria-label="Default select example">
                                    <option value="paperback">Paperback</option>
                                    <option value="hardback">Hardback</option>
                                    <option value="special">Special</option>
                                </select>
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

                    @if ($errors->any())
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

        function openInput() {
            file_input.click();
        }

        function handleFiles() {
            let [file] = file_input.files;
            if (file) {
                preview.src = URL.createObjectURL(file);
            }
            hideEmptyInputData();
        }

        function hideEmptyInputData() {
            empty_file_input_data.classList.add('hidden');
            preview.classList.remove('hidden');
        }
    </script>
</x-app-layout>