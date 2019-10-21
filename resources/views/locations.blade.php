@extends('layouts.app')

@section('content')
<div id="locations_list_app" class="container">
        <bounce-loader :loading="loading" :color="color" :size="size"></bounce-loader> 
    {{-- <locations-list :locations="locations"></locations-list> --}}
    {{-- <locations-in-location :locations="locations"></locations-in-location> --}}
    <router-link to="/locations"></router-link>
    <router-view></router-view>
</div>
@endsection
