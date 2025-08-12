@extends('layouts.app')

@section('breadcrumb')
    Case > Edit Case
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-purple-600">edit</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">Edit Case</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Update the case details.</p>
                </div>
            </div>
        </div>
        <div class="p-4 md:p-6">
            @php(ob_start())
            @include('case-create')
            @php($content = ob_get_clean())
            {!! str_replace('Create Case', 'Update Case', $content) !!}
        </div>
    </div>
</div>
@endsection 