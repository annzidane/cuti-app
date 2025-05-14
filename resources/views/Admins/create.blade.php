<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Cuti') }}
        </h2>
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

    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form action="{{ route('admins.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700">Pegawai</label>
                    <select id="employee_id" name="employee_id" class="w-full border p-2 rounded">
                        @foreach($pegawai as $p)
                            <option value="{{ $p->id }}">{{ $p->first_name }} {{ $p->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Alasan Cuti</label>
                    <textarea name="reason" class="w-full border p-2 rounded" required></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="w-full border p-2 rounded" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Tanggal Selesai</label>
                    <input type="date" name="end_date" class="w-full border p-2 rounded" required>
                </div>

                <div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Simpan
                    </button>
                    <a href="{{ route('admins.index') }}" class="ml-2 text-gray-600 underline">Kembali</a>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#employee_id').selectize();
        });
    </script>
    @endpush    
</x-app-layout>
