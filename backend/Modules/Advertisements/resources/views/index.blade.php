@extends('advertisements::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('advertisements.name') !!}</p>
@endsection
