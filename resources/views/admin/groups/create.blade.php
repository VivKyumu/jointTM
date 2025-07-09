@extends('layouts.partials')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="row">
            <div class="col-md-6">
                <h1>Create New Group</h1>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('admin.groups.index') }}" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Group Details</h3>
            </div>
            <form action="{{ route('admin.groups.store') }}" method="POST">
                @csrf
                <div class="box-body">
                    <div class="form-group @error('name') has-error @enderror">
                        <label>Group Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="help-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Create Group
                    </button>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection