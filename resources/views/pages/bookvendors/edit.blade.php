<x-app-layout>
    @section('title', 'Edit Book')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('bookvendors.index') }}"
                class="font-normal cursor-pointer hover:underline">{{ __('Vendor') }}</a> / <span
                class="font-normal">{{ __('Edit') }} </span>/ {{ $vendor->title }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-jet-action-section title="Pointers"
                description="This should be a list of pointers but I dont know what to write rn">
                <x-slot name="content">

                    <form action="{{ route('bookvendors.update', ['bookvendor' => $vendor->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="">
                            <x-jet-label for="name" value="{{ __('Name') }}" />
                            <x-jet-input required name="name" placeholder="E.g. Amazon UK" type="text"
                                class="mt-1 block w-full" autocomplete="name" 
                                value="{{$vendor->name}}" />
                            <x-jet-input-error for="name" class="mt-2" />
                        </div>

                        <div class="mt-2 flex">
                            <div class="w-full ml-1">
                                <x-jet-label for="is_public" value="{{ __('Public') }}" />
                                <label class="inline-flex items-center mt-3">
                                    <input name="is_public" type="checkbox" @if($vendor->public) checked @endif
                                        class="form-checkbox h-5 w-5 text-gray-600"><span
                                        class="ml-2 text-gray-700">This Vendor is shown to Users</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button
                                class="w-full text-center py-3 bg-black text-white rounded-md">{{ __('Submit') }}</button>
                        </div>
                    </form>
                    <div class="p-2">
                        <form action="{{ route('bookvendors.destroy', ['bookvendor' => $vendor->id]) }}" method="POST">
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
