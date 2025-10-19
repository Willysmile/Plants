@extends('layouts.simple')

@section('title', 'Ajouter une plante')

@section('content')
  <div class="bg-white p-6 rounded shadow">
    <h1 class="text-xl font-bold mb-6">Ajouter une plante</h1>
    <x-plant-form :tags="$tags" />
  </div>
@endsection

@section('extra-scripts')
  <script src="{{ asset('js/form-validation.js') }}"></script>
  <script src="{{ asset('js/file-preview.js') }}"></script>
  <script src="{{ asset('js/app.js') }}"></script>
@endsection