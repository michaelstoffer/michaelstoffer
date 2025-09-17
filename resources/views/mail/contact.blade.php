@component('mail::message')
    # {{ $copy ? 'Your message' : 'New message received' }}

    **Name:** {{ $data['name'] }}
    **Email:** {{ $data['email'] }}
    **Topic:** {{ $data['topic'] ?? '—' }}
    **Link:** {{ $data['link'] ?? '—' }}

    ---

    {{ $data['message'] }}

@endcomponent
