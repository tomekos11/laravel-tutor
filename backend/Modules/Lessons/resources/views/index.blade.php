@extends('lessons::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('lessons.name') !!}</p>
@endsection
