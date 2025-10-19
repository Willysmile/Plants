@extends('layouts.simple')

@section('title', 'Ajouter une plante')

@section('content')
  <div class="bg-white p-6 rounded shadow">
    <h1 class="text-xl font-bold mb-6">Ajouter une plante</h1>
    <x-plant-form :categories="$categories" :tags="$tags" />
  </div>
@endsection