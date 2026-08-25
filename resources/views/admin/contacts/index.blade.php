@push('page_css')
@vite('resources/css/admin/entries/misc.css')
@endpush

@extends('layouts.admin')

@section('page_title', 'Contact Messages')
@section('page_subtitle', 'Manage visitor inquiries')
@section('sidebar_contacts', 'active')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <h1 class="text-3xl font-bold text-gray-900">
            Admin Contact Messages
        </h1>
        <div class="flex items-center gap-3 w-full md:w-auto">
            <input type="text" id="searchInput" placeholder="Search name, email..."
                class="w-full md:w-72 px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none">
            <select id="subjectFilter"
                class="px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                <option value="">All Subjects</option>
                @foreach($contacts->pluck('subject')->unique() as $subject)
                    <option value="{{ $subject }}">{{ $subject }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
        <div style="overflow-x:auto;">
        <table class="w-full text-sm" style="min-width:560px;">
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
                    <td class="p-4 font-medium text-gray-800">{{ $c->name }}</td>
                    <td class="text-gray-600">{{ $c->email }}</td>
                    <td>
                        <span class="px-3 py-1 bg-indigo-100 text-indigo-600 rounded-full text-xs">{{ $c->subject }}</span>
                    </td>
                    <td class="text-gray-500 truncate max-w-xs">{{ $c->message }}</td>
                    <td class="text-center">
                        <form method="POST" action="{{ route('admin.contacts.destroy', $c->id) }}" style="display:inline;" onsubmit="return confirm('Delete this message? This cannot be undone.');">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-lg text-xs transition">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
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
        row.style.display = (matchesSearch && matchesSubject) ? "" : "none";
    });
}

searchInput.addEventListener('keyup', filterTable);
subjectFilter.addEventListener('change', filterTable);
</script>
@endpush
