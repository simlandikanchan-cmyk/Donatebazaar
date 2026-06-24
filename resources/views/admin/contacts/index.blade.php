@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50 py-10 px-6">

<div class="max-w-7xl mx-auto">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <h1 class="text-3xl font-bold text-gray-900">
            Admin Contact Messages
        </h1>

        <!-- SEARCH -->
        <div class="flex items-center gap-3 w-full md:w-auto">
            <input 
                type="text" 
                id="searchInput"
                placeholder="Search name, email..."
                class="w-full md:w-72 px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none"
            >

            <!-- SUBJECT FILTER -->
            <select id="subjectFilter"
                class="px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                <option value="">All Subjects</option>
                @foreach($contacts->pluck('subject')->unique() as $subject)
                    <option value="{{ $subject }}">{{ $subject }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
    <div class="bg-green-100 text-green-700 p-4 mb-6 rounded-lg shadow">
        {{ session('success') }}
    </div>
    @endif

    <!-- TABLE -->
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">

        <table class="w-full text-sm">
            
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="p-4 text-left">Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>

            <tbody id="contactTable">

                @foreach($contacts as $c)
                <tr class="border-t hover:bg-gray-50 transition contact-row"
                    data-name="{{ strtolower($c->name) }}"
                    data-email="{{ strtolower($c->email) }}"
                    data-subject="{{ $c->subject }}">

                    <td class="p-4 font-medium text-gray-800">
                        {{ $c->name }}
                    </td>

                    <td class="text-gray-600">
                        {{ $c->email }}
                    </td>

                    <td>
                        <span class="px-3 py-1 bg-indigo-100 text-indigo-600 rounded-full text-xs">
                            {{ $c->subject }}
                        </span>
                    </td>

                    <td class="text-gray-500 truncate max-w-xs">
                        {{ $c->message }}
                    </td>

                    <td class="text-center">
                        <a href="/admin/contacts/delete/{{ $c->id }}"
                           class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-lg text-xs transition">
                            Delete
                        </a>
                    </td>

                </tr>
                @endforeach

            </tbody>

        </table>

    </div>

</div>
</div>

<!-- FILTER SCRIPT -->
<script>
    const searchInput = document.getElementById('searchInput');
    const subjectFilter = document.getElementById('subjectFilter');
    const rows = document.querySelectorAll('.contact-row');

    function filterTable() {
        const search = searchInput.value.toLowerCase();
        const subject = subjectFilter.value;

        rows.forEach(row => {
            const name = row.dataset.name;
            const email = row.dataset.email;
            const rowSubject = row.dataset.subject;

            const matchesSearch = name.includes(search) || email.includes(search);
            const matchesSubject = subject === "" || rowSubject === subject;

            if (matchesSearch && matchesSubject) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    searchInput.addEventListener('keyup', filterTable);
    subjectFilter.addEventListener('change', filterTable);
</script>

@endsection