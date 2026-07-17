<?php
$u = \App\Models\User::where('email','like','%admin%')->first();
if ($u) { echo "EMAIL=".$u->email."\n"; } else { echo "NONE\n"; }
$d = \App\Models\User::first();
if ($d) { echo "FIRST=".$d->email."\n"; }
