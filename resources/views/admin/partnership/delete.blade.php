@extends('layouts.app')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap');

:root {
  --bg: #ffffffda; --surface: #f9f9faff;
  --border: rgba(255,255,255,0.07);
  --text: #000; --muted: #6b7280;
  --danger: #ef4444; --danger-bg: rgba(239,68,68,0.1);
  --accent: #6366f1; --accent-glow: rgba(99,102,241,0.25);
  --font: 'DM Sans', sans-serif; --mono: 'DM Mono', monospace;
}

body { font-family: var(--font); background: var(--bg); color: var(--text); }

.page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

/* Card */
.card {
  width: 100%;
  max-width: 520px;
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 16px;
  padding: 28px;
}

/* Header */
.title {
  font-size: 22px;
  font-weight: 600;
  color: var(--danger);
}

.sub {
  font-size: 13px;
  color: var(--muted);
  margin-top: 4px;
}

/* Warning box */
.warn {
  margin-top: 20px;
  background: var(--danger-bg);
  color: var(--danger);
  padding: 12px;
  border-radius: 10px;
  font-size: 12px;
  font-family: var(--mono);
}

/* Info */
.info {
  margin-top: 18px;
  background: #f3f4f6;
  padding: 14px;
  border-radius: 10px;
  font-size: 13px;
}

.info strong {
  display: inline-block;
  width: 100px;
  color: var(--muted);
}

/* Buttons */
.actions {
  margin-top: 24px;
  display: flex;
  gap: 10px;
}

.btn {
  flex: 1;
  padding: 11px;
  border-radius: 10px;
  font-size: 13px;
  font-weight: 500;
  border: none;
  cursor: pointer;
  transition: .15s;
}

/* Danger */
.btn-danger {
  background: var(--danger);
  color: #fff;
}

.btn-danger:hover {
  background: #dc2626;
  transform: translateY(-1px);
}

/* Cancel */
.btn-cancel {
  background: #e5e7eb;
  color: #374151;
}

.btn-cancel:hover {
  background: #d1d5db;
}

/* Back link */
.back {
  font-size: 12px;
  color: var(--accent);
  text-decoration: none;
  display: inline-block;
  margin-bottom: 10px;
}
</style>

<div class="page">

  <div class="card">

    <!-- Back -->
    <a href="{{ route('admin.partnership.index') }}" class="back">
      ← Back to list
    </a>

    <!-- Title -->
    <div class="title">Delete Partnership</div>
    <div class="sub">This action cannot be undone</div>

    <!-- Warning -->
    <div class="warn">
      ⚠️ You are about to permanently delete this partnership request.
    </div>

    <!-- Info -->
    <div class="info">
      <p><strong>Name:</strong> {{ $partnership->name ?? '-' }}</p>
      <p><strong>Email:</strong> {{ $partnership->email ?? '-' }}</p>
      <p><strong>Organization</strong> {{ $partnership->organization_name ?? '-' }}</p>
    </div>

    <!-- Actions -->
    <div class="actions">

      <form method="POST" action="{{ route('admin.partnership.delete',$partnership->id) }}" style="flex:1;">
        @csrf
        @method('DELETE')

        <button class="btn btn-danger">
          Yes, Delete
        </button>
      </form>

      <a href="{{ route('admin.partnership.index') }}" class="btn btn-cancel">
        Cancel
      </a>

    </div>

  </div>

</div>

@endsection