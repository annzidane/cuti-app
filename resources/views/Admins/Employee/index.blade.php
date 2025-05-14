<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daftar Pegawai') }}
            </h2>
            <a href="{{ route('employees.create') }}" class="bg-blue-500 text-black px-4 py-2 rounded hover:bg-blue-600">
                + Tambah Pegawai
            </a>
        </div>
    </x-slot>

    @if (session()->has('success'))
        <div class="pt-3">
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="pt-3">
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <table class="w-full table-auto border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 border">No</th>
                        <th class="px-4 py-2 border">Nama</th>
                        <th class="px-4 py-2 border">Tanggal Lahir</th>
                        <th class="px-4 py-2 border">Gender</th>
                        <th class="px-4 py-2 border">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $index => $employee)
                        <tr>
                            <td class="border px-4 py-2">{{ $loop->iteration + ($employees->currentPage() - 1) * $employees->perPage() }}</td>
                            <td class="border px-4 py-2">{{ $employee->first_name ?? '' }} {{ $employee->last_name ?? '' }}</td>
                            <td class="border px-4 py-2">{{ $employee->dob }}</td>
                            <td class="border px-4 py-2">{{ $employee->gender }}</td>
                            <td class="border px-4 py-2">
                                <div class="flex space-x-2">
                                    <!-- Tombol View -->
                                    <a href="{{ route('employees.show', $employee->id) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-green-500 text-white text-sm font-semibold rounded-md shadow hover:bg-green-600 transition duration-200">
                                        👁️ View
                                    </a>
                                    <!-- Tombol Delete -->
                                    <form action="{{ route('employees.destroy', $employee->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus pegawai ini?');">
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
                {{ $employees->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
