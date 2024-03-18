<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Todo items') }}
        </h3>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg " style="padding: 25px">
                <div class="">
                    <form method="POST" action="{{ route('todo.store') }}">
                        @csrf
                        <div class="col-span-full">
                        <div>
                            <x-input-label for="text" :value="__('New Todo')" />
                            <x-text-input id="text" class="block mt-1 w-full" type="text" name="text" :value="old('text')" />
                            <x-input-error :messages="$errors->get('text')" class="mt-2" />
                        </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                        <x-primary-button class="ms-4">Save</x-primary-button>
                        </div>
                    </form>

                    <hr>

                    @foreach($todoitems as $item)
                    <p>
                        {{$item->text}} <a href="{{ route('todo.view', ['id' => $item->id]) }}">Подробнее</a>
                    </p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
