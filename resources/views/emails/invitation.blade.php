{{-- resources/views/emails/invitation.blade.php (Markdown) --}}

<x-mail::message>
# You're invited to join {{ $invitation->colocation->name }}

You have been invited to join the colocation **{{ $invitation->colocation->name }}**.

<x-mail::button :url="route('invitations.accept', $invitation)">
    Join colocation
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
