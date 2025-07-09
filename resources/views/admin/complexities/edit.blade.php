@extends('layouts.partials')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Edit Complexity Level</h1>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Complexity Details</h3>
            </div>
            <form action="{{ route('admin.complexities.update', $complexity->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="box-body">
                    <div class="form-group">
                        <label>Level (1-5)</label>
                        <input type="number" name="level" class="form-control" value="{{ $complexity->level }}" min="1" max="5" required>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $complexity->name }}" required>
                    </div>
                    <div class="form-group">
                        <label>Color Code</label>
                        <input type="color" name="color" class="form-control" value="{{ $complexity->color }}" required>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Update Level</button>
                    <a href="{{ route('admin.complexities.index') }}" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection