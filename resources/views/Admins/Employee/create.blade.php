<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Pegawai') }}
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
            <form action="{{ route('employees.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700">Nama Depan</label>
                    <input name="first_name" class="w-full border p-2 rounded" ></input>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Nama Belakang</label>
                    <input name="last_name" class="w-full border p-2 rounded" ></input>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Email</label>
                    <input name="email" type="email" class="w-full border p-2 rounded" ></input>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">No Handphone</label>
                    <input name="phone_number" class="w-full border p-2 rounded" ></input>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Alamat</label>
                    <input name="address" class="w-full border p-2 rounded" ></input>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Jenis Kelamin</label>
                    <select id="gender" name="gender" class="w-full border p-2 rounded">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Simpan
                    </button>
                    <a href="{{ route('employees.index') }}" class="ml-2 text-gray-600 underline">Kembali</a>
                </div>
            </form>
        </div>
    </div> 
</x-app-layout>
