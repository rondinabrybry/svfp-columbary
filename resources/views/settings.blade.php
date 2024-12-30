<!-- filepath: /C:/xampp/htdocs/LARAVEL/columbary/svfp/resources/views/settings.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-white bg-gray-800 rounded-lg p-4">
            <p class="mb-4">Select Any Levels from what Floor and Rack you want to disable in the pre-selling.</p>
            <form method="POST" action="{{ route('settings.update') }}">
                @csrf
                @method('PUT')

                <div class="flex space-x-4 mb-4">
                    @foreach($data as $floor => $racks)
                        <button type="button" class="collapsible bg-gray-700 text-white font-semibold py-2 px-4 rounded text-center">
                            Floor: {{ $floor }}
                            <svg class="inline-block ml-2 w-4 h-4 transform transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    @endforeach
                </div>

                @foreach($data as $floor => $racks)
                    <div class="content hidden bg-gray-600 p-4 rounded mb-4">
                        <div class="flex flex-row">
                            @foreach($racks as $rack => $levels)
                                <div class="ml-4 mb-2">
                                    <h4 class="font-semibold text-md">{{ chr(64 + $rack) }}</h4>
                                    @foreach($levels as $level)
                                        <div class="ml-8">
                                            <input type="checkbox" id="level_{{ $floor }}_{{ $rack }}_{{ $level['level_number'] }}" name="levels[]" value="{{ $floor }}_{{ $rack }}_{{ $level['level_number'] }}" {{ $level['checked'] ? 'checked' : '' }}>
                                            <label for="level_{{ $floor }}_{{ $rack }}_{{ $level['level_number'] }}">{{ $level['level_number'] }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const collapsibles = document.querySelectorAll('.collapsible');
            const contents = document.querySelectorAll('.content');

            collapsibles.forEach((collapsible, index) => {
                collapsible.addEventListener('click', function () {
                    // Close all other contents
                    contents.forEach((content, contentIndex) => {
                        if (contentIndex !== index) {
                            content.classList.add('hidden');
                            collapsibles[contentIndex].querySelector('svg').classList.remove('rotate-180');
                        }
                    });

                    // Toggle the current content
                    contents[index].classList.toggle('hidden');
                    this.querySelector('svg').classList.toggle('rotate-180');
                });
            });
        });
    </script>

    <style>
        .rotate-180 {
            transform: rotate(180deg);
        }
    </style>
</x-app-layout>