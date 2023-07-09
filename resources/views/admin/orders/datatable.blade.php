@extends('layouts.admin_app')
 
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Manage Users</div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@endsection
 
@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
<script src = "http://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js" defer ></script>

