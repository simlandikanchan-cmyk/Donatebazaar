@extends('layouts.app')

@section('content')

<style>
.vol-cv{max-width:900px;margin:0 auto;padding:48px 22px 70px;font-family:'Outfit',system-ui,sans-serif;color:#1f2233;}
.vol-cv h1{font-family:'Playfair Display',serif;font-size:30px;font-weight:600;margin-bottom:6px;}
.vol-cv .sub{color:#6b7188;font-size:14px;margin-bottom:26px;}
.vol-cv table{width:100%;border-collapse:collapse;background:#fff;border:1px solid rgba(20,20,40,.10);border-radius:16px;overflow:hidden;box-shadow:0 12px 30px rgba(76,29,149,.06);}
.vol-cv th{text-align:left;padding:13px 16px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6b7188;background:#f6f5ff;border-bottom:1px solid rgba(20,20,40,.10);}
.vol-cv td{padding:14px 16px;border-bottom:1px solid rgba(20,20,40,.07);font-size:14px;color:#3a3f55;vertical-align:top;}
.vol-cv tr:last-child td{border-bottom:none;}
.vol-cv .who{font-weight:600;color:#1f2233;}
.vol-cv .msg{color:#6b7188;font-size:13px;}
.vbadge{display:inline-block;font-size:11px;font-weight:700;padding:4px 10px;border-radius:20px;text-transform:capitalize;}
.vbadge.pending{background:#fef3c7;color:#b45309;}
.vbadge.approved{background:#dcfce7;color:#15803d;}
.vbadge.rejected{background:#fee2e2;color:#b91c1c;}
.vol-empty{background:#fff;border:1px solid rgba(20,20,40,.10);border-radius:16px;padding:40px;text-align:center;color:#6b7188;}
.vol-act{display:flex;gap:6px;}
.vol-act form{display:inline;}
.vbtn{display:inline-flex;align-items:center;gap:4px;padding:6px 12px;border:none;border-radius:8px;font-size:11px;font-weight:700;cursor:pointer;transition:transform .15s,box-shadow .15s;text-decoration:none;font-family:inherit;}
.vbtn:hover{transform:translateY(-1px);}
.vbtn-approve{background:#dcfce7;color:#15803d;}
.vbtn-approve:hover{box-shadow:0 3px 10px rgba(21,128,61,.25);}
.vbtn-reject{background:#fee2e2;color:#b91c1c;}
.vbtn-reject:hover{box-shadow:0 3px 10px rgba(185,28,28,.25);}
.vbtn-disabled{opacity:.4;cursor:not-allowed;}
</style>

<div class="vol-cv">
  <h1>Volunteer Applications</h1>
  <p class="sub">Campaign: {{ $campaign->title }}</p>

  @if($applications->isEmpty())
    <div class="vol-empty">No volunteer applications for this campaign yet.</div>
  @else
    <table>
      <thead>
        <tr><th>Volunteer</th><th>Message</th><th>Status</th><th>Applied</th><th>Actions</th></tr>
      </thead>
      <tbody>
        @foreach($applications as $app)
          <tr>
            <td class="who">{{ $app->volunteer->user->name ?? 'Volunteer #'.$app->volunteer_id }}</td>
            <td class="msg">{{ $app->message ?: '—' }}</td>
            <td><span class="vbadge {{ $app->status }}">{{ $app->status }}</span></td>
            <td>{{ $app->applied_at ? $app->applied_at->format('d M Y') : $app->created_at->format('d M Y') }}</td>
            <td>
              @if(auth()->user()->role === 'admin' && $app->status === 'pending')
                <div class="vol-act">
                  <form method="POST" action="{{ route('admin.volunteer.status', $app->id) }}">
                    @csrf
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" class="btn btn-green vbtn vbtn-approve">&#10003; Approve</button>
                  </form>
                  <form method="POST" action="{{ route('admin.volunteer.status', $app->id) }}">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <button type="submit" class="btn btn-red vbtn vbtn-reject">&#10007; Reject</button>
                  </form>
                </div>
              @else
                <span class="vbtn vbtn-disabled">—</span>
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
</div>

@endsection
