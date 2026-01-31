@extends('layouts.app')

@section('content')
    @include('dashboard.overview')
    @include('dashboard.services')
    @include('dashboard.store')
    @include('dashboard.appointments')
@endsection
