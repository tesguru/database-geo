<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg border border-gray-800">
                <div class="p-6 text-gray-300">
                    {{ __("You're logged in!") }}
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center mt-4 px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-500 text-white text-sm font-semibold rounded-lg hover:from-indigo-600 hover:to-purple-600 transition">
                        Go to Admin Dashboard →
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
