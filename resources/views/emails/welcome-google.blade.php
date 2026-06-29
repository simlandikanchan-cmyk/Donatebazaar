@component('mail::message')
# Welcome to DonateBazaar, {{ $user->name }}! 

Thank you for joining us with Google. Your account is ready.

You can now:
- Browse verified campaigns
- Make secure donations
- Track your impact

@component('mail::button', ['url' => url('/user/dashboard'), 'color' => 'success'])
Go to Dashboard
@endcomponent

Together we make a difference. 💜

**The DonateBazaar Team**
@endcomponent