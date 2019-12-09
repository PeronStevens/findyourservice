@extends('layouts.app')

@section('content')
    <services-component :auth="{{ Auth::user() ?? 0 }}" ></services-component>
@endsection