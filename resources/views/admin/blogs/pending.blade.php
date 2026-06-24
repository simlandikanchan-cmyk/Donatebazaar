@extends('layouts.admin')
@section('content')
<div class="p-8">
  <h2 class="text-2xl font-bold mb-6">Pending Review ({{ $blogs->total() }})</h2>
 
  @forelse($blogs as $blog)
    <div class="bg-white rounded-2xl shadow p-6 mb-4">
      <div class="flex justify-between items-start">
        <div class="flex-1">
          <h3 class="font-bold text-lg">{{ $blog->title }}</h3>
          <p class="text-sm text-gray-500 mt-1">
            By <strong>{{ $blog->author->name }}</strong>
            ({{ ucfirst($blog->author->role) }}) ·
            Submitted {{ $blog->updated_at->diffForHumans() }}
          </p>
          @if($blog->category)
            <span class="badge badge-blue mt-2">{{ $blog->category->name }}</span>
          @endif
          <p class="text-gray-600 mt-3 line-clamp-2">{{ $blog->excerpt_or_auto }}</p>
        </div>
        @if($blog->cover_image)
          <img src="{{ $blog->cover_image_url }}" class="w-24 h-20 object-cover rounded-lg ml-4">
        @endif
      </div>
 
      <div class="flex gap-3 mt-5 pt-4 border-t">
        <a href="{{ route('admin.blogs.show', $blog) }}" class="btn btn-ghost">Preview</a>
 
        <form method="POST" action="{{ route('admin.blogs.approve', $blog) }}">
          @csrf
          <button class="btn btn-success">✓ Approve</button>
        </form>
 
        <form method="POST" action="{{ route('admin.blogs.reject', $blog) }}"
              onsubmit="return promptReason(this)">
          @csrf
          <input type="hidden" name="reason" id="reject_reason_{{ $blog->id }}">
          <button class="btn btn-danger" data-id="{{ $blog->id }}">✗ Reject</button>
        </form>
      </div>
    </div>
  @empty
    <div class="text-center py-20 text-gray-400">
      <p class="text-5xl mb-4">✓</p>
      <p class="text-xl">No blogs pending review!</p>
    </div>
  @endforelse
 
  {{ $blogs->links() }}
</div>
 
@push('scripts')
<script>
function promptReason(form) {
  const id = form.querySelector('button[data-id]').dataset.id;
  const reason = prompt('Rejection reason (required):');
  if (!reason || !reason.trim()) return false;
  document.getElementById('reject_reason_' + id).value = reason;
  return true;
}
</script>
@endpush
@endsection