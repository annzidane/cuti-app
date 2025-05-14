<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daftar Cuti') }}
            </h2>
            <a href="{{ route('leaves.create') }}" class="bg-gray-400 text-black px-4 py-2 rounded hover:bg-gray-500">
                + Create Cuti
            </a>
        </div>
    </x-slot>

    @if (session()->has('success'))
        <div class="pt-3">
            <div class="flex items-center bg-green-100 border border-green-400 text-green-700 px-6 py-3 rounded-md shadow-md animate-fade-in" role="alert" id="success-alert">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v4a1 1 0 102 0V7zm-1 7a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="pt-3">
            <div class="flex items-center bg-red-100 border border-red-400 text-red-700 px-6 py-3 rounded-md shadow-md animate-fade-in" role="alert" id="error-alert">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <table class="w-full table-auto border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 border">No</th>
                        <th class="px-4 py-2 border">Pegawai</th>
                        <th class="px-4 py-2 border">Alasan</th>
                        <th class="px-4 py-2 border">Tanggal Mulai</th>
                        <th class="px-4 py-2 border">Tanggal Selesai</th>
                        <th class="px-4 py-2 border">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leaves as $leave)
                        <tr>
                            <td class="border px-4 py-2">{{ $leave->id }} </td>
                            <td class="border px-4 py-2">{{ $leave->employee->first_name ?? ''}} {{ $leave->employee->last_name ?? ''}}</td>
                            <td class="border px-4 py-2">{{ $leave->reason }}</td>
                            <td class="border px-4 py-2">{{ $leave->start_date }}</td>
                            <td class="border px-4 py-2">{{ $leave->end_date }}</td>
                            <td class="border px-4 py-2">
                                <div class="flex space-x-2">
                                    <!-- Tombol View -->
                                    <a href="{{ route('leaves.show', $leave->id) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-green-500 text-white text-sm font-semibold rounded-md shadow hover:bg-green-600 transition duration-200">
                                        👁️ View
                                    </a>
                                    <!-- Tombol Delete -->
                                    <form action="{{ route('leaves.destroy', $leave->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-500 text-white text-sm font-semibold rounded-md shadow hover:bg-red-600 transition duration-200">
                                            🗑️ Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $leaves->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const successAlert = document.getElementById('success-alert');
        const errorAlert = document.getElementById('error-alert');

        if (successAlert) {
            setTimeout(() => {
                successAlert.style.transition = 'opacity 0.5s';
                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 500);
            }, 5000);
        }

        if (errorAlert) {
            setTimeout(() => {
                errorAlert.style.transition = 'opacity 0.5s';
                errorAlert.style.opacity = '0';
                setTimeout(() => errorAlert.remove(), 500);
            }, 5000);
        }
    });
</script>
