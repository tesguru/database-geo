<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-white">Users</h1>
                    <p class="text-gray-400 mt-1">All registered users with their activity</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.users') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                        Users
                    </a>
                    <a href="{{ route('admin.sales') }}" class="inline-flex items-center px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition text-sm font-medium">
                        Sales
                    </a>
                    <a href="{{ route('admin.populations') }}" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition text-sm font-medium">
                        City Populations
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-8 p-4 rounded-xl bg-emerald-900/30 border border-emerald-700 text-emerald-300">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-gray-900 rounded-2xl border border-gray-800 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-800">
                        <thead class="bg-gray-800/60">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Searches</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Logins</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Joined</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-900 divide-y divide-gray-800">
                            @forelse($users as $user)
                            <tr class="hover:bg-gray-800/40 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center mr-3">
                                            <span class="text-white font-bold text-sm">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-white">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->is_admin)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-purple-500/10 border border-purple-500/30 text-xs font-semibold text-purple-400">Admin</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-gray-800 border border-gray-700 text-xs font-semibold text-gray-400">Member</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-white font-semibold">{{ number_format($user->search_count) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-white font-semibold">{{ number_format($user->login_count) }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-400">{{ $user->created_at?->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    No users registered yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($users->hasPages())
            <div class="mt-6">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
