@extends('layouts.partials')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="row">
            <div class="col-md-6">
                <h1>Status Details</h1>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('admin.statuses.index') }}" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Status Information</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">ID</th>
                        <td>{{ $status->id }}</td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>{{ $status->name }}</td>
                    </tr>
                    <tr>
                        <th>Color</th>
                        <td>{{ $status->color }}</td>
                    </tr>
                    <tr>
                        <th>Preview</th>
                        <td>
                            <span class="badge" style="background-color: {{ $status->color }}">{{ $status->name }}</span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="box-footer">
                <a href="{{ route('admin.statuses.edit', $status->id) }}" class="btn btn-primary">Edit</a>
            </div>
        </div>
    </section>
</div>
@endsection