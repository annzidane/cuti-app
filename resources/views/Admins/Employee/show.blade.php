<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($employee) ? __('Edit Pegawai') : __('Tambah Pegawai') }}
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
            <form action="{{ isset($employee) ? route('employees.update', $employee->id) : route('employees.store') }}" method="POST">
                @csrf
                @if (isset($employee))
                    @method('PUT')
                @endif
                
                <div class="mb-4">
                    <label class="block text-gray-700">Nama Depan</label>
                    <input name="first_name" value="{{ old('first_name', isset($employee) ? $employee->first_name : '') }}" class="w-full border p-2 rounded" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Nama Belakang</label>
                    <input name="last_name" value="{{ old('last_name', isset($employee) ? $employee->last_name : '') }}" class="w-full border p-2 rounded" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Email</label>
                    <input name="email" type="email" value="{{ old('email', isset($employee) ? $employee->email : '') }}" class="w-full border p-2 rounded" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">No Handphone</label>
                    <input name="phone_number" value="{{ old('phone_number', isset($employee) ? $employee->phone_number : '') }}" class="w-full border p-2 rounded" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Alamat</label>
                    <input name="address" value="{{ old('address', isset($employee) ? $employee->address : '') }}" class="w-full border p-2 rounded" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Jenis Kelamin</label>
                    <select id="gender" name="gender" class="w-full border p-2 rounded" required>
                        <option value="Male" {{ (isset($employee) && $employee->gender == 'Male') ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ (isset($employee) && $employee->gender == 'Female') ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        {{ isset($employee) ? 'Update' : 'Simpan' }}
                    </button>
                    <a href="{{ route('employees.index') }}" class="ml-2 text-gray-600 underline">Kembali</a>
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
