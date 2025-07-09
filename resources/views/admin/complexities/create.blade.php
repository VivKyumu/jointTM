@extends('layouts.partials')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Create New Complexity Level</h1>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Complexity Details</h3>
            </div>
            <form action="{{ route('admin.complexities.store') }}" method="POST">
                @csrf
                <div class="box-body">
                    <div class="form-group">
                        <label>Level (1-5)</label>
                        <input type="number" name="level" class="form-control" min="1" max="5" required>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Color Code</label>
                        <input type="color" name="color" class="form-control" value="#0073b7" required>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Create Level</button>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection