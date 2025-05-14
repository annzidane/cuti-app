<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit leave') }}
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
            <form action="{{ route('leaves.update', $leave->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-700">Pegawai</label>
                    <select id="employee_id" name="employee_id" class="w-full border p-2 rounded">
                        @foreach($pegawai as $p)
                            <option value="{{ $p->id }}" {{ $leave->employee_id == $p->id ? 'selected' : '' }}>
                                {{ $p->first_name }} {{ $p->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Alasan leave</label>
                    <textarea name="reason" class="w-full border p-2 rounded">{{ $leave->reason }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="w-full border p-2 rounded"
                        value="{{ $leave->start_date }}">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Tanggal Selesai</label>
                    <input type="date" name="end_date" class="w-full border p-2 rounded"
                        value="{{ $leave->end_date }}">
                </div>

                <div>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        Perbarui
                    </button>
                    <a href="{{ route('leaves.index') }}" class="ml-2 text-gray-600 underline">Kembali</a>
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
