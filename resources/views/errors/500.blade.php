@extends('layouts.error')

@section('content')
    @component('components.errors')
        @slot('code')
            500
        @endslot

        @slot('message')
            Something went wrong
        @endslot
    @endcomponent

    @if(app()->bound('sentry') && !empty(Sentry::getLastEventID()))
        <div class="subtitle">Error ID: {{ Sentry::getLastEventID() }}</div>

        <!-- Sentry JS SDK 2.1.+ required -->
        <script src="https://cdn.ravenjs.com/3.3.0/raven.min.js"></script>

        <script>
            @php
                $name = $email = '';

                if($encrypt = \Cookie::get('last_logged_name')){
                    try{
                        $name = \Crypt::decrypt($encrypt);
                    }catch (\Exception $e){
                        //
                    }
                }

                if($encrypt = \Cookie::get('last_logged_email')){
                    try{
                        $email = \Crypt::decrypt($encrypt);
                    }catch (\Exception $e){
                        //
                    }
                }
            @endphp

            Raven.showReportDialog({
                eventId: '{{ Sentry::getLastEventID() }}',
                // use the public DSN (dont include your secret!)
                dsn: '{{ env('SENTRY_LARAVEL_PUBLIC') }}',
                user: {
                    'name': '{{ $name }}',
                    'email': '{{ $email }}',
                }
            });
        </script>
    @endif
@endsection