@component('mail::apiMessage')
# Verification

You received this email because you have registered an account in {{ config('app.name') }} .

@component('mail::button', ['url' => $url])
    Verify Your account
@endcomponent

Thanks,<br>
{{ config('app.name') }}

@slot('custom_footer')
    If above button doesn't work , please go to this url
    {{$url}}
@endslot
@endcomponent

