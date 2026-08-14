@push('page_css')
@vite('resources/css/admin/entries/misc.css')
@endpush

@extends('layouts.admin')

@section('page_title', 'Contact Messages')
@section('page_subtitle', 'Manage visitor inquiries')
@section('sidebar_contacts', 'active')

@push('page_styles')
<style>
@media(max-width:480px){
  .max-w-7xl{padding:0 14px!important}
  .flex.flex-col.md\:flex-row{gap:12px!important}
  h1.text-3xl{font-size:clamp(18px,5vw,22px)!important}
  .md\:w-72{width:100%!important}
  .gap-3{gap:8px!important}
  .gap-4{gap:8px!important}
  .py-2{padding:6px 12px!important}
  .space-y-2{gap:6px!important}
}
@media(max-width:380px){
  .max-w-7xl{padding:0 10px!important}
  h1.text-3xl{font-size:clamp(16px,5vw,20px)!important}
  .px-4{padding:0 10px!important}
  .py-2{font-size:12px!important;height:34px!important}
  .rounded-lg{border-radius:6px!important}
  .overflow-x-auto{-webkit-overflow-scrolling:touch}
  table{font-size:12px!important}
  table th,table td{padding:6px 8px!important}
  .w-full.md\:w-auto{width:100%!important}
  .btn{width:100%!important;justify-content:center!important;font-size:12px!important;padding:8px 14px!important}
  .flex.items-center.gap-3{flex-wrap:wrap!important}
  .md\:flex-row{flex-direction:column!important;align-items:stretch!important}
  .md\:items-center{align-items:stretch!important}
  .md\:justify-between{gap:12px!important}
  .mb-8{margin-bottom:16px!important}
}
</style>
@endpush

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
                        <a href="{{ route('admin.contacts.delete', $c->id) }}"
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