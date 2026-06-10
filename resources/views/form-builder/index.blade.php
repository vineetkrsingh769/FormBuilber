@extends('layouts.form-builder')

@section('title', 'Form Builder')

@section('content')
<div x-data="formBuilder" x-init="init()" x-cloak class="flex h-screen overflow-hidden">
    @include('form-builder.partials.sidebar')

    <div class="flex min-h-0 flex-1 flex-col overflow-hidden">
        @include('form-builder.partials.mobile-nav')
        @include('form-builder.partials.editor-header')
        @include('form-builder.tabs.editor')
        @include('form-builder.tabs.my-forms')
    </div>

    @include('form-builder.partials.editor-footer')
    @include('form-builder.partials.preview-modal')
    @include('form-builder.partials.schema-dialog')
    @include('form-builder.partials.toast')
</div>
@endsection
