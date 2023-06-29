@extends('layouts.app')

@section('content')
    <login-component token_csrf="{{ @csrf_token() }}" xyz="546as4da6s"></login-component>
@endsection
