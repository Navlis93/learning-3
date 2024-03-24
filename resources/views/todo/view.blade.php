<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Todo item') }}
        </h3>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg " style="padding: 25px">
                <div class="">
                    
                    <form method="POST" action="{{ route('todo.update', ['id' => $item->id]) }}">
                        @csrf
                        <div class="col-span-full">
                            <div>
                                <x-input-label for="text" :value="__('Todo')" />
                                <x-text-input id="text" class="block mt-1 w-full" type="text" name="text" :value="old('text') ?? $item->text" />
                                <x-input-error :messages="$errors->get('text')" class="mt-2" />
                            </div>
                        </div>
                        <div>
                            <div>
                                <x-input-label for="text" :value="__('Status')" />
                                <input type="checkbox" name="status" value="1" {{$item->status == 1 ? 'checked="true"' : ''}}>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>
                        <hr>

                        <div>
                            <x-input-label for="tags[]" :value="_('Tags')" />
                            @foreach($tags as $tag)
                                <input type="checkbox" id="tag-{{$tag->id}}" name="tags[]" value="{{$tag->id}}" {{!$item->tags->filter( function ($el) use ($tag) {return $el->id == $tag->id;})->isEmpty()? 'checked="true"' : ''}}> <label for="tag-{{$tag->id}}">{{ $tag->name }}</label>
                            @endforeach
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                        <x-primary-button class="ms-4">Save</x-primary-button>
                        </div>
                    </form>
                    
                    <hr>

                   
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
